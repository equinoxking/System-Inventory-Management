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
        }
        .slider-container {
            position: relative;
            overflow: hidden;
            width: 100%;
        }
        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
            overflow-x: hidden;
        }
        .chart-card {
            flex: 0 0 20%;
            padding: 10px;
            min-width: 300px;
        }
        .chart-container {
            width: 100%;
            height: 250px;
        }
        .arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            z-index: 10;
        }
        .arrow-left {
            left: 0;
        }
        .arrow-right {
            right: 0;
        }
    </style>
</head>
<body>

<div class="container-fluid mt-4">
    @for ($cat = 1; $cat <= 10; $cat++) <!-- Loop for Multiple Categories -->
        <div class="category-title">Category {{ $cat }}</div>
        
        <div class="slider-container">
            <button class="arrow arrow-left" onclick="slide(-1, {{ $cat }})">&#9665;</button>
            <div class="slider" id="graphSlider{{ $cat }}">
                <!-- Graphs will be injected here -->
            </div>
            <button class="arrow arrow-right" onclick="slide(1, {{ $cat }})">&#9655;</button>
        </div>
    @endfor
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const categories = 10; // Total number of categories
        const chartsPerCategory = 10; // Number of charts per category

        for (let cat = 1; cat <= categories; cat++) {
            const slider = document.getElementById(`graphSlider${cat}`);

            for (let i = 1; i <= chartsPerCategory; i++) {
                let chartCard = document.createElement("div");
                chartCard.className = "chart-card";
                chartCard.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="chart${cat}_${i}"></canvas>
                            </div>
                        </div>
                    </div>
                `;
                slider.appendChild(chartCard);
            }
        }

        // Initialize Charts
        for (let cat = 1; cat <= categories; cat++) {
            for (let i = 1; i <= chartsPerCategory; i++) {
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
    });

    let currentIndex = {};
    function slide(direction, cat) {
        const slider = document.getElementById(`graphSlider${cat}`);
        const cards = document.querySelectorAll(`#graphSlider${cat} .chart-card`);
        const cardWidth = cards[0].offsetWidth + 20; // Width + padding
        const maxIndex = cards.length - 5; // Show 5 at a time

        if (!currentIndex[cat]) currentIndex[cat] = 0;
        currentIndex[cat] += direction;
        if (currentIndex[cat] < 0) currentIndex[cat] = 0;
        if (currentIndex[cat] > maxIndex) currentIndex[cat] = maxIndex;

        slider.style.transform = `translateX(-${currentIndex[cat] * cardWidth}px)`;
    }
</script>

</body>
</html>

@endsection
