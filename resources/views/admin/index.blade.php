@extends('admin.layout.admin-layout')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .category-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 20px 0 10px;
            padding-left: 15px;
            cursor: pointer;
        }
        .chart-card {
            flex: 0 0 20%;
            padding: 10px;
            min-width: 300px;
            margin-bottom: 20px; /* Add space between charts */
        }
        .chart-container {
            width: 100%;
            height: 250px;
        }
    </style>
</head>
<body>

<div class="container-fluid mt-4">
    <!-- Month Filter for selection -->
    <div class="mb-3">
        <label for="monthSelect" class="form-label">Select Month</label>
        <select class="form-select" id="monthSelect" onchange="showCategoryGraphs()">
            <option value="">Select Month</option>
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>
    </div>

    <!-- Category Dropdown for selection -->
    <div class="mb-3">
        <label for="categorySelect" class="form-label">Select Category</label>
        <select class="form-select" id="categorySelect" onchange="showCategoryGraphs()">
            @php
                $month = session('selected_month', null);
                $categoryUsage = session('category_usage', []);
                $categories = range(1, 10);

                if ($month && isset($categoryUsage[$month])) {
                    // Sort categories based on usage for the selected month
                    usort($categories, function ($a, $b) use ($categoryUsage, $month) {
                        $usageA = $categoryUsage[$month][$a] ?? 0;
                        $usageB = $categoryUsage[$month][$b] ?? 0;
                        return $usageB - $usageA; // Sort in descending order (most used first)
                    });
                }
            @endphp

            @foreach ($categories as $cat)
                <option value="{{ $cat }}" @if ($loop->first) selected @endif>Category {{ $cat }}</option>
            @endforeach
        </select>
    </div>

    <!-- Graphs will be shown after selecting a category -->
    @for ($cat = 1; $cat <= 10; $cat++) <!-- Loop for Graph Containers -->
        <div class="slider-container" id="sliderContainer{{ $cat }}" style="display: none;">
            <div class="row">
                <div class="col-12">
                    <h3>Category {{ $cat }}</h3>
                </div>
                <!-- Graphs for selected category -->
                <div class="col-12">
                    <div class="d-flex flex-wrap">
                        @for ($i = 1; $i <= 10; $i++) <!-- Loop for graphs inside each category -->
                            <div class="chart-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chart{{ $cat }}_{{ $i }}"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    @endfor
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const categories = 10; // Total number of categories
        const chartsPerCategory = 10; // Number of charts per category

        // Initialize Charts
        for (let cat = 1; cat <= categories; cat++) {
            const slider = document.getElementById(`sliderContainer${cat}`);

            // Create chart cards dynamically
            for (let i = 1; i <= chartsPerCategory; i++) {
                // Initialize Chart.js for each chart
                new Chart(document.getElementById(`chart${cat}_${i}`), {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                        datasets: [{
                            label: `Graph ${i} (Category ${cat})`,
                            data: Array.from({ length: 5 }, () => Math.floor(Math.random() * 30)),
                            backgroundColor: ['#D84040', '#205781', '#FFCF50', '#C7DB9C', '#D69ADE']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
        }

        // Show the most commonly used category by default
        const categorySelect = document.getElementById("categorySelect");
        categorySelect.value = categorySelect.options[0].value; // Automatically select the most used category (first in sorted list)
        showCategoryGraphs(); // Display graphs for the first selected category
    });

    // Show selected category graphs
    function showCategoryGraphs() {
        const selectedCategory = document.getElementById("categorySelect").value;
        const selectedMonth = document.getElementById("monthSelect").value;

        // Update the selected month in session storage
        updateSelectedMonth(selectedMonth);

        // Update category usage for the selected month
        updateCategoryUsage(selectedCategory, selectedMonth);

        // Hide all sliders initially
        for (let i = 1; i <= 10; i++) {
            document.getElementById(`sliderContainer${i}`).style.display = 'none';
        }

        // If a category is selected, show its corresponding graphs
        if (selectedCategory) {
            document.getElementById(`sliderContainer${selectedCategory}`).style.display = 'block';
        }
    }

    // Update the selected month in session storage
    function updateSelectedMonth(month) {
        fetch('/update-selected-month', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ month: month })
        });
    }

    // Update the usage frequency of selected categories for the selected month in session storage
    function updateCategoryUsage(categoryId, month) {
        // Get the current usage data from the session
        let categoryUsage = @json(session('category_usage', [])); // Pass session data to JavaScript

        // Increment the usage for the selected category for the selected month
        if (!categoryUsage[month]) {
            categoryUsage[month] = {};
        }

        if (!categoryUsage[month][categoryId]) {
            categoryUsage[month][categoryId] = 0;
        }

        categoryUsage[month][categoryId]++;

        // Update the session with the new usage data
        fetch('/update-category-usage', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ category_usage: categoryUsage })
        });
    }
</script>

</body>
</html>

@endsection
