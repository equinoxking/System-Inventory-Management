@extends('admin.layout.admin-layout')

@section('content')
<div class="container-fluid mt-3 mb-3">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb"> 
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Reports</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-md-12">
            <table id="auditTable">
                <thead>
                    <th>Prepared By</th>
                    <th>Report Name</th>
                    <th>Category</th>
                    <th>Date Submitted</th>
                    <th>Date Checked</th>
                    <th>Date Approved</th>
                    <th>Status</th>
                    <th>Remarks</th>
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
@endsection
