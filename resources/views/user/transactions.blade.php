@extends('user.layout.layout')
@section('content')
<div class="container-fluid mt-3">
    <div class="row align-items-center">
        <div class="col-md-2">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('user/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Transactions</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-10 text-end">
            <div>
                <label for="action" class="font-weight-bold">Action</label>
            </div>
            <button type="button" class="btn btn-success" id="requestBtn" title="Request item button">
                <i class="fa-solid fa-handshake"></i>
            </button>
        </div>
    </div>
</div>
<div class="container-fluid card w-100 shadow rounded p-4" id="requestForm" style="max-height: 300px; overflow-y: auto; background-color: #f8f9fa; display:none; border: 2px solid #ddd;">
    <!-- Form Header -->
    <div class="d-flex justify-content-between align-items-center bg-success text-light p-3 rounded-top">
        <h4 class="m-0 text-center flex-grow-1"><strong>REQUEST ITEM FORM</strong></h4>
        <button type="button" id="requestItem-closeBtn" class="btn btn-danger p-2">&times;</button>
    </div>
    <!-- Form Body -->
    <form action="" id="requestItem-form" class="p-3">
        @csrf
        <div id="requestItem-container">
            <div class="row mb-3 mt-2 request-item-row">
                <div class="col-md-3 form-group">
                    <label for="itemName" class="font-weight-bold">Item Name</label>
                    <input type="text" class="search-request-items form-control" name="requestItemName[]" id="requestItemName" placeholder="Search items..." autocomplete="off"/>
                    <ul class="item-results" style="display: none; max-height: 200px; overflow-y: auto;"></ul>
                    <input type="text" class="selected-item-id" id="requestItemId" name="requestItemId[]" hidden>
                </div>
                <div class="col-md-1 form-group">
                    <label for="quantity" class="font-weight-bold">Quantity</label>
                    <input type="number" class="form-control requestQuantity" name="requestQuantity[]" id="requestQuantity" placeholder="Enter quantity" min="1">
                </div>
                <div class="col-md-2 form-group">
                    <label for="maxQuantity" class="font-weight-bold">Available Quantity</label>
                    <input type="number" class="form-control quantity requestMaxQuantity" name="requestMaxQuantity[]" id="requestMaxQuantity" readonly>
                </div>
                <div class="col-md-1 form-group">
                    <label for="action" class="font-weight-bold">Action</label>
                    <button type="button" class="remove-request-item btn btn-danger">Remove</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-between">
                <button type="button" id="requestItemReceived-btn" class="btn btn-primary rounded px-4 py-2">
                    Request more item
                </button>
                <div>
                    <button type="reset" class="btn btn-secondary rounded px-4 py-2 me-3">Clear</button>
                    <button type="submit" id="requestItemSubmit-btn" class="btn btn-success rounded px-4 py-2">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12" style="text-align: left">
            <h4><strong>CURRENT TRANSACTIONS</strong></h4>
        </div>
    </div>
</div>
<div class="container-fluid" style="background-color: whitesmoke">
    <div class="row">
        <div class="col-md-12">
            <table id="transactionsTable" style="font-size: 10px;">
                <thead>
                    <th>Time Request</th>
                    <th>Transaction Number</th>
                    <th>Stock On Hand</th>
                    <th>Quantity</th>
                    <th>UoM</th>
                    <th>Item Name</th>
                    <th>Date/Time Acted</th>
                    <th>Request Aging</th>
                    <th>Released by</th>
                    <th>Time Released</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12" style="text-align: left">
            <h4><strong>ACTED TRANSACTIONS</strong></h4>
        </div>
    </div>
</div>
<div class="container-fluid" style="background-color: whitesmoke">
    <div class="row">
        <div class="col-md-12">
            <table id="historyTransactionTable" style="font-size: 10px;">
                <thead>
                    <th>Time Request</th>
                    <th>Transaction Number</th>
                    <th>Stock On Hand</th>
                    <th>Quantity</th>
                    <th>UoM</th>
                    <th>Item Name</th>
                    <th>Date/Time Acted</th>
                    <th>Request Aging</th>
                    <th>Released by</th>
                    <th>Time Released</th>
                    <th>Availability Aging</th>
                    <th class="text-center">Status</th>
                    <th>Remarks</th>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="acceptanceTransactionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">ACCEPTANCE TRANSACTION FORM</h5>
                    <button type="button" id="transaction-acceptance-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="transaction-acceptance-form">
                        @csrf
                        <div class="form-group" hidden>
                            <label for="transactionAcceptanceID">Transaction ID</label>
                            <input type="text" class="form-control" name="transaction-acceptance-id" id="transaction-acceptance-id">
                        </div>
                        <div class="form-group">
                            <strong class="text-danger">Are you sure that they delivered your item?</strong>
                        </div>                        
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-warning" id="transaction-acceptance-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cancelTransactionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" style="color:white;">CANCEL TRANSACTION FORM</h5>
                    <button type="button" id="transaction-cancel-close-btn" data-dismiss="modal" class="btn btn-light" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="transaction-cancel-form">
                        @csrf
                        <div class="form-group" hidden>
                            <label for="transactionCancelID">Transaction ID</label>
                            <input type="text" class="form-control" name="transaction-cancel-id" id="transaction-cancel-id">
                        </div>
                        <div class="form-group">
                            <strong class="text-danger">Are you sure you want to cancel your transaction?</strong>
                        </div>                        
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-danger" id="transaction-cancel-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/user/items/request-item.js') }}"></script>
<script src="{{ asset('assets/js/user/items/search-items.js') }}"></script>
<script src="{{ asset('assets/js/user/transactions/acceptance.js') }}"></script>
<script src="{{ asset('assets/js/user/transactions/cancel.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const requestQuantity = document.getElementById("requestQuantity");
    const maxQuantity = document.getElementById('requestMaxQuantity');
    requestQuantity.addEventListener('input', function () {
        if (Number(requestQuantity.value) > Number(maxQuantity.value)) {
            $('#requestQuantity').val(maxQuantity.value);
        }
        if(Number(requestQuantity.value) < 0){
            $('#requestQuantity').val(1);
        }
    });
});


</script>