@extends('admin.layout.admin-layout')
@section('content')
    <div class="container-fluid mt-3 mb-3">
        <div class="row align-items-center">
            <div class="col-md-2">
                <!-- Breadcrumb Navigation -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Items</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-10 text-end">
                <button type="button" class="btn btn-success" id="addItemBtn" title="Add item button">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <button type="button" class="btn btn-warning" id="receivedBtn" title="Deliver item button">
                    <i class="fa-solid fa-cart-plus" style="color: #ffffff;"></i>
                </button>
                <button type="button" class="btn btn-info" id="pdfBtn" title="Generate PDF button">
                    <i class="fa-solid fa-file-pdf"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="container-fluid card mb-5 w-100" style="max-height: 700px; overflow-y: auto;">
        <div class="row">
            <div class="col-md-2 form-group mt-3">
                <label for="category-filter">Filter by Category: </label>
                <select id="category-filter" class="form-control">
                    <option value="">All</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                </select>
            </div>
            <div class="col-md-2 form-group mt-3">
                <label for="unit-filter">Filter by Unit: </label>
                <select id="unit-filter" class="form-control">
                    <option value="">All</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                        @endforeach
                </select>
            </div>
            <div class="col-md-2 form-group mt-3">
                <label for="minimum-quantity-filter">Filter by Minimum Quantity: </label>
                <input type="number" class="form-control" id="min-quantity-filter" placeholder="Min Quantity" min="0" value="0">
            </div>
            <div class="col-md-2 form-group mt-3">
                <label for="maximum-quantity-filter">Filter by Maximum Quantity: </label>
                <input type="number" class="form-control" id="max-quantity-filter" placeholder="Max Quantity" min="0" value="0">
            </div>
            <div class="col-md-2 form-group mt-3">
                <label for="status-filter">Filter by Status: </label>
                <select id="status-filter" class="form-control">
                    <option value="">All</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->name }}">{{ $status->name }}</option>
                        @endforeach
                </select>
            </div>
            <div class="col-md-2 form-group mt-3">
                <label for="level-filter">Filter by Stock Levels: </label>
                <select id="level-filter" class="form-control">
                    <option value="">All</option>
                    <option value="Low Stock">Low Stock</option>
                    <option value="Moderate Stock">Moderate Stock</option>
                    <option value="High Stock">High Stock</option>
                </select>
            </div>
            <div class="col-md-12">
                <table id="itemsTable" class="table-striped table-hover">
                    <thead class="bg-info">
                        <th width="10%">Control Number</th>
                        <th width="20%">Category</th>
                        <th width="20%">Name</th>
                        <th width="5%">Quantity</th>
                        <th width="5%">Unit</th>
                        <th width="10%">Status</th>
                        <th width="10%">Stock Level</th>
                        <th width="10%">Action</th>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->controlNumber }}</td>
                                <td>{{ $item->category->name }}</td>
                                <td>{{ $item->name}}</td>
                                <td>{{ $item->inventory->quantity }}</td>
                                <td>{{ $item->inventory->unit->name }}</td>
                                <td>
                                    @if($item->status && $item->status->name == 'Available')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Available
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times-circle"></i> Unavailable
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $quantity = $item->inventory->quantity;
                                        $maxQuantity = $item->inventory->max_quantity;
                                        $stockStatus = '';
                                        $statusClass = '';
                                        $icon = '';
                                
                                        $percentage = ($quantity / $maxQuantity) * 100;

                                        if ($percentage <= 20) {
                                            $stockStatus = 'Low Stock';
                                            $statusClass = 'stock-low';
                                            $icon = 'fa-exclamation-triangle';
                                        } elseif ($percentage <= 50) {
                                            $stockStatus = 'Moderate Stock';
                                            $statusClass = 'stock-moderate';
                                            $icon = 'fa-exclamation-circle';
                                        } else {
                                            $stockStatus = 'High Stock';
                                            $statusClass = 'stock-high';
                                            $icon = 'fa-check-circle';
                                        }
                                    @endphp
                                
                                    <span class="{{ $statusClass }}">
                                        <i class="fa {{ $icon }}"></i>
                                        {{ $stockStatus }}
                                    </span>
                                </td>                                
                                <td> 
                                    <button type="button" class="btn btn-warning" title="Item edit button" id="editItemBtn" onclick="editItem('{{ addslashes(json_encode($item)) }}')"><i class="fa-solid fa-edit" style="color: white;"></i></button>
                                    <button type="button" class="btn btn-danger"  title="Delete Item Button" id="deleteItemBtn" onclick="deleteItem('{{ addslashes(json_encode($item)) }}')"><i class="fa-solid fa-trash" style="color: white;"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<div class="container-fluid card w-100 shadow rounded p-4" id="itemForm" style="max-height: 300px; overflow-y: auto; background-color: #f8f9fa; display:none; border: 2px solid #ddd;">
    <!-- Form Header -->
    <div class="d-flex justify-content-between align-items-center bg-success text-white p-3 rounded-top">
        <h4 class="m-0 text-center flex-grow-1"><strong>CREATE ITEM FORM</strong></h4>
        <button type="button" id="createItem-closeBtn" class="btn btn-danger p-2">
            &times;
        </button>
    </div>
    <!-- Form Body -->
    <form action="" id="createItem-form" class="p-3">
        <div id="item-container">
            <div class="row mb-3 mt-2 item-row">
                <!-- Category -->
                <div class="col-md-3 form-group">
                    <label for="category" class="font-weight-bold">Category</label>
                    <input type="text" class="search-category form-control" name="category[]" placeholder="Search categories..." autocomplete="off"/>
                    <ul class="category-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                    <input type="text" class="selected-category-id" id="category" name="categoryId[]" hidden>
                </div>
                <!-- Item Name -->
                <div class="col-md-3 form-group">
                    <label for="itemName" class="font-weight-bold">Item Name</label>
                    <textarea name="itemName[]" class="form-control" cols="5" rows="1"></textarea>
                </div>
                <!-- Item Unit -->
                <div class="col-md-2 form-group">
                    <label for="itemUnit" class="font-weight-bold">Unit</label>
                    <input type="text" class="search-unit form-control" name="unit[]"  placeholder="Search unit..." autocomplete="off"/>
                    <ul class="unit-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                    <input type="text" class="selected-unit-id" name="unitId[]" hidden>
                </div>
                <!-- Item Quantity -->
                <div class="col-md-1 form-group">
                    <label for="quantity" class="font-weight-bold">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity[]" placeholder="Enter quantity" min="0">
                </div>
                <div class="col-md-1 form-group">
                    <label for="maxQuantity" class="font-weight-bold">Max Quantity</label>
                    <input type="number" class="form-control" name="maxQuantity[]" placeholder="Enter max quantity" min="0">
                </div>
                <div class="col-md-1 form-group">
                    <label for="action" class="font-weight-bold">Action</label>
                    <button type="button" class="remove-item btn btn-danger">Remove</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-between">
                <button type="button" id="addItem-btn" class="btn btn-primary rounded px-4 py-2">
                    Add more item
                </button>
                <div>
                    <button type="reset" class="btn btn-secondary rounded px-4 py-2 me-3">Clear</button>
                    <button type="submit" id="addItemSubmit-btn" class="btn btn-success rounded px-4 py-2">Submit</button>
                </div>
            </div>
        </div>
    </form>    
</div>
<div class="container-fluid card w-100 shadow rounded p-4" id="receivedItemForm" style="max-height: 300px; overflow-y: auto; background-color: #f8f9fa; display:none; border: 2px solid #ddd;">
    <!-- Form Header -->
    <div class="d-flex justify-content-between align-items-center bg-warning text-dark p-3 rounded-top">
        <h4 class="m-0 text-center flex-grow-1"><strong>RECEIVED ITEM FORM</strong></h4>
        <button type="button" id="receivedItem-closeBtn" class="btn btn-danger p-2">&times;</button>
    </div>
    <!-- Form Body -->
    <form action="" id="receivedItem-form" class="p-3">
        <div id="receivedItem-container">
            <div class="row mb-3 mt-2 receive-item-row">
                <div class="col-md-3 form-group">
                    <label for="itemName" class="font-weight-bold">Item Name</label>
                    <input type="text" class="search-items form-control" name="receivedItemName[]" id="receiveItemName" placeholder="Search items..." autocomplete="off"/>
                    <ul class="item-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                    <input type="text" class="selected-item-id" id="receiveItemId" name="receivedItemId[]" hidden>
                </div>
                <div class="col-md-1 form-group">
                    <label for="quantity" class="font-weight-bold">Quantity</label>
                    <input type="number" class="form-control" name="receivedQuantity[]" placeholder="Enter quantity" min="0">
                </div>
                <div class="col-md-1 form-group">
                    <label for="action" class="font-weight-bold">Action</label>
                    <button type="button" class="remove-received-item btn btn-danger">Remove</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-between">
                <button type="button" id="receivedItemReceived-btn" class="btn btn-primary rounded px-4 py-2">
                    Received more item
                </button>
                <div>
                    <button type="reset" class="btn btn-secondary rounded px-4 py-2 me-3">Clear</button>
                    <button type="submit" id="receivedItemSubmit-btn" class="btn btn-warning rounded px-4 py-2">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="deleteItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" style="color:white;">DELETE ITEM FORM</h5>
                    <button type="button" id="delete-item-close-btn" data-dismiss="modal" class="btn" aria-label="Close" style="background-color: white">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="delete-item-form">
                        <div class="col-md-3 form-group" hidden>
                            <label for="deleteItemId">Item ID</label>
                            <input type="text" class="form-control" name="delete-item-id" id="delete-item-id">
                        </div>
                        <strong>Are you sure to delete this item?</strong>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-danger" id="delete-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">EDIT ITEM FORM</h5>
                    <button type="button" id="edit-item-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row edit-item-row">
                    <form id="edit-item-form">
                        <div class="form-group" hidden>
                            <label for="editItemId">Item ID</label>
                            <input type="text" class="form-control" name="edit-item-id[]" id="edit-item-id">
                        </div>
                        <div class="form-group">
                            <label for="category" class="font-weight-bold">Category</label>
                            <input type="text" class="edit-search-category form-control" name="edit-category[]" placeholder="Search categories..." autocomplete="off"/>
                            <ul class="edit-category-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                            <input type="text" class="edit-selected-category-id" id="edit-category" name="edit-categoryId[]" hidden>
                        </div>
                        <div class="form-group">
                            <label for="edit-itemName" class="font-weight-bold">Item Name</label>
                            <textarea name="edit-itemName[]" id="edit-item-name" class="form-control" cols="5" rows="1"></textarea>
                        </div>
                        <!-- Item Unit -->
                        <div class="form-group">
                            <label for="itemUnit" class="font-weight-bold">Unit</label>
                            <input type="text" class="edit-search-unit form-control" name="edit-unit[]"  placeholder="Search unit..." autocomplete="off"/>
                            <ul class="edit-unit-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                            <input type="text" class="edit-selected-unit-id" name="edit-unitId[]" hidden>
                        </div>
                        <!-- Item Quantity -->
                        <div class="form-group">
                            <label for="edit-quantity" class="font-weight-bold">Quantity</label>
                            <input type="number" class="form-control" id="edit-quantity" name="edit-quantity[]" placeholder="Enter quantity" min="0">
                        </div>
                        <div class="form-group">
                            <label for="edit-maxQuantity" class="font-weight-bold">Max Quantity</label>
                            <input type="number" class="form-control" id="edit-max-quantity" name="edit-maxQuantity[]" placeholder="Enter max quantity" min="0">
                        </div>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-danger" id="delete-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pdfReportModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" style="color:white;">PDF CUSTOMIZE FORM</h5>
                    <button type="button" id="pdf-report-close-btn" data-dismiss="modal" class="btn" aria-label="Close" style="background-color: white">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="pdf-report-form">
                        <div class="form-group">
                            <label for="period" class="font-weight-bold">Period</label>
                            <select name="period" id="period" class="form-control">
                                <option value="">Select period</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Quarterly">Quarterly</option>
                            </select>
                        </div>
                        <div class="form-group" id="month-row" style="display: none">
                            <label for="month" class="font-weight-bold">Month</label>
                            <select name="month" id="month" class="form-control">
                                <option value="">Select month</option>
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
                            <label for="selectedYear" class="font-weight-bold">Select Year</label>
                            <select id="selectedYear" name="monthlySelectedYear" class="form-control">
                                <option value="">Select a Year</option>
                            </select>
                        </div>
                        <div class="form-group" id="quarterly-row" style="display: none">
                            <label for="quarterly" class="font-weight-bold">Quarterly</label>
                            <select name="quarterly" id="quarterly" class="form-control">
                                <option value="">Select quarterly</option>
                                <option value="1-2-3">Jan-Feb-Mar</option>
                                <option value="4-5-6">Apr-May-Jun</option>
                                <option value="7-8-9">Jul-Aug-Sep</option>
                                <option value="10-11-12">Oct-Nov-Dec</option>
                            </select>
                            <label for="selectedYear" class="font-weight-bold">Select Year</label>
                            <select id="yearSelectQuarterly" name="selectedYear" class="form-control">
                                <option value="">Select a Year</option>
                            </select>
                        </div>
                    </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-info" id="report-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
/* Prevent item quantity to value of negative */
document.addEventListener('DOMContentLoaded', function () {
    const quantity = document.getElementById("quantity");
    quantity.addEventListener('input', function () {
        if (Number(quantity.value) < 0 || isNaN(quantity.value)) {
            $('#quantity').val('0');
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const period = document.getElementById("period");
    period.addEventListener('change', function () {
        const monthRow = document.getElementById("month-row");
        const quarterlyRow = document.getElementById('quarterly-row')
        if (period.value === "Monthly") {
            monthRow.style.display = "block"; 
            quarterlyRow.style.display = "none";
        } else if(period.value === "Quarterly") {
            monthRow.style.display = "none"; 
            quarterlyRow.style.display = "block";
        }else{
            quarterlyRow.style.display = "none";
            monthRow.style.display = "none"; 
        }
    });
});
window.onload = function() {
    // Monthly Select (current year on top)
    var currentYearMonthly = new Date().getFullYear();
    var selectMonthly = document.getElementById('selectedYear'); // Get the monthly select element

    // First, add the current year at the top for monthly selection
    let currentOptionMonthly = document.createElement('option');
    currentOptionMonthly.value = currentYearMonthly;
    currentOptionMonthly.text = currentYearMonthly;
    currentOptionMonthly.selected = true;  // Set the current year as selected by default
    selectMonthly.appendChild(currentOptionMonthly);  // Append to selectMonthly

    // Then, add options for the previous 5 years
    for (var i = currentYearMonthly - 1; i >= currentYearMonthly - 5; i--) {
        let option = document.createElement('option');
        option.value = i;
        option.text = i;
        selectMonthly.appendChild(option);  // Append to selectMonthly
    }

    // Quarterly Select (current year on top)
    var currentYearQuarterly = new Date().getFullYear();
    var selectQuarterly = document.getElementById('yearSelectQuarterly'); // Get the quarterly select element

    // First, add the current year at the top for quarterly selection
    let currentOptionQuarterly = document.createElement('option');
    currentOptionQuarterly.value = currentYearQuarterly;
    currentOptionQuarterly.text = currentYearQuarterly;
    currentOptionQuarterly.selected = true;  // Set the current year as selected by default
    selectQuarterly.appendChild(currentOptionQuarterly);  // Append to selectQuarterly

    // Then, add options for the previous 10 years
    for (var i = currentYearQuarterly - 1; i >= currentYearQuarterly - 10; i--) {
        let option = document.createElement('option');
        option.value = i;
        option.text = i;
        selectQuarterly.appendChild(option);  // Append to selectQuarterly
    }
};


</script>
<script src="{{ asset('assets/js/admin/items/add-item-functions/category-search.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/add-item-functions/unit-search.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/received-item-functions/search-item-name.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/received-item.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/delete-item.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/edit-item.js') }}"></script>
<script src="{{ asset('assets/js/admin/pdf/report.js') }}"></script>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
