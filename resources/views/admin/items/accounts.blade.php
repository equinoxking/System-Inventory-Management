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
                    <li class="breadcrumb-item active" aria-current="page">User Accounts and Admins</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-9" style="text-align: left">
            <h4><strong>USER ACCOUNTS</strong></h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <table id="accountTable" class="table-striped table-hover" style="font-size: 12px">
                <thead>
                    <th width="8%">Employee Number</th>
                    <th width="30%">Full Name</th>
                    <th width="20%">Email</th>
                    <th width="8%">System Role</th>
                    <th width="5%">Office</th>
                    <th width="8%">Position</th>
                    <th width="10%">Date/Time Created</th>
                    <th width="5%">Status</th>
                    <th width="5%">Action</th>
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
                                <td>{{ \Carbon\Carbon::parse($client->created_at)->format('F d, Y H:i A') }}</td>
                                <td id="user_status" class="text-center">
                                    @if($client->status && $client->status == 'Active')
                                        <span class="badge badge-success" style="width: 4rem; font-size: 10px;" id="status-badge">
                                            <i class="fas fa-check-circle"></i> Active
                                        </span>
                                    @else
                                        <span class="badge badge-danger" style="width: 4rem; font-size: 10px;" id="status-badge">
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
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-9" style="text-align: left">
            <h4><strong>ADMINISTRATORS</strong></h4>
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
                    <th width="10%">Admin Number</th>
                    <th>Full Name</th>
                    <th width="10%">System Role</th>
                    <th width="10%">Office Position</th>
                    <th width="10%">Date/Time Created</th>
                    <th width="10%">Status</th>
                    <th width="5%">Action</th>
                </thead>
                <tbody>
                    @foreach ($admins as $admin)
                        <tr>
                      
                            <td>{{ $admin->control_number }}</td>
                            <td>{{ $admin->full_name }}</td>
                            <td>
                                {{ preg_replace('/([a-z])([A-Z])/', '$1 $2', $admin->role->name) }}
                            </td>
                            <td>{{ $admin->position }}</td>
                            <td>{{ \Carbon\Carbon::parse($admin->created_at)->format('F d, Y H:i A') }}</td>
                            <td id="user_status" class="text-center">
                                @if($admin->status && $admin->status == 'Active')
                                    <span class="badge badge-success" style="width: 4rem; font-size: 10px;" id="status-badge">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="badge badge-danger" style="width: 4rem; font-size: 10px;" id="status-badge">
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
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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

<div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" style="color:white;">ADD ADMIN FORM</h5>
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
                                    @if ($role->name != 'User')
                                        <option value="{{ $role->id }}">
                                            {{ preg_replace('/([a-z])([A-Z])/', '$1 $2', $role->name) }}
                                        </option>
                                    @endif
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
                                    @if ($role->name != 'User')
                                        <option value="{{ $role->id }}">
                                            {{ preg_replace('/([a-z])([A-Z])/', '$1 $2', $role->name) }}
                                        </option>
                                    @endif
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
                </div>
            <div class="modal-body">
                <div class="row">
                <form id="delete-admin-form">
                        <div class="col-md-3 form-group" hidden>
                            <label for="deleteAdmin">ADMIN ID</label>
                            <input type="text" class="form-control" name="admin_id" id="delete-admin-id">
                        </div>
                        <strong>Are you sure you want to delete this admin?</strong>
                </div>
                <div class="row">
                    <div class="modal-footer d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-success" id="delete-admin-close-btn">NO</button>
                        <button type="submit" class="btn btn-danger" id="delete-admin-submit-btn">YES</button>
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
<script src="{{ asset('assets/js/admin/accounts/other-function.js') }}"></script>
<script src="{{ asset('assets/js/admin/accounts/clients-setting.js') }}"></script>
<script src="{{ asset('assets/js/admin/accounts/status.js') }}"></script>