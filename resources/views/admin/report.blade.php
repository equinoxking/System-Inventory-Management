@extends('admin.layout.admin-layout')
@section('content')
<div class="container-fluid card w-100">
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
