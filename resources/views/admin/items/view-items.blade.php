@extends('admin.layout.admin-layout')
@section('content')
    <div class="container-fluid mt-3 mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <!-- Breadcrumb Navigation -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Items</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4 text-center">
                <a href="#" class="btn btn-primary">Item Category</a>
                <a href="#" class="btn btn-warning">Item Status</a>
            </div>
            <div class="col-md-4 text-end">
                <button type="button" class="btn btn-success" id="addItemBtn" title="Add item button">
                    <i class="fa-solid fa-plus"></i>
                </button>
                <button type="button" class="btn btn-info" title="Generate PDF button">
                    <i class="fa-solid fa-file-pdf"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="container-fluid card mb-5 w-100" style="max-height: 700px; overflow-y: auto;">
        <div class="row">
            <div class="col-md-12">
                <table id="itemsTable">
                    <thead>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    
                    </tbody>
                    <tfoot>
                    
                    </tfoot>
                </table>
            </div>
        </div>
    </div>



    <div class="container-fluid card w-100 shadow rounded p-4" 
     id="itemForm" 
     style="max-height: 400px; overflow-y: auto; background-color: #f8f9fa; display:none; border: 2px solid #ddd;">
     
    <!-- Form Header -->
    <div class="d-flex justify-content-between align-items-center bg-primary text-white p-3 rounded-top">
        <h4 class="m-0 text-center flex-grow-1"><strong>CREATE ITEM FORM</strong></h4>
        <button type="button" id="createItem-closeBtn" class="btn btn-danger p-2">
            &times;
        </button>
    </div>

    <!-- Form Body -->
    <form action="" id="createItem-form" class="p-3">
        <div class="row mb-3 mt-2">
            <!-- Category -->
            <div class="col-md-3 form-group">
                <label for="category" class="font-weight-bold">Category</label>
                <select name="category" id="category" class="form-control">
                    <option value="">Select category</option>
                    @foreach ($categories as $category)
                        <option value='{{ $category->name }}'>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Item Name -->
            <div class="col-md-3 form-group">
                <label for="itemName" class="font-weight-bold">Item Name</label>
                <input type="text" class="form-control" name="itemName" id="itemName" placeholder="Enter item name">
            </div>

            <!-- Item Unit -->
            <div class="col-md-2 form-group">
                <label for="itemUnit" class="font-weight-bold">Unit</label>
                <select name="unit" id="unit" class="form-control">
                    <option value="">Select unit</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->symbol }}">{{ $unit->symbol }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Item Quantity -->
            <div class="col-md-2 form-group">
                <label for="itemQuantity" class="font-weight-bold">Quantity</label>
                <input type="number" class="form-control" name="itemQuantity" id="itemQuantity" placeholder="Enter quantity" min="0">
            </div>

            <!-- Description -->
            <div class="col-md-12 form-group">
                <label for="itemDescription" class="font-weight-bold">Description</label>
                <textarea name="itemDescription" id="itemDescription" class="form-control" rows="2" placeholder="Enter item description"></textarea>
            </div>

            <!-- Buttons on One Side (Right) -->
            <div class="col-md-12 d-flex justify-content-end mt-3">
                <button type="reset" class="btn btn-secondary rounded me-2">Clear</button>
                <button type="submit" class="btn btn-primary rounded">Submit</button>
            </div>
        </div>
    </form>
</div>
@endsection
<script>
document.getElementById("itemQuantity").addEventListener("input", function() {
    var quantity = document.getElementById("itemQuantity");
    if(quantity < 0){
        $('#itemQuantity').val('0');
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const quantity = document.getElementById("itemQuantity");
    quantity.addEventListener('input', function () {
        if()
    });
});

</script>