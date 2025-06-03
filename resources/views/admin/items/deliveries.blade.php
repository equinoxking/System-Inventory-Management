@extends('admin.layout.admin-layout')
@section('content')
<div class="container-fluid mt-3 mb-3">
    <div class="row align-items-center">
        <div class="col-md-12">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lookup Tables</li>
                    <li class="breadcrumb-item active" aria-current="page">Deliveries</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid card w-100 shadow rounded p-4" id="receivedItemForm" style="max-height: 500px; overflow-y: auto; background-color: #f8f9fa; display:none; border: 2px solid #ddd;">
    <!-- Form Header -->
    <div class="d-flex justify-content-between align-items-center bg-warning text-dark p-3 rounded-top">
        <h4 class="m-0 text-center flex-grow-1"><strong>ADD NEW DELIVERY FORM</strong></h4>
        <button type="button" id="receivedItem-closeBtn" class="btn btn-danger p-2">&times;</button>
    </div>
    <!-- Form Body -->
    <form action="" id="receivedItem-form" class="p-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" id="addRequest-btn" class="btn btn-primary rounded px-4 py-2" title="add more delivery row">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <div id="receivedItem-container">
            <div class="row mb-3 mt-2 receive-item-row">
                <div class="col-md-2 form-group">
                    <label for="control_number_received" class="font-weight-bold">P.O Number</label>
                    <input type="text" class="form-control control_number_received" name="control_number[]"  placeholder="Enter control number">
                </div>
                <div class="col-md-2 form-group">
                    <label for="supplier" class="font-weight-bold">Supplier</label>
                    {{-- <input type="text" class="form-control" name="supplier[]" id="supplier" placeholder="Enter supplier" min="0"> --}}
                    <select name="supplier[]"class="form-control supplier">
                        <option value="">Select Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
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
                    <label for="action" class="font-weight-bold">Action</label><br>
                    <button type="button" class="remove-deliver-item btn btn-danger"><i class="fa-solid fa-eraser"></i></button>
                </div>
            </div>
        </div>
        <div class="row mt-3 justify-content-end">
            <div class="col-auto form-group text-end">
                <label for="" class="font-weight-bold">&nbsp;</label>
                <button type="reset" class="btn btn-secondary rounded px-4 py-2 form-control">CLEAR</button>
            </div>
            <div class="col-auto form-group text-end">
                <label for="" class="font-weight-bold">&nbsp;</label>
                <button type="submit" id="receivedItemSubmit-btn" class="btn btn-warning rounded px-4 py-2 form-control">SAVE</button>
            </div>
        </div>
    </form>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-9" style="text-align: left">
            <h4><strong>DELIVERIES</strong></h4>
        </div>
        <div class="col-md-3" style="text-align: right">
            <button type="button" class="btn btn-success" id="receivedBtn" title="Deliver item button">
                <i class="fa-solid fa-plus "></i>
            </button>
        </div>
    </div>
</div>
<div class="container-fluid card mb-1 w-100" style="max-height: 700px; overflow-y: auto;">
    <div class="row">
        <div class="col-md-2 form-group mt-3">
            <label for="category-filter">Filter by Purchase Oder: </label>
            <input type="text" id="po-filter" class="form-control" placeholder="Search purchase order">
        </div>
        <div class="col-md-2 form-group mt-3">
            <label for="unit-filter">Filter by Supplier: </label>
            <input type="text" id="supplier-filter" class="form-control" placeholder="Search supplier">
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <table id="receivesTable" class="table-hover" style="font-size: 12px">
                <thead style="background-color: #3d5c99; color:white">
                    <tr>
                        <th width="10%">Date/Time Delivered</th>
                        <th width="10%">Purchase Order Number</th>
                        <th>Supplier</th>
                        <th>Item Name</th>
                        <th width="9%">Delivered Quantity</th>
                        <th width="4%">Uom</th>
                        <th width="7%">Stock On Hand</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item['created_at'] }}</td>
                            <td>{{ $item['control_number'] }}</td>
                            <td>{{ $item['supplier'] }}</td>
                            <td>{{ $item['item_name'] }}</td>
                            <td>{{ $item['received_quantity'] }}</td>
                            <td>{{ $item['unit_name'] }}</td>
                            <td>{{ $item['remaining_quantity'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/admin/items/other-functions.js') }}"></script>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/admin/items/received-item-functions/search-item-name.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/received-item.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/received-item-functions/edit-received-item.js') }}"></script>
