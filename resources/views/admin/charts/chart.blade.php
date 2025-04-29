@extends('admin.layout.admin-layout')
@section('content')
<div class="container-fluid mt-3 mb-3">
    <div class="row align-items-center">
        <div class="col-md-12">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Reports</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4><strong>Reports</strong></h4>
        </div>
    </div>
</div>
<div class="container-fluid mt-4">
    <div class="row g-3">  
        <!-- Full-width chart -->
        <div class="col-md-4">
            <div class="card p-3">
                <canvas id="transactionChart" height="300"></canvas>
            </div>
        </div>

        <!-- Smaller summary chart -->
        <div class="col-md-4">
            <div class="card p-3">
                <canvas id="monthlySummaryChart" height="300"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <canvas id="itemStockChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="row g-3 mt-3">  
        <!-- Full-width chart -->
        <div class="col-md-4">
            <div class="card p-3">
                <canvas id="itemIssuedChart" height="300"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <canvas id="topIssuedItemsChart" height="300"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <canvas id="inventoryChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Transaction Summary Chart
const labels = @json($labels1);
const data = @json($data1);

const ctx = document.getElementById('transactionChart').getContext('2d');
const transactionChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Summary of Transactions for {{ now()->format("F Y") }}',
            data: data,
            backgroundColor: [
                'rgba(169, 169, 169, 0.2)',  // Light Gray (Review)
                'rgba(40, 167, 69, 0.2)',  // Light Green (Ready for Release)
                'rgba(54, 162, 235, 0.2)',  // Light Blue (Released)
                'rgba(255, 205, 86, 0.2)',  // Light Yellow (Completed)
                'rgba(255, 99, 132, 0.2)',  // Light Red (Rejected)
                'rgba(255, 165, 0, 0.2)'   // Light Orange (Canceled)
            ],
            borderColor: [
                'rgba(169, 169, 169, 1)',  // Dark Gray (Review)
                'rgba(40, 167, 69, 1)',    // Dark Green (Ready for Release)
                'rgba(54, 162, 235, 1)',   // Dark Blue (Released)
                'rgba(255, 205, 86, 1)',   // Dark Yellow (Completed)
                'rgba(255, 99, 132, 1)',   // Dark Red (Rejected)
                'rgba(255, 165, 0, 1)'     // Dark Orange (Canceled)
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Summary of Transactions for {{ now()->format("F Y") }}',
                font: { size: 16 }
            },
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 5
                }
            },
            x: {
                display: true // show or hide as needed
            }
        }
    }
});


// Monthly Summary Chart
const ctx2 = document.getElementById('monthlySummaryChart').getContext('2d');
const monthlySummaryChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: [@json($month)],
        datasets: [
            {
                label: 'Total Delivered',
                data: [{{ $totalDelivered }}],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',  // soft blue
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            },
            {
                label: 'Total Transactions',
                data: [{{ $completedTransactionsThisMonth }}],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',  // soft red
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            },
            x: {
                display: true
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Monthly Summary for {{ $month }}',
                font: { size: 16 }
            },
            legend: {
                display: true,
                position: 'top'
            }
        }
    }
});

// Item Stock vs Issued Chart
const inventoryDataMonthly = @json($chartDataMonthly);

const chartConfigMonthly = {
    labels: inventoryDataMonthly.labelItems,
    datasets: [{
        label: 'Inventory Overview',
        data: [inventoryDataMonthly.issuedData, inventoryDataMonthly.currentStock],  // <-- This is correct as an array of numbers
        backgroundColor: ['rgba(255, 205, 86, 0.2)', 'rgba(255, 165, 0, 0.2)'],
        borderColor: ['rgba(255, 165, 0, 1)', 'rgba(255, 165, 0, 1)'],
        borderWidth: 1
    }]
};

const ctxb2 = document.getElementById('itemStockChart').getContext('2d');
const itemStockChart = new Chart(ctxb2, {
    type: 'bar',
    data: chartConfigMonthly, // <-- Corrected to match the config name
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 5
                }
            },
            x: {
                display: true // You can hide if you want, but true helps with debugging
            }
        },
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Issued and Inventory Summary {{ now()->format('F Y') }}',
                font: { size: 16 }
            }
        },
    }
});

// Top 10 Issued Items This Month
const itemNames = @json($itemNames);
const itemIssued = @json($itemIssued);

const ctx4 = document.getElementById('itemIssuedChart').getContext('2d');
const itemIssuedChart = new Chart(ctx4, {
    type: 'bar',
    data: {
        labels: itemNames,
        datasets: [{
            label: 'Total Issued (This Month)',
            data: itemIssued,
            backgroundColor: 'rgba(40, 167, 69, 0.2)',
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Top 10 Commonly Used Items for {{ now()->format('F Y') }}',
                font: { size: 16 }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 5
                }
            },
            x: {
                display: false // hides the bottom labels
            }
        }
    }
});

// Top 10 Issued Items All Time
const topItemsNames = @json($topItemsNames);
const topItemsIssuedQty = @json($topItemsIssuedQty);

const chartCtx = document.getElementById('topIssuedItemsChart').getContext('2d');
const topIssuedItemsChart = new Chart(chartCtx, {
    type: 'bar',
    data: {
        labels: topItemsNames,
        datasets: [{
            label: 'Total Issued (All Time)',
            data: topItemsIssuedQty,
            backgroundColor: 'rgba(169, 169, 169, 0.2)',
            borderColor: 'rgba(169, 169, 169, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Top 10 Commonly Used Items',
                font: { size: 16 }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 5
                }
            },
            x: {
                display: false // hides the bottom labels
            }
        }
    }
});
// Get the chart data passed from the Laravel controller
const inventoryData = @json($chartData);
const chartConfig = {
    labels: inventoryData.labels,  // Labels for the chart (such as "Issued Quantity", "Remaining Inventory")
    datasets: [{
        label: 'Inventory Overview',  // Label for the chart legend
        data: [inventoryData.issuedQuantity, inventoryData.remainingInventory],  // Data for the chart (issued vs remaining)
        backgroundColor: ['rgba(169, 169, 169, 0.2)', 'rgba(54, 162, 235, 0.2)'],  // Colors for the bars
        borderColor: ['rgba(169, 169, 169, 1)', 'rgba(54, 162, 235, 1)'],  // Border colors for the bars
        borderWidth: 1  // Border width for each bar
    }]
};

// Create the chart
var ctxb = document.getElementById('inventoryChart').getContext('2d');
var inventoryChart = new Chart(ctxb, {
    type: 'bar',  // Type of chart (bar, line, etc.)
    data: chartConfig,  // Use the chart configuration
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 5
                }
            },
            // x: {
            //     display: false
            // }
        },
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Overall Summary of Issued and Inventory',
                font: { size: 16 }
            }
        },
    }
});

</script> 
@endsection
