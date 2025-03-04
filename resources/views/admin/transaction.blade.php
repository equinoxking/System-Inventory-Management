@extends('admin.layout.admin-layout')
@section('content')
    <div class="container-fluid mt-3 mb-3" style="text-align: left;">
        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-info"><i class="fa-solid fa-file-pdf" title="Generate PDF button"></i></button>
            </div>
        </div>
    </div>
    <div class="container-fluid card w-100">
        <div class="row">
            <div class="col-md-12">
                <table id="transactionTable">
                    <thead>
                        <th>Time Request</th>
                        <th>Name</th>
                        <th>Item Name</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Released by</th>
                        <th>Time Released</th>
                        <th>Time Approved</th>
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
