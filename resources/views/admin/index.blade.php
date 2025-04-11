@extends('admin.layout.admin-layout')  
@section('content')  
<style>
    /* Highlight class for selected category */

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
    font-size: 45px;  
    margin-top: 40px;  
}  
.table-container {  
    max-height: 200px;  
    overflow-y: auto;  
}  
.chart-container {  
    width: 100%;  
    height: 200%;  
}  
.mb-4 {  
    margin-bottom: 1.5rem;  
}  
.data-table-section {  
    margin-top: 35px;  
    padding: 15px;  
    background-color: #ffffff;  
    border: 1px solid #ddd;  
    border-radius: 5px;  
}  
.icon-number {
    font-size: 30px;  
    font-weight: bold;  
    color: #343a40;
    margin-top: 15px;  
}
.table-scroll-wrapper {
    max-height: 200px;
    overflow-y: auto;
}

.table-scroll-wrapper table {
    margin: 0;
}

/* Optional: Match column widths */
#availableItemTable th,
.table-scroll-wrapper td {
    width: 33.33%;
}
</style>
<div class="container-fluid mt-4">  
    <div class="row align-items-stretch">  
        <!-- Notifications Section -->  
        <div class="col-md-5 mb-2">  
            <div class="card">  
                <h4 class="card-title">Notifications</h4>  
                <div>  
                    <table class="table" style="font-size: 12px" id="notificationTable">  
                        <thead>  
                            <tr>  
                                <th>Date/Time</th>
                                <th>Control Number</th> 
                                <th>Message</th>  
                            </tr>  
                        </thead>  
                        <tbody>  
                            @foreach ($notifications as $notification)
                                <tr>
                                    <td style="font-size: 11px"> {{  \Carbon\Carbon::parse($notification->created_at)->format('F d, Y H:i A') }}</td>
                                    <td>{{ $notification->control_number }}</td>
                                    <td  class="text-left" style="font-size: 11px">{{ $notification->message }}
                                        Status |
                                        @if ($notification->status === "Pending")
                                            <span class="badge badge-pending">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @else
                                            <span class="badge badge-release">
                                                <i class="fas fa-check"></i> Accepted
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>  
                    </table>  
                </div>  
            </div>  
        </div>  

        <!-- Bar Chart Section -->  
        <div class="col-md-3 mb-2">  
            <div class="card">  
                <h4 class="card-title">Bar Graph</h4>  <br>
                <canvas id="transactionChart"></canvas>
            </div>  
        </div>  

        <div class="col-md-4 mt-3">  
            <div class="row">  
                <div class="col-md-6 mb-4">  
                    <div class="card">  
                        <h4 class="card-title">Users</h4>  
                        <i class="fas fa-users icon" style="font-size: 40px; color: #007bff; margin-right: 10px;"></i>   
                        <div class="icon-number">
                            @if ($clients >= 0 && $clients <= 9)
                                0{{ $clients }}
                            @else
                                {{ $clients }}
                            @endif
                        </div> 
                    </div>  
                </div>  
                <div class="col-md-6 mb-4">  
                    <div class="card">  
                        <h4 class="card-title">Transactions</h4>  
                        <i class="fas fa-receipt icon" style="color: #28a745; font-size: 40px;"></i>  
                        <div class="icon-number">
                            @if ($transactions >= 0 && $transactions <= 9)
                                0{{ $transactions }}
                            @else
                                {{ $transactions }}
                            @endif
                        </div>
                    </div>  
                </div>  
                <div class="col-md-6 mb-4">  
                    <div class="card">  
                        <h4 class="card-title">Items</h4>  
                        <i class="fas fa-box icon" style="color: #ffc107; font-size: 40px;"></i> 
                        <div class="icon-number">
                            @if ($itemCount >= 0 && $itemCount <= 9)
                                0{{ $itemCount }}
                            @else
                                {{ $itemCount }}
                            @endif
                        </div>
                    </div>  
                </div>  
                <div class="col-md-6 mb-4">  
                    <div class="card">  
                        <h4 class="card-title">Deliveries</h4>  
                        <i class="fas fa-shopping-cart icon" style="color: #dc3545; font-size: 40px;"></i>
                        <div class="icon-number">
                            @if ($receives >= 0 && $receives <= 9)
                                0{{ $receives }}
                            @else
                                {{ $receives }}
                            @endif
                        </div> 
                    </div>  
                </div>  
            </div>  
        </div>
          
    </div>  

    <!-- Data Table Section -->  
    <div class="data-table-section">
        <h2 class="text-center">Item List</h2>
        <div class="d-flex align-items-center">
            <div class="col-md-2 form-group text-right">
                <label for="category-filter">Filter By Category: </label>
            </div>
            <div class="col-md-2">
                <select id="category-filter" class="form-control">
                    <option value="">All</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                </select>
            </div>
        </div>
        <!-- Scrollable wrapper -->
        <div>
            <table id="availableItemTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Stock On Hand</th>
                        <th>Item Name</th>
                        <th>Stock Level</th>
                    </tr>
                </thead>
                <tbody style="max-height: 200px; overflow-y: auto;">
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item->category->name }}</td>
                            <td class="text-right">Availability ({{ $item->inventory->quantity }})</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                @if($item->inventory->quantity == 0)
                                    <span class="badge badge-noStock"><i class="fas fa-times-circle"></i> No Stock</span>
                                @elseif ($item->inventory->quantity <= 20) 
                                    <span class="badge badge-noStock"><i class="fas fa-times-circle"></i> Critical</span>
                                @else 
                                    <span class="badge badge-highStock"><i class="fas fa-check-circle"></i> Normal</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('transactionChart').getContext('2d');
    var transactionChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels), // labels like Completed, Rejected, etc.
            datasets: [{
                label: 'Summary of Transactions',
                data: @json($data), // the count data from the database
                backgroundColor: [
                    'rgba(153, 102, 255, 0.2)', // For Review
                    'rgba(75, 192, 192, 0.2)', // For Released
                    'rgba(54, 162, 235, 0.2)', // Completed
                    'rgba(255, 99, 132, 0.2)', // Rejected
                    'rgba(255, 159, 64, 0.2)'  // Canceled
                ],
                borderColor: [
                    'rgba(153, 102, 255, 1)', // For Review
                    'rgba(75, 192, 192, 1)', // For Released
                    'rgba(54, 162, 235, 1)', // Completed
                    'rgba(255, 99, 132, 1)', // Rejected
                    'rgba(255, 159, 64, 1)'  // Canceled
                ],
                borderWidth: 1
            }]
        },
        options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    // Round the y-axis ticks to integer values
                    callback: function(value) {
                        return Math.floor(value); // Rounds down the numbers to the nearest integer
                    }
                }
            }
        }
    }
    });
</script> 
@endsection  
