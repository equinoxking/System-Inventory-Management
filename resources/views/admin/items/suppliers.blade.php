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
                    <li class="breadcrumb-item active" aria-current="page">Suppliers</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-9" style="text-align: left">
            <h4><strong>SUPPLIERS</strong></h4>
        </div>
        <div class="col-md-3" style="text-align: right">
            <button type="button" class="btn btn-success" id="addSupplierBtn" title="Add unit button">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <table id="unitTable" class="table-hover" style="font-size: 11px">
                <thead>                    
                    <th width="10%">Supplier Number</th>
                    <th>Supplier Name</th>
                    <th width="5%" class="text-center">Action</th>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->control_number}}</td>
                            <td>{{ $supplier->name }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="editSupplier('{{ addslashes(json_encode($supplier)) }}')" title="Edit supplier button"><i class="fa fa-edit" style="color: white;"></i></button>
                                <button type="button" class="btn btn-danger" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="deleteSupplier('{{ addslashes(json_encode($supplier)) }}')" title="Delete supplier button"><i class="fa fa-trash" style="color: white;"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" style="color:white;">ADD SUPPLIER FORM</h5>
                    <button type="button" id="add-supplier-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="add-supplier-form">
                    @csrf
                        <div class="form-group">
                            <label for="addSupplierName" class="font-weight-bold">Supplier Name</label>
                            <input type="text" class="form-control" name="supplier_name" id="add-supplier-name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="modal-footer">
                            <div class="col-md-3 form-group">
                                <button type="submit" class="btn btn-success" id="add-supplier-submit-btn">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editSupplierModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">EDIT SUPPLIER FORM</h5>
                    <button type="button" id="edit-supplier-close-btn" data-dismiss="modal" class="btn bg-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark" style="color: white;"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="edit-supplier-form">
                        @csrf
                        <div class="form-group" >
                            <label for="supplier_id">Supplier ID</label>
                            <input type="text" class="form-control" name="supplier_id" id="edit-supplier-id">
                        </div>
                        <div class="form-group">
                            <label for="supplier_name">Supplier Name</label>
                            <input type="text" class="form-control" name="supplier_name" id="edit-supplier-name">
                        </div>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-warning" id="edit-supplier-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteSupplierModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" style="color:white;">DELETE SUPPLIER FORM</h5>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="delete-supplier-form">
                        <div class="col-md-3 form-group" hidden>
                            <label for="deleteUnitId">uint ID</label>
                            <input type="text" class="form-control" name="supplier_id" id="delete-supplier-id">
                        </div>
                        <strong>Are you sure you want to delete this supplier?</strong>
                </div>
                <div class="row">
                    <div class="modal-footer d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-success" id="delete-supplier-close-btn">NO</button>
                        <button type="submit" class="btn btn-danger" id="delete-supplier-submit-btn">YES</button>
                    </div>   
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/admin/items/supplier-functions/add-supplier.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/supplier-functions/edit-supplier.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/supplier-functions/delete-supplier.js') }}"></script>