@extends('admin.layout.admin-layout')
@section('content')
<div class="container-fluid mt-3 mb-3">
    <div class="row align-items-center">
        <div class="col-md-8">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb"> 
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Transactions</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <!-- Generate PDF Button Properly Aligned -->
            <button type="button" class="btn btn-info" title="Generate PDF button">
                <i class="fa-solid fa-file-pdf"></i> 
            </button>
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
