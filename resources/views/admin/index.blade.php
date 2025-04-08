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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            text-align: center;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            height: 100%;
            position: relative;
        }
        .card-title {
            position: absolute;
            top: 10px;
            left: 15px;
            font-size: 16px;
            font-weight: bold;
            color: #6c757d;
        }
        .icon {
            font-size: 40px;
            margin-top: 40px;
        }
        .table-container {
            max-height: 200px;
            overflow-y: auto;
        }
        .chart-container {
            width: 100%;
            height: 100%;
        }
        .mb-4 {
            margin-bottom: 1.5rem;
        }
        .data-table-section {
            margin-top: 30px;
            padding: 15px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="row align-items-stretch">
        <!-- Notifications Section -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <h5 class="card-title">Notifications</h5>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date/Time</th>
                                <th>Message</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>12/03/2025</td>
                                <td>New user registered</td>
                                <td><button class="btn btn-primary btn-sm">View</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pie Chart Section -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <h5 class="card-title">Pie Graph</h5>
                <canvas id="pieChart" class="chart-container"></canvas>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="row h-100">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <h5 class="card-title">Users</h5>
                        <i class="fas fa-users icon"></i>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <h5 class="card-title">Transactions</h5>
                        <i class="fas fa-receipt icon"></i>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <h5 class="card-title">Items</h5>
                        <i class="fas fa-box icon"></i>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <h5 class="card-title">Request</h5>
                        <i class="fas fa-shopping-cart icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Section -->
    <div class="data-table-section">
        <h2 class="text-center mb-4">Item List</h2>
        <table id="itemTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example Data -->
                <tr>
                    <td>Electronics</td>
                    <td>Smartphone, Laptop, Tablet, Smartwatch, Headphones, Speaker, Camera, Microphone, Printer, Drone</td>
                </tr>
                <tr>
                    <td>Furniture</td>
                    <td>Sofa, Dining Table, Chair, Coffee Table, Wardrobe, Bookshelf, Bed Frame, Desk, Cabinet, Recliner</td>
                </tr>
                <tr>
                    <td>Clothing</td>
                    <td>T-Shirt, Jeans, Dress, Jacket, Sweater, Shorts, Skirt, Coat, Socks, Shoes</td>
                </tr>
                <!-- Add up to 20 categories -->
            </tbody>
        </table>
    </div>
</div>

<script>
    // Initialize DataTable for Item List
    $(document).ready(function() {
        $('#itemTable').DataTable();
    });

    // Initialize Pie Chart
    document.addEventListener("DOMContentLoaded", function () {
        new Chart(document.getElementById("pieChart"), {
            type: 'pie',
            data: {
                labels: ["Received", "Released", "Approved", "Canceled"],
                datasets: [{
                    data: [12, 19, 3, 5],
                    backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0"]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
</body>
</html>
@endsection
