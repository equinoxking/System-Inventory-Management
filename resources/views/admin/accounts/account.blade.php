@extends('admin.layout.admin-layout')
@section('content')

<div class="container-fluid card w-100 p-3">
    <!-- Breadcrumb Wrapper -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb"> 
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Accounts</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table id="accountTable" class="table-striped table-hover">
                <thead>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th width="10%">Action</th>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        @if ($client->role->id != 1)
                            <td>{{ $client->full_name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->role->name }}</td>
                            <td id="user_status">
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
                            <td>
                                <button type="button" class="btn btn-warning" onclick="setUser('{{ addslashes(json_encode($client)) }}' )" id="setUserBtn" title="Set permission button"><i class="fa fa-user" style="color: white;"></i></button>
                                <button type="button" class="btn btn-danger" onclick="changeUserStatus('{{ addslashes(json_encode($client)) }}' )" id="changeUserStatusBtn" title="Change user status button"><i class="fa fa-trash"></i></button>
                            </td>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
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
                                <option value="">Select Role</option>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/admin/accounts/clients-setting.js') }}"></script>
<script src="{{ asset('assets/js/admin/accounts/status.js') }}"></script>
<script>
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
