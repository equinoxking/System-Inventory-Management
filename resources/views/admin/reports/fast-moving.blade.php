@extends('admin.layout.admin-layout')
@section('content')
<div class="container-fluid mt-3 mb-3">
    <div class="row align-items-center">
        <div class="col-md-12">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb"> 
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Reports</li>
                    <li class="breadcrumb-item active" aria-current="page">Fast Moving Items</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-9" style="text-align: left">
            <h4><strong>Fast Moving Items</strong></h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <table id="reportsTable" class="table-hover" style="font-size: 12px">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Total Quantity Requested</th>
                        <th>Times Requested</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fastMovingItems as $detail)
                        <tr>
                            <td>{{ $detail->item->name }}</td>
                            <td>{{ $detail->total_requested }}</td>
                            <td>{{ $detail->request_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="pdfPreviewModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document"> <!-- Use this for Bootstrap 5 -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">PDF Preview</h5>
                <button type="button" class="close btn btn-danger" id="pdf-preview-close-btn" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-0"> <!-- Remove padding -->
                <iframe id="pdfFrame" src="" width="100%" height="100%" style="border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/admin/report/add-report.js') }}"></script>