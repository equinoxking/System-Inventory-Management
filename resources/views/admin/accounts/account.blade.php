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
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        @if ($client->role->id === 4)
                            <td>{{ $client->full_name }}</td>
                            <td>{{ $client->role->name }}</td>
                            <td>
                                @if($client->status && $client->status == 'Active')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td>

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
