@extends('admin.layout.admin-layout')
@section('content')
<div class="container-fluid card w-100 p-3">
    <!-- Breadcrumb Wrapper -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb"> 
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Audit</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table id="auditTable">
                <thead>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Activity</th>
                    <th>Role</th>
                    <th>Date and Time</th>
                </thead>
                <tbody>

                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
