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
<!-- Bootstrap Modal -->

  
{{-- <div class="container-fluid card w-100 shadow rounded p-4"  >
    <!-- Form Header -->
   
    <!-- Form Body -->
    
</div> --}}
<div class="container-fluid mt-4">  
    <div class="row align-items-stretch">  
        <!-- Notifications Section -->  
        
        <div class="col-9 mb-2"> 
            <div class="card p-3">  
                <div class="table-responsive"  style=" position: absolute; top: 0px; height: 100%; width: 90%">  
                    <div class="text-left mt-2">
                        <button id="toggleTableBtn" class="btn btn-sm btn-info  mb-3">
                            Show Critical Items
                        </button>                        
                    </div>
                    <h4 class="mt-3" id="tableTitle">Top 10 Commonly Used Items for this Month</h4>
                    <div id="top10Container">
                        <table class="table table-bordered table-hover" style="font-size: 13px; width: 100%;" id="top10Table">  
                            <thead class="thead-light">  
                                <tr>  
                                    <th style="width: 5%; text-align:left">No.</th>
                                    <th style="width: 30%; text-align:left">Category</th>
                                    <th style="width: 45%; text-align:left">Item Name</th>
                                    <th style="width: 10%">Stock on Hand</th>
                                    <th style="width: 5%;">Issued</th>  
                                    <th style="width: 5%;">Request Frequency</th>  
                                </tr>  
                            </thead>  
                            <tbody>  
                                @foreach ($top10IssuedItems as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}.</td>
                                    <td  class="text-left">{{ $data['item']->category->name }}</td>
                                    <td class="text-left">{{ $data['item']->name }}</td>
                                    <td  class="text-right">{{ $data['item']->inventory->quantity }}</td>
                                    <td>{{ $data['total_issued'] }}</td>
                                    <td>{{ $data['request_count'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>  
                        </table>
                    </div>
                    <div id="availableItemContainer" style="display: none;">
                        <table id="availableItemTable" class="table table-bordered table-hover" style="font-size: 11px;">
                            <thead class="thead-light">  
                                <tr>
                                    <th width="30%">Category</th>
                                    <th width="30%">Item Name</th>
                                    <th width="5%">Issued</th>
                                    <th width="7%">Stock On Hand</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($criticalItemsWithSums as $itemData)
                                    <tr class="{{ $itemData['item']->inventory->quantity == 0 ? 'table-danger text-dark' : '' }}">
                                        <td>{{ $itemData['item']->category->name }}</td>
                                        <td>{{ $itemData['item']->name }}</td>
                                        <td>{{ number_format($itemData['total_transaction_sum']) }}</td>
                                        <td class="text-right">{{ $itemData['item']->inventory->quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>  
            </div>  
        </div> 
        <div class="col-md-3 mt-3">  
            <div class="row">  
                <div class="col-md-6 mb-4">  
                    <div class="card">
                        <h4 class="card-title">Registered Users</h4>
                    
                        <!-- Users Icon with Dropdown -->
                        <div class="dropdown" style="display: inline-block;">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-users icon" style="font-size: 40px; color: #007bff; margin-right: 10px;"></i>
                            </a>
                            <!-- Dropdown Menu -->
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('/admin/lookup-tables/user-accounts') }}">View Users</a></li>
                                <li><a class="dropdown-item" id="setPermission">Set Permission</a></li>
                                <li><a class="dropdown-item" id="modifyStatus">Modify Status</a></li>
                            </ul>
                        </div>
                    
                        <!-- Icon Number Display -->
                        <div class="icon-number">
                            @if ($countclients >= 0 && $countclients <= 9)
                                0{{ $countclients }}
                            @else
                                {{ $countclients }}
                            @endif
                        </div>
                    </div>
                </div>  
                <div class="col-md-6 mb-4">  
                    <div class="card">  
                        <h4 class="card-title">Pending Transactions</h4> 
                        <div class="dropdown" style="display: inline-block;">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-file-invoice icon" style="color: #28a745; font-size: 40px;"></i>
                            </a>
                            <!-- Dropdown Menu -->
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('/admin/transaction') }}">View Transactions</a></li>
                                <li><a class="dropdown-item" id="requestBtnDashboard">Create Transaction</a></li>
                                <li><a class="dropdown-item" id="updateTransactionStatus">Approved Disapproved Transaction</a></li>
                            </ul>
                        </div>
                
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
                        <h4 class="card-title">Critical Items</h4>
                        <div class="dropdown" style="display: inline-block;">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-box icon" style="font-size: 40px; color: #dc3545; margin-right: 10px;"></i>
                            </a>
                            <!-- Dropdown Menu -->
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('/admin/lookup-tables/items') }}">View Items</a></li>
                                <li><a class="dropdown-item" id="addItem">Add Item</a></li>
                                <li><a class="dropdown-item" id="editItem">Edit Item</a></li>
                                <li><a class="dropdown-item" id="deleteItem">Delete Item</a></li>
                            </ul>
                        </div>
                        <div class="icon-number">
                            @if ($criticalCount >= 0 && $criticalCount <= 9)
                                0{{ $criticalCount }}
                            @else
                                {{ $criticalCount }}
                            @endif
                        </div>
                    </div>  
                </div>  
                <div class="col-md-6 mb-4">  
                    <div class="card">  
                        <h4 class="card-title">Delivered Items</h4>
                        <div class="dropdown" style="display: inline-block;">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-truck icon" style="color: #ffc107;7 font-size: 40px;"></i>
                            </a>
                            <!-- Dropdown Menu -->
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('/admin/lookup-tables/deliveries') }}">View Deliveries</a></li>
                                <li><a class="dropdown-item" id="receivedBtn">Create Delivery</a></li>
                            </ul>
                        </div>  
                        <div class="icon-number">
                            @if ($receives >= 0 && $receives <= 9)
                                0{{ number_format($receives) }}
                            @else
                                {{ number_format($receives) }}
                            @endif
                        </div> 
                    </div>  
                </div>  
            </div>  
            <div class="row">  
                <div class="col-md-6 mb-4">  
                    <div class="card">  
                        <h4 class="card-title">Item Categories</h4>  
                        
                        <div class="dropdown" style="display: inline-block;">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-tags icon" style="font-size: 40px; color: #6f42c1; margin-right: 10px;"></i>
                            </a>
                            <!-- Dropdown Menu -->
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('/admin/lookup-tables/categories') }}">View Categories</a></li>
                                <li><a class="dropdown-item" id="addCategory">Add Category</a></li>
                                <li><a class="dropdown-item" id="editCategory">Edit Category</a></li>
                                <li><a class="dropdown-item" id="deleteCategory">Delete Category</a></li>
                            </ul>
                        </div>

                        <div class="icon-number">
                            @if ($countCategories >= 0 && $countCategories <= 9)
                                0{{ $countCategories }}
                            @else
                                {{ $countCategories }}
                            @endif
                        </div> 
                    </div>  
                </div>  
                <div class="col-md-6 mb-4">  
                    <div class="card">  
                        <h4 class="card-title" style="font-size: 14px;">Item Units of Measurement</h4> 
                        <div class="dropdown" style="display: inline-block;">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ruler-combined icon" style="color: #17a2b8; font-size: 40px;"></i>
                            </a>
                            <!-- Dropdown Menu -->
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('/admin/lookup-tables/units') }}">View UoM</a></li>
                                <li><a class="dropdown-item" id="createUnit">Create UoM</a></li>
                                <li><a class="dropdown-item" id="editUnit">Edit UoM</a></li>
                                <li><a class="dropdown-item" id="deleteUnit">Delete UoM</a></li>
                            </ul>
                        </div>
                        <div class="icon-number">
                            @if ($countUnits >= 0 && $countUnits <= 9)
                                0{{ $countUnits }}
                            @else
                                {{ $countUnits }}
                            @endif
                        </div>
                    </div>  
                </div>  
                <div class="col-md-6 mb-4">  
                    <div class="card">  
                        <h4 class="card-title">Generated Reports</h4>
                        <div class="dropdown" style="display: inline-block;">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-chart-bar icon" style="font-size: 40px; color: #6610f2; margin-right: 10px;"></i>
                            </a>
                            <!-- Dropdown Menu -->
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('/admin/reports/monthly-report') }}">View Month Reports</a></li>
                                <li><a class="dropdown-item" href="{{ url('/admin/reports/quarterly-report') }}">View Quarterly Reports</a></li>
                                <li><a class="dropdown-item generateReportBtn">Generate Utilization Report</a></li>
                                <li><a class="dropdown-item pdfTransactionGenerationBtn">Generate Transaction Report</a></li>
                            </ul>
                        </div>
                        <div class="icon-number">
                            @if ($countReports >= 0 && $countReports <= 9)
                                0{{ $countReports }}
                            @else
                                {{ $countReports }}
                            @endif
                        </div>
                    </div>  
                </div>  
                <div class="col-md-6 mb-4">  
                    <div class="card">  
                        <h4 class="card-title">System Activity Logs</h4> 
                        <div class="dropdown" style="display: inline-block;">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-history icon" style="color: #fd7e14; font-size: 40px;"></i>
                            </a>
                            <!-- Dropdown Menu -->
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('/admin/trails') }}">View Activity Logs</a></li>
                            </ul>
                        </div>
                        <div class="icon-number">
                            @if ($countTrails >= 0 && $countTrails <= 9)
                                0{{ $countTrails }}
                            @else
                                {{ $countTrails }}
                            @endif
                        </div> 
                    </div>  
                </div>  
            </div>  
        </div>
    </div>  
</div>
    

  
@endsection  
@include('admin.modals.modals')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/admin/dashboard/accounts/permission.js')}}"></script>
<script src="{{ asset('assets/js/admin/dashboard/accounts/status.js')}}"></script>
<script src="{{ asset('assets/js/admin/dashboard/items/add-item.js')}}"></script>
<script src="{{ asset('assets/js/admin/dashboard/items/unit-search.js')}}"></script>
<script src="{{ asset('assets/js/admin/dashboard/items/category-search.js')}}"></script>
<script src="{{ asset('assets/js/admin/dashboard/items/edit-item.js')}}"></script>
<script src="{{ asset('assets/js/admin/dashboard/items/delete-item.js')}}"></script>
<script src="{{ asset('assets/js/admin/transactions/request-item.js')}}"></script>
<script src="{{ asset('assets/js/admin/transactions/search-items.js') }}"></script>
<script src="{{ asset('assets/js/admin/dashboard/transactions/status.js') }}"></script>
<script src="{{ asset('assets/js/admin/dashboard/receive/received-item.js') }}"></script>
<script src="{{ asset('assets/js/admin/dashboard/receive/search-item-name.js') }}"></script>
<script src="{{ asset('assets/js/admin/dashboard/categories/add-category.js') }}"></script>
<script src="{{ asset('assets/js/admin/dashboard/categories/edit-category.js') }}"></script>
<script src="{{ asset('assets/js/admin/dashboard/categories/delete-category.js') }}"></script>
<script src="{{ asset('assets/js/admin/dashboard/units/add-unit.js') }}"></script>
<script src="{{ asset('assets/js/admin/dashboard/units/edit-unit.js') }}"></script>
<script src="{{ asset('assets/js/admin/dashboard/units/delete-unit.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const statusSelect = document.getElementById("transaction_status");
        const timeDivision = document.getElementById("releaseTimeDivision");
        const reasonDivision = document.getElementById("denialReasonDivision");
        const timeInput = document.getElementById("releaseTime");

        statusSelect.addEventListener("change", function () {
            const value = this.value;

            timeDivision.style.display = "none";
            reasonDivision.style.display = "none";

            if (value === "2") {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                timeInput.value = `${hours}:${minutes}`;
                timeDivision.style.display = "block";
            } else if (value === "3") {
                reasonDivision.style.display = "block";
            }
        });
    });
document.getElementById('edit-category-id').addEventListener('change', function () {
    var categoryId = this.value;

    if (categoryId) {
        fetch('/get-category-control-number/' + categoryId)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit-category-control_number').value = data.control_number || '';
            })
            .catch(error => {
                console.error('Error fetching control number:', error);
                document.getElementById('edit-category-control_number').value = '';
            });
    } else {
        document.getElementById('edit-category-control_number').value = '';
    }
});
document.getElementById('edit_unit_id').addEventListener('change', function () {
        var unitId = this.value;

        if (unitId) {
            fetch('/get-unit-control-number/' + unitId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit-unit-control_number').value = data.control_number || '';
                })
                .catch(error => {
                    console.error('Error fetching control number:', error);
                    document.getElementById('edit-unit-control_number').value = '';
                });
        } else {
            document.getElementById('edit-unit-control_number').value = '';
        }
    });
    document.addEventListener("DOMContentLoaded", function () {
        const toggleBtn = document.getElementById('toggleTableBtn');
        const top10Container = document.getElementById('top10Container');
        const availableContainer = document.getElementById('availableItemContainer');
        const tableTitle = document.getElementById('tableTitle');

        toggleBtn.addEventListener('click', function () {
            const showingTop10 = top10Container.style.display !== 'none';

            top10Container.style.display = showingTop10 ? 'none' : 'block';
            availableContainer.style.display = showingTop10 ? 'block' : 'none';

            toggleBtn.textContent = showingTop10 ? 'Show Top 10 Items' : 'Show Available Items';
            tableTitle.textContent = showingTop10 ? 'Critical Items' : 'Top 10 Commonly Used Items for this Month';
        });
    });
</script>
