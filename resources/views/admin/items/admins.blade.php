<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-9" style="text-align: left">
            <h4><strong>ADMINS</strong></h4>
        </div>
        <div class="col-md-3" style="text-align: right">
            <button type="button" class="btn btn-success" id="addAdminBtn" title="Add admin button">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <table id="adminTable" class="table-striped table-hover" style="font-size: 12px">
                <thead>
                    <th>Date/Time Created</th>
                    <th>Date/Time Updated</th>
                    <th>Control Number</th>
                    <th>System Role</th>
                    <th>Full Name</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th width="10%">Action</th>
                </thead>
                <tbody>
                    @foreach ($admins as $admin)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($admin->created_at)->format('F d, Y H:i A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($admin->updated_at)->format('F d, Y H:i A') }}</td>
                            <td>{{ $admin->control_number }}</td>
                            <td>
                                @if ($admin->role->name != null)
                                    Admin
                                @endif
                            </td>
                            <td>{{ $admin->full_name }}</td>
                            <td>{{ $admin->position }}</td>
                            <td id="user_status" class="text-center">
                                @if($admin->status && $admin->status == 'Active')
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
                                <button type="button" class="btn btn-warning" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="editAdmin('{{ addslashes(json_encode($admin)) }}')" title="Edit admin button"><i class="fa fa-edit" style="color: white;"></i></button>
                                <button type="button" class="btn btn-danger" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="deleteAdmin('{{ addslashes(json_encode($admin)) }}')" title="Delete admin button"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" style="color:white;">ADD UNIT FORM</h5>
                    <button type="button" id="add-admin-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="add-admin-form">
                    @csrf
                        <div class="form-group">
                            <label for="addAdminFullName" class="font-weight-bold">Admin Full Name</label>
                            <input type="text" class="form-control" name="admin_full_name" id="add-admin-full-name">
                        </div>
                        <div class="form-group">
                            <label for="addSystemRole" class="font-weight-bold">System Role</label>
                            <select name="system_role" id="add-system-role" class="form-control">
                                <option value="">Select System Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">Admin</option>
                                    @break;
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="addAdminPosition" class="font-weight-bold">Position</label>
                            <input type="text" class="form-control" name="admin_position" id="add-admin-position">
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
<div class="modal fade" id="editAdminModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">EDIT ADMIN FORM</h5>
                    <button type="button" id="edit-admin-close-btn" data-dismiss="modal" class="btn bg-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark" style="color: white;"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="edit-admin-form">
                        @csrf
                        <div class="form-group" hidden>
                            <label for="editAdminId" class="font-weight-bold">Admin Id</label>
                            <input type="text" class="form-control" name="admin_id" id="edit-admin-id">
                        </div>
                        <div class="form-group">
                            <label for="editAdminControlNumber" class="font-weight-bold">Control Number</label>
                            <input type="text" class="form-control" name="admin_control_number" id="edit-admin-control_number" readonly>
                        </div>
                        <div class="form-group">
                            <label for="editAdminFullName" class="font-weight-bold">Admin Full Name</label>
                            <input type="text" class="form-control" name="admin_full_name" id="edit-admin-full-name">
                        </div>
                        <div class="form-group">
                            <label for="editSystemRole" class="font-weight-bold">System Role</label>
                            <select name="system_role" id="edit-system-role" class="form-control">
                                <option value="">Select System Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">Admin</option>
                                    @break;
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editAdminPosition" class="font-weight-bold">Position</label>
                            <input type="text" class="form-control" name="admin_position" id="edit-admin-position">
                        </div>
                        <div class="form-group">
                            <label for="editSystemRole" class="font-weight-bold">Status</label>
                            <select name="admin_status" id="edit-admin-status" class="form-control">
                                <option value="">Select System Role</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-warning" id="edit-admin-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteAdminModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" style="color:white;">DELETE ADMIN FORM</h5>
                    <button type="button" id="delete-admin-close-btn" data-dismiss="modal" class="btn" aria-label="Close" style="background-color: white">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="delete-admin-form">
                        <div class="col-md-3 form-group" hidden>
                            <label for="deleteAdmin">ADMIN ID</label>
                            <input type="text" class="form-control" name="admin_id" id="delete-admin-id">
                        </div>
                        <strong>Are you sure to delete this admin?</strong>
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-danger" id="delete-admin-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/admin/admin/add-admin.js') }}"></script>
<script src="{{ asset('assets/js/admin/admin/edit-admin.js') }}"></script>
<script src="{{ asset('assets/js/admin/admin/delete-admin.js') }}"></script>