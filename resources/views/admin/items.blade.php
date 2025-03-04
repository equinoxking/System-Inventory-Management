@extends('admin.layout.admin-layout')
@section('content')
    <div class="container-fluid mt-3 mb-3" style="text-align: left;">
        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-success" id="addItemBtn"><i class="fa-solid fa-plus" title="Add item button"></i></button>
                <button type="button" class="btn btn-info"><i class="fa-solid fa-file-pdf" title="Generate PDF button"></i></button>
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
    <div class="container-fluid card w-100" id="itemForm" style="max-height: 400px; overflow-y: auto; background-color:whitesmoke; display:none">
        <h1 style="display: flex; justify-content: space-between; align-items: center;">
            <strong style="text-align: center; flex-grow: 1;">ADDING ITEM FORM</strong>
            <button type="button" class="btn btn-danger" style="text-align: right;">x</button>
        </h1>
        <form action="">
            <div class="row mb-3 mt-5">
                <div class="col-md-2 form-group">
                    <label for="category" class="text-left">Category</label>
                    <select name="category" id="category" class="form-control">
                        <option value="">Select here category</option>
                        <option value="Fertilizers">Fertilizers</option>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label for="itemName" class="text-left">Item Name</label>
                    <input type="text" class="form-control" name="itemName" id="itemName" placeholder="Type here item name.">
                </div>
                <div class="col-md-1 form-group">
                    <label for="itemUnit" class="text-left">Item Unit</label>
                    <input type="text" class="form-control" name="itemUnit" id="itemUnit" placeholder="Type here item unit.">
                </div>
                <div class="col-md-1 form-group">
                    <label for="itemQuantity" class="text-left">Item Quantity</label>
                    <input type="number" class="form-control" name="itemQuantity" id="itemQuantity" placeholder="Type here item unit.">
                </div>
                <div class="col-md-4 form-group">
                    <label for="itemDescription" class="text-left">Item Description</label>
                    <textarea name="itemDescription" id="itemDescription" class="form-control" cols="1" rows="1"></textarea>
                </div>
                <div class="col-md-1">
                    <button type="reset" class="btn btn-secondary rounded mt-4 w-100">Clear</button>
                 </div>
                <div class="col-md-1">
                   <button type="submit" class="btn btn-primary rounded mt-4 w-100">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection