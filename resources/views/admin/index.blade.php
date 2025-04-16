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


#notificationTable {
    width: 33.33%;
}


</style>
<div class="container-fluid mt-4">  
    <div class="row align-items-stretch">  
        <!-- Notifications Section -->  
        <div class="col-9 mb-2">  
            <div class="card p-3">  
                <h4 class="card-title mb-3">Notifications</h4>  
                <div class="table-responsive">  
                    <table class="table table-bordered table-hover" style="font-size: 14px; width: 100%;" id="notificationTable">  
                        <thead class="thead-light">  
                            <tr>  
                                <th style="width: 18%;">Date/Time</th>
                                <th style="width: 12%;">Control #</th> 
                                <th style="width: 70%;">Message</th>  
                            </tr>  
                        </thead>  
                        <tbody>  
                            @foreach ($notifications as $notification)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($notification->created_at)->format('F d, Y h:i A') }}</td>
                                    <td>{{ $notification->control_number }}</td>
                                    <td class="text-left">
                                        {{ $notification->message }} Status |
                                        @if ($notification->status === "Pending")
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @else
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Issued
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
        {{-- <div class="col-md-3 mb-2">  
            <div class="card">  
                <h4 class="card-title">Bar Graph</h4>  <br>
                <canvas id="transactionChart"></canvas>
            </div>  
        </div>   --}}

        <div class="col-md-3 mt-3">  
            <div class="row">  
                <div class="col-md-6 mb-4">  
                    <div class="card">  
                        <h4 class="card-title">Users</h4>  
                        <a href="{{ url('/admin/lookup-tables') }}?section=accounts"><i class="fas fa-users icon" style="font-size: 40px; color: #007bff; margin-right: 10px;"></i></a>  
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
                        <a href="{{ url('/admin/transaction') }}"><i class="fas fa-receipt icon" style="color: #28a745; font-size: 40px;"></i>  </a>
                        
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
                        <a href="{{ url('/admin/lookup-tables') }}?section=items"><i class="fas fa-box icon" style="color: #ffc107; font-size: 40px;"></i></a>  
                       
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
                        <a href="{{ url('/admin/lookup-tables') }}?section=deliveries"><i class="fas fa-shopping-cart icon" style="color: #dc3545; font-size: 40px;"></i></a>  
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
        <div class="row mb-3">
            <div class="row mb-3">
                <div class="col-md-4 d-flex align-items-center">
                    <label for="category-filter" class="mb-0 mr-2" style="white-space: nowrap;">Filter By Category:</label>
                    <select id="category-filter" class="form-control">
                        <option value="">All</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
        </div>
        
        <!-- Scrollable wrapper -->
        <div>
            <table id="availableItemTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="30%">Category</th>
                        <th width="30%">Item Name</th>
                        <th width="5%">Issued</th>
                        <th width="7%">Stock On Hand</th>
                        <th width="10%" class="text-center">Stock Level</th>
                    </tr>
                </thead>
                <tbody style="max-height: 200px; overflow-y: auto;">
                    @foreach ($itemsWithTransactionSums as $itemData)
                        <tr>
                            <td>{{ $itemData['item']->category->name }}</td>
                            <td>{{ $itemData['item']->name }}</td>
                            <td>{{ $itemData['total_transaction_sum'] }}</td> <!-- Display the total transaction sum -->
                            <td class="text-right">{{ $itemData['item']->inventory->quantity }}</td>
                            <td  class="text-center">
                                @if($itemData['item']->inventory->quantity == 0)
                                    <span class="badge badge-noStock"><i class="fas fa-times-circle"></i> No Stock</span>
                                @elseif ($itemData['item']->inventory->quantity <= 20) 
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
{{-- <script>
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
    }); --}}
</script> 
@endsection  
