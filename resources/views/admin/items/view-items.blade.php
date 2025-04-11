@extends('admin.layout.admin-layout')
@section('content')
<div class="container-fluid mt-3 mb-3">
    <div class="row align-items-center">
        <div class="col-md-2">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lookup Tables</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-1 text-right">
            <label for="container">Tables</label>
        </div>
        <div class="col-md-2">
            <select name="container" id="container" class="form-control">
                <option value="items">Items</option>
                <option value="deliveries">Deliveries</option>
                <option value="reports">Reports</option>
                <option value="accounts">Accounts</option>
                <option value="categories">Categories</option>
                <option value="units">Units</option>
            </select>
        </div>
        <div class="col-md-7 text-end">
            
        </div>
    </div>
</div>
<div class="container-fluid card w-100 shadow rounded p-4" id="itemForm" style="max-height: 500px; overflow-y: auto; background-color: #f8f9fa; display:none; border: 2px solid #ddd;">
    <!-- Form Header -->
    <div class="d-flex justify-content-between align-items-center bg-success text-white p-3 rounded-top">
        <h4 class="m-0 text-center flex-grow-1"><strong>CREATE ITEM FORM</strong></h4>
        <button type="button" id="createItem-closeBtn" class="btn btn-danger p-2">
            &times;
        </button>
    </div>
    <!-- Form Body -->
    <form action="" id="createItem-form" class="p-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" id="addItem-btn" class="btn btn-primary rounded px-4 py-2">
                Add more item
            </button>
        </div>
        <div id="item-container">
            <div class="row mb-3 mt-2 item-row">
                <!-- Category -->
                <div class="col-md-3 form-group">
                    <label for="category" class="font-weight-bold">Category</label>
                    <input type="text" class="search-category form-control" name="category[]" placeholder="Search categories..." autocomplete="off"/>
                    <ul class="category-results" style="display: none; max-height: 300px; overflow-y: auto;"></ul>
                    <input type="text" class="selected-category-id" id="category" name="categoryId[]" hidden>
                </div>
                <!-- Item Name -->
                <div class="col-md-3 form-group">
                    <label for="itemName" class="font-weight-bold">Item Name</label>
                    <textarea name="itemName[]" class="form-control" cols="5" rows="1"></textarea>
                </div>
                <!-- Item Unit -->
                <div class="col-md-2 form-group">
                    <label for="itemUnit" class="font-weight-bold">Unit of Measurement</label>
                    <input type="text" class="search-unit form-control" name="unit[]"  placeholder="Search unit..." autocomplete="off"/>
                    <ul class="unit-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                    <input type="text" class="selected-unit-id" name="unitId[]" hidden>
                </div>
                <!-- Item Quantity -->
                <div class="col-md-1 form-group">
                    <label for="quantity" class="font-weight-bold">Stock on Hand</label>
                    <input type="number" class="form-control" id="quantity" name="quantity[]" placeholder="Enter quantity" min="0">
                </div>
                <div class="col-md-1 form-group">
                    <label for="action" class="font-weight-bold">Action</label>
                    <button type="button" class="remove-add-item btn btn-danger">Remove</button>
                </div>
            </div>
        </div>
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="col-md-1 form-group">
                        <label for="" class="font-weight-bold">&nbsp</label>
                        <button type="reset" class="btn btn-secondary rounded px-4 py-2 me-3 form-control">Clear</button>
                    </div>
                    <div class="col-md-1 form-group">
                        <label for="" class="font-weight-bold">&nbsp</label>
                        <button type="submit" id="addItemSubmit-btn" class="btn btn-success rounded px-4 py-2 form-control">Submit</button>
                    </div>
                </div>
            </div>
    </form>    
</div>
<div class="container-fluid card w-100 shadow rounded p-4" id="receivedItemForm" style="max-height: 300px; overflow-y: auto; background-color: #f8f9fa; display:none; border: 2px solid #ddd;">
    <!-- Form Header -->
    <div class="d-flex justify-content-between align-items-center bg-warning text-dark p-3 rounded-top">
        <h4 class="m-0 text-center flex-grow-1"><strong>DELIVERY FORM</strong></h4>
        <button type="button" id="receivedItem-closeBtn" class="btn btn-danger p-2">&times;</button>
    </div>
    <!-- Form Body -->
    <form action="" id="receivedItem-form" class="p-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" id="addRequest-btn" class="btn btn-primary rounded px-4 py-2">
                Deliver more item
            </button>
        </div>
        <div id="receivedItem-container">
            <div class="row mb-3 mt-2 receive-item-row">
                <div class="col-md-2 form-group">
                    <label for="delivery_types" class="font-weight-bold">Delivery Type</label>
                    <select name="delivery_types[]" id="delivery_types" class="form-control">
                        <option value="">Select Delivery Type</option>
                        <option value="Inspection Delivery">Inspection Delivery</option>
                        <option value="Receipt for Stock">Receipt for Stock</option>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label for="control_number_received" class="font-weight-bold">Control Number</label>
                    <input type="text" class="form-control" name="control_number[]" id="control_number_received" placeholder="Enter control number">
                </div>
                <div class="col-md-3 form-group">
                    <label for="itemName" class="font-weight-bold">Item Name</label>
                    <input type="text" class="search-items form-control" name="receivedItemName[]" id="receiveItemName" placeholder="Search items..." autocomplete="off"/>
                    <ul class="item-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                    <input type="text" class="selected-item-id" id="receiveItemId" name="receivedItemId[]" hidden>
                </div>
                <div class="col-md-2 form-group">
                    <label for="quantity" class="font-weight-bold">Quantity</label>
                    <input type="number" class="form-control" name="receivedQuantity[]" id="received_quantity" placeholder="Enter quantity" min="0">
                </div>
                <div class="col-md-2 form-group">
                    <label for="remaining_quantity" class="font-weight-bold">Stock on Hand</label>
                    <input type="number" class="form-control remaining_quantity" name="remainingQuantity[]" id="remaining_quantity" placeholder="Enter quantity" min="0" readonly>
                </div>
                <div class="col-md-1 form-group">
                    <label for="action" class="font-weight-bold">Action</label>
                    <button type="button" class="remove-deliver-item btn btn-danger">Remove</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
                <div class="col-md-1 form-group">
                    <label for="" class="font-weight-bold">&nbsp</label>
                    <button type="reset" class="btn btn-secondary rounded px-4 py-2 me-3 form-control">Clear</button>
                </div>
                <div class="col-md-1 form-group">
                    <label for="" class="font-weight-bold">&nbsp</label>
                    <button type="submit" id="receivedItemSubmit-btn" class="btn btn-warning rounded px-4 py-2 form-control">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="items-container" class="table-container" style="display: block;">
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-9" style="text-align: left">
                <h4><strong>INVENTORY</strong></h4>
            </div>
            <div class="col-md-3" style="text-align: right">
                <button type="button" class="btn btn-success" id="addItemBtn" title="Add item button">
                    <i class="fa-solid fa-plus"></i>
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
            <div class="col-md-12">
                <table id="itemsTable" class="table-striped table-hover" style="font-size: 11px">
                    <thead class="bg-info">
                        <th>Date/Time Created</th>
                        <th>Date/Time Updated</th>
                        <th>Control Number</th>
                        <th>Stock on Hand</th>
                        <th>UoM</th>
                        <th width="20%">Category</th>
                        <th width="20%">Item Name</th>
                        <th>Status</th>
                        <th width="5%">Stock Level</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="table-container" id="deliveries-container">
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-9" style="text-align: left">
                <h4><strong>DELIVERIES</strong></h4>
            </div>
            <div class="col-md-3" style="text-align: right">
                <button type="button" class="btn btn-warning" id="receivedBtn" title="Deliver item button">
                    <i class="fa-solid fa-cart-plus" style="color: #ffffff;"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <table id="receivesTable" class="table-striped table-hover" style="font-size: 12px">
                    <thead style="background-color: #3d5c99; color:white">
                        <tr>
                            <th width="12%">Date/Time Received</th>
                            <th width="12%">Date/Time Updated</th>
                            <th width="12%">Delivery Type</td>
                            <th width="10%">Control Number</th>
                            <th width="10%">Stock On Hand</th>
                            <th width="10%">Delivered Quantity</th>
                            <th width="5%">UoM</th>
                            <th>Item Name</th>
                            <th width="5%">Remarks</th>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
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
                            <input type="text" class="edit-search-category form-control" name="category[]" placeholder="Search categories..." autocomplete="off"/>
                            <ul class="edit-category-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                            <input type="text" class="edit-selected-category-id" id="edit-category" name="category_id[]" hidden>
                        </div>
                        <div class="form-group">
                            <label for="edit-itemName" class="font-weight-bold">Item Name</label>
                            <textarea name="item_name[]" id="edit-item-name" class="form-control" cols="5" rows="1"></textarea>
                        </div>
                        <!-- Item Unit -->
                        <div class="form-group">
                            <label for="itemUnit" class="font-weight-bold">Unit</label>
                            <input type="text" class="edit-search-unit form-control" name="unit[]"  placeholder="Search unit..." autocomplete="off"/>
                            <ul class="edit-unit-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                            <input type="text" class="edit-selected-unit-id" name="unitId[]" hidden>
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
<div class="modal fade" id="updateReceivedModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">EDIT DELIVERIES FORM</h5>
                    <button type="button" id="edit-received-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="update-received-status-form">
                        @csrf
                        <div class="form-group" hidden>
                            <label for="editReceivedId">Received ID</label>
                            <input type="text" class="form-control" name="edit-received-id" id="edit-received-id">
                        </div>
                        <div class="form-group" hidden>
                            <label for="editExistingQuantity" class="font-weight-bold">Item Id</label>
                            <textarea name="item_id" id="edit-received-item-id" class="form-control" cols="5" rows="1" readonly></textarea>
                        </div>
                        <!-- Item Unit -->
                        <div class="form-group" hidden>
                            <label for="editReceivedQuantity" class="font-weight-bold">Quantity</label>
                            <input type="text" class="form-control" name="edit-received-quantity" id="edit-received-quantity" />
                        </div>                  
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-danger" id="update-received-submit-btn">SUBMIT</button>
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
                                <option value="1-2-3">First Quarter</option>
                                <option value="4-5-6">Second Quarter</option>
                                <option value="7-8-9">Third Quarter</option>
                                <option value="10-11-12">Fourth Quarter</option>
                            </select>
                            <label for="selectedYear" class="font-weight-bold">Select Year</label>
                            <select id="yearSelectQuarterly" name="selectedYear" class="form-control">
                                <option value="">Select a Year</option>
                            </select>
                        </div>
                        <div class="form-group" id="signatories-row" style="display: none">
                            <label for="conducted" class="font-weight-bold">Conducted By:</label>
                            <select name="conducted" id="conducted" class="form-control">
                                <option value="">Select Conducted By:</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->full_name }}</option>
                                @endforeach
                            </select>
                        
                            <label for="prepared" class="font-weight-bold">Prepared By:</label>
                            <select name="prepared" id="prepared" class="form-control">
                                <option value="">Select Prepared By:</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->full_name }}</option>
                                @endforeach
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
<div class="modal fade" id="addReportModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" style="color:white;">ADD REPORT FORM</h5>
                    <button type="button" id="add-report-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="add-report-form" enctype="multipart/form-data">
                    @csrf
                        <div class="form-group">
                            <label for="reportType" class="font-weight-bold">Report Type</label>
                            <select name="report_type" id="report_type" class="form-control">
                                <option value="">Select Report Type</option>
                                <option value="Quarterly">Quarterly Report</option>
                                <option value="Monthly">Monthly Report</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reportName" class="font-weight-bold">Report Name</label>
                            <input type="text" class="form-control" name="report_name" id="report_name">
                        </div>
                        <div class="form-group">
                            <label for="submittedBy" class="font-weight-bold">Submitted By:</label>
                            <select name="submitted" id="submitted" class="form-control">
                                <option value="">Select Admin</option>
                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reportName" class="font-weight-bold">Report File</label>
                            <input type="file" name="pdf" id="pdf_report" class="form-control" accept="application/pdf" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="modal-footer">
                            <div class="col-md-3 form-group">
                                <button type="submit" class="btn btn-success" id="add-report-submit-btn">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-container" id="reports-container">
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-9" style="text-align: left">
                <h4><strong>REPORTS</strong></h4>
            </div>
            <div class="col-md-3" style="text-align: right">
                <button type="button" class="btn btn-success" id="addReportBtn" title="Add report button">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <table id="reportsTable" class="table-striped table-hover" style="font-size: 12px">
                    <thead style="background-color: #3d5c99; color:white">
                        <tr>
                            <th width="12%">Date/Time Submitted</th>
                            <th width="12%">Date/Time Updated</th>
                            <th width="10%">Report Type</th>
                            <th width="10%">Control Number</th>
                            <th width="10%">Report Name</th>
                            <th width="10%">Submitted By</th>
                            <th width="10%">Position</th>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($report->created_at)->format('F d, Y H:i A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($report->updated_at)->format('F d, Y H:i A') }}</td>
                            <td>{{ $report->report_type }}</td>
                            <td>{{ $report->control_number }}</td>
                            <td>{{ $report->report_name }}</td>
                            <td>{{ $report->admin->full_name }}</td>
                            <td>{{ $report->admin->position }}</td>
                            <td class="text-center">
                                <button class="btn btn-info view-pdf-btn" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" data-filename="{{ $report->report_file }}"><i class="fa fa-eye"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pdfPreviewModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document"> <!-- Use this for Bootstrap 5 -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">PDF Preview</h5>
                <button type="button" class="close btn btn-danger" id="pdf-preview-close-btn" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-0"> <!-- Remove padding -->
                <iframe id="pdfFrame" src="" width="100%" height="100%" style="border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="table-container" id="accounts-container">
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-9" style="text-align: left">
                <h4><strong>ACCOUNTS</strong></h4>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <table id="accountTable" class="table-striped table-hover" style="font-size: 12px">
                    <thead>
                        <th>Employee Number</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Office</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th width="10%">Action</th>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            @if ($client->role->id != 1)
                                <tr>
                                    <td>{{ $client->employee_number }}</td>
                                    <td>{{ $client->full_name }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->role->name }}</td>
                                    <td>{{ $client->office }}</td>
                                    <td>{{ $client->position }}</td>
                                    <td id="user_status" class="text-center">
                                        @if($client->status && $client->status == 'Active')
                                            <span class="badge badge-success" id="status-badge">
                                                <i class="fas fa-check-circle"></i> Active
                                            </span>
                                        @else
                                            <span class="badge badge-danger" id="status-badge">
                                                <i class="fas fa-times-circle"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="setUser('{{ addslashes(json_encode($client)) }}')" title="Set permission"><i class="fa fa-user" style="color: white;"></i></button>
                                        <button type="button" class="btn btn-danger" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="changeUserStatus('{{ addslashes(json_encode($client)) }}')" title="Change user status"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="setUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">SET USER ROLE FORM</h5>
                    <button type="button" id="set-user-close-btn" data-dismiss="modal" class="btn bg-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark" style="color: white;"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="set-user-role-form">
                        @csrf
                        <div class="form-group" hidden>
                            <label for="userId">USER ID</label>
                            <input type="text" class="form-control" name="user_id" id="set-user-id">
                        </div>
                        <div class="form-group">
                            <label for="fullName">Full Name</label>
                            <input type="text" class="form-control" name="full_name" id="full-name" readonly>
                        </div>
                        <div class="form-group" >
                            <label for="roleId">System Roles</label>
                            <select name="role_id" id="role-id" class="form-control">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @if($role->name == $client->role->name) selected @endif>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-warning" id="set-role-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="changeUserStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" style="color:white;">CHANGE USER STATUS FORM</h5>
                    <button type="button" id="change-user-status-close-btn" data-dismiss="modal" class="btn bg-light" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="change-user-status-form">
                        @csrf
                        <div class="form-group" hidden>
                            <label for="userId">USER ID</label>
                            <input type="text" class="form-control" name="user_id" id="change-user-status-id">
                        </div>
                        <div class="form-group">
                            <label for="fullName">Full Name</label>
                            <input type="text" class="form-control" name="full_name" id="change-user-full-name" readonly>
                        </div>
                        <div class="form-group" >
                            <label for="status">System Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Select User Status</option>
                                <option value="Inactive">Deactivate</option>
                                <option value="Active">Activate</option>
                            </select>
                        </div>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-danger" id="change-user-status-submit-btn">SUBMIT</button>
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
/* Function for monthly and quarterly display */
document.addEventListener('DOMContentLoaded', function () {
    const period = document.getElementById("period");
    period.addEventListener('change', function () {
        const monthRow = document.getElementById("month-row");
        const quarterlyRow = document.getElementById('quarterly-row');
        const signatoriesRow = document.getElementById('signatories-row');
        if (period.value === "Monthly") {
            monthRow.style.display = "block"; 
            quarterlyRow.style.display = "none";
            signatoriesRow.style.display = "block";
        } else if(period.value === "Quarterly") {
            monthRow.style.display = "none"; 
            quarterlyRow.style.display = "block";
            signatoriesRow.style.display = "block";
        }else{
            quarterlyRow.style.display = "none";
            monthRow.style.display = "none";
            signatoriesRow.style.display = "none";
        }
    });
});
/* Function for year */
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
/* Function for received to prevent overflow of quantity */
document.addEventListener('DOMContentLoaded', function () {
    const receivedQuantity = document.getElementById('received_quantity'); // Input for received quantity
    const remainingQuantity = document.getElementById("remaining_quantity"); // Read-only remaining quantity
    const maxQuantity = document.getElementById('max_quantity'); // Read-only max quantity
    
    // Listen for input on receivedQuantity
    receivedQuantity.addEventListener('input', function () {
        const remaining = Number(remainingQuantity.value); // Get remaining quantity
        const received = Number(receivedQuantity.value); // Get received quantity
        const max = Number(maxQuantity.value); // Get max quantity

        // If the sum of receivedQuantity and remainingQuantity exceeds maxQuantity, adjust the receivedQuantity
        if (received + remaining > max) {
            // Limit the receivedQuantity to max - remainingQuantity to avoid exceeding maxQuantity
            receivedQuantity.value = max - remaining;
        }
        
        // Ensure receivedQuantity is never negative
        if (receivedQuantity.value < 0) {
            receivedQuantity.value = 0;
        }
    });
});
/* Function for edit received to prevent overflow of quantity */
document.addEventListener('DOMContentLoaded', function () {
    const receivedQuantity = document.getElementById('edit-received-quantity'); // Input for received quantity
    const remainingQuantity = document.getElementById("edit-received-remaining-quantity"); // Read-only remaining quantity
    const maxQuantity = document.getElementById('edit-received-max-quantity'); // Read-only max quantity
    
    // Listen for input on receivedQuantity
    receivedQuantity.addEventListener('input', function () {
        const remaining = Number(remainingQuantity.value); // Get remaining quantity
        const received = Number(receivedQuantity.value); // Get received quantity
        const max = Number(maxQuantity.value); // Get max quantity

        // If the sum of receivedQuantity and remainingQuantity exceeds maxQuantity, adjust the receivedQuantity
        if (received + remaining > max) {
            // Limit the receivedQuantity to max - remainingQuantity to avoid exceeding maxQuantity
            receivedQuantity.value = max - remaining;
        }
        
        // Ensure receivedQuantity is never negative
        if (receivedQuantity.value < 0) {
            receivedQuantity.value = 0;
        }
    });
});
document.getElementById('container').addEventListener('change', function () {
    // Hide all containers first
    const containers = document.querySelectorAll('.table-container');
    containers.forEach(container => {
        container.style.display = 'none';
    });

    // Show the selected container
    const selectedValue = this.value;
    if (selectedValue) {
        const selectedContainer = document.getElementById(`${selectedValue}-container`);
        if (selectedContainer) {
            selectedContainer.style.display = 'block';
        }
    }
});
$(document).ready(function() {
    var currentRole = "{{ $client->role->name }}";  
    var roleDropdown = $('#role-id');
    roleDropdown.find('option').each(function() {
        var option = $(this);
        var roleName = option.text().trim();

        if (currentRole === 'HeadAdmin') {
            if (roleName === 'HeadAdmin') {
                option.prop('hidden', true);
            } else {
                option.prop('hidden', false);
            }
        } else if (currentRole === 'InventoryAdmin') {
            if (roleName === 'InventoryAdmin') {
                option.prop('hidden', true);
            } else {
                option.prop('hidden', false);
            }
        } else if (currentRole === 'CheckerAdmin') {
            if (roleName === 'CheckerAdmin') {
                option.prop('hidden', true);
            } else {
                option.prop('hidden', false);
            }
        } else if (currentRole === 'User') {
            if (roleName === 'User') {
                option.prop('hidden', true);
            } else {
                option.prop('hidden', false);
            }
        } else {
            option.prop('hidden', false);
        }
    });
});
</script>
<script src="{{ asset('assets/js/admin/items/add-item-functions/category-search.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/add-item-functions/unit-search.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/received-item-functions/search-item-name.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/received-item.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/delete-item.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/edit-item.js') }}"></script>
<script src="{{ asset('assets/js/admin/pdf/report.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/received-item-functions/edit-received-item.js') }}"></script>
<script src="{{ asset('assets/js/admin/report/add-report.js') }}"></script>
<script src="{{ asset('assets/js/admin/accounts/clients-setting.js') }}"></script>
<script src="{{ asset('assets/js/admin/accounts/status.js') }}"></script>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
