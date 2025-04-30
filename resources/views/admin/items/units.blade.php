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
                    <li class="breadcrumb-item active" aria-current="page">Units of Measurement</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-9" style="text-align: left">
            <h4><strong>UNITS OF MEASUREMENT</strong></h4>
        </div>
        <div class="col-md-3" style="text-align: right">
            <button type="button" class="btn btn-success" id="addUnitBtn" title="Add unit button">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <table id="unitTable" class="table-striped table-hover" style="font-size: 11px">
                <thead>                    
                    <th width="10%">Unit Number</th>
                    <th width="20%">Unit Name</th>
                    <th width="55%">Description</th>
                    <th width="10%">Date/Time Created</th>
                    <th width="5%" class="text-center">Action</th>
                   
                </thead>
                <tbody>
                    @foreach ($units as $unit)
                        <tr>
                            <td>{{ $unit->control_number}}</td>
                            <td>{{ $unit->name }}</td>
                            <td>{{ $unit->description }}</td>
                            <td>{{ \Carbon\Carbon::parse($unit->created_at)->format('F d, Y H:i A') }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="editUnit('{{ addslashes(json_encode($unit)) }}')" title="Edit unit button"><i class="fa fa-edit" style="color: white;"></i></button>
                                <button type="button" class="btn btn-danger" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="deleteUnit('{{ addslashes(json_encode($unit)) }}')" title="Delete unit button"><i class="fa fa-trash" style="color: white;"></i></button>
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
<div class="modal fade" id="editUnitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">EDIT UNIT FORM</h5>
                    <button type="button" id="edit-unit-close-btn" data-dismiss="modal" class="btn bg-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark" style="color: white;"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="edit-unit-form">
                        @csrf
                        <div class="form-group" hidden>
                            <label for="unit_id">Unit ID</label>
                            <input type="text" class="form-control" name="unit_id" id="edit-unit-id">
                        </div>
                        <div class="form-group">
                            <label for="unit_control_number">Unit Control Number</label>
                            <input type="text" class="form-control" name="unit_control_number" id="edit-unit-control_number" readonly>
                        </div>
                        <div class="form-group">
                            <label for="category_name">Unit Name</label>
                            <input type="text" class="form-control" name="unit_name" id="edit-unit-name">
                        </div>
                        <div class="form-group">
                            <label for="unit_description">Description</label>
                            <textarea type="text" class="form-control" name="unit_description" id="edit-unit-description" cols="5" rows="5" placeholder="This is Optional"></textarea>
                        </div>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-warning" id="edit-unit-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteUnitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" style="color:white;">DELETE UNIT FORM</h5>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="delete-unit-form">
                        <div class="col-md-3 form-group" hidden>
                            <label for="deleteUnitId">uint ID</label>
                            <input type="text" class="form-control" name="unit_id" id="delete-unit-id">
                        </div>
                        <strong>Are you sure you want to delete this unit?</strong>
                </div>
                <div class="row">
                    <div class="modal-footer d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-success" id="delete-unit-close-btn">NO</button>
                        <button type="submit" class="btn btn-danger" id="delete-unit-submit-btn">YES</button>
                    </div>   
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addUnitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" style="color:white;">ADD UNIT FORM</h5>
                    <button type="button" id="add-unit-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="add-unit-form">
                    @csrf
                        <div class="form-group">
                            <label for="addUnitName" class="font-weight-bold">Unit Name</label>
                            <input type="text" class="form-control" name="unit_name" id="add-unit-name">
                        </div>
                        <div class="form-group">
                            <label for="addUnitDescription" class="font-weight-bold">Description</label>
                            <textarea type="text" class="form-control" name="unit_description" id="add-unit-description" placeholder="This is optional" cols="5" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="modal-footer">
                            <div class="col-md-3 form-group">
                                <button type="submit" class="btn btn-success" id="add-unit-submit-btn">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/admin/items/unit-functions/edit-unit.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/unit-functions/delete-unit.js') }}"></script>
<script src="{{ asset('assets/js/admin/items/unit-functions/add-unit.js') }}"></script>