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
                    <th>Date/Time Created</th>
                    <th>Date/Time Updated</th>
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
                                <td>{{ \Carbon\Carbon::parse($client->created_at)->format('F d, Y H:i A') }}</td>
                                <td>{{ \Carbon\Carbon::parse($client->updated_at)->format('F d, Y H:i A') }}</td>
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
<script src="{{ asset('assets/js/admin/accounts/other-function.js') }}"></script>
<script src="{{ asset('assets/js/admin/accounts/clients-setting.js') }}"></script>
<script src="{{ asset('assets/js/admin/accounts/status.js') }}"></script>