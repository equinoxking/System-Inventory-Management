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
                <label for="action" class="font-weight-bold">Request</label>
            </div>
            <button type="button" class="btn btn-success" id="requestBtn" title="Request item button">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>
</div>
<div class="container-fluid card w-100 shadow rounded p-4" id="requestForm" style="max-height: 500px; overflow-y: auto; background-color: #f8f9fa; display:none; border: 2px solid #ddd;">
    <!-- Form Header -->
    <div class="d-flex justify-content-between align-items-center bg-success text-light p-3 rounded-top">
        <h4 class="m-0 text-center flex-grow-1"><strong>REQUEST ITEM FORM</strong></h4>
        <button type="button" id="requestItem-closeBtn" class="btn btn-danger p-2">&times;</button>
    </div>
    <!-- Form Body -->
    <form action="" id="requestItem-form" class="p-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" id="requestItemReceived-btn" class="btn btn-primary rounded px-4 py-2" title="Add more request item row">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
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
                    <label for="action" class="font-weight-bold">Action</label><br>
                    <button type="button" class="remove-request-item btn btn-danger title="Remove item request row"><i class="fa-solid fa-eraser"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
                <div class="col-md-1 form-group">
                    <label for="" class="font-weight-bold">&nbsp</label>
                    <button type="reset" class="btn btn-secondary rounded px-4 py-2 me-3 w-100">Clear</button>
                </div>
                <div class="col-md-1 form-group">
                    <label for="" class="font-weight-bold">&nbsp</label>
                    <button type="submit" id="requestItemSubmit-btn" class="btn btn-success rounded px-4 py-2 w-100">Submit</button>
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
            <div style="overflow-x: auto; width: 100%;">
                <table id="transactionsTable" style="font-size: 10px; white-space: nowrap;">
                    <thead>
                        <th>Transaction Number</th>
                        <th>Stock On Hand</th>
                        <th>Quantity</th>
                        <th>UoM</th>
                        <th>Item Name</th>
                        <th>Date/Time Requested</th>
                        <th>Date/Time Acted</th>
                        <th>Request Aging</th>
                        <th>Released by</th>
                        <th >Time Released</th>
                        <th class="text-center">Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
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
<div class="container-fluid card w-100">
    <div class="row">
        <div class="col-md-12">
            <!-- Scrollable wrapper -->
            <div style="overflow-x: auto; width: 100%;">
                <table id="historyTransactionTable" style="font-size: 10px; white-space: nowrap;">
                    <thead>
                        <th>Transaction Number</th>
                        <th>Stock On Hand</th>
                        <th>Quantity</th>
                        <th>UoM</th>
                        <th>Item Name</th>
                        <th>Date/Time Requested</th>
                        <th>Date/Time Acted</th>
                        <th>Request Aging</th>
                        <th>Released by</th>
                        <th>Time Released</th>
                        <th class="text-center">Date/Time Received</th>
                        <th>Receive Aging</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="acceptanceTransactionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">ACCEPTANCE TRANSACTION FORM</h5>
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
                            <strong class="text-danger">Are you sure that you received your item?</strong>
                        </div>                        
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="modal-footer d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-danger" id="transaction-acceptance-close-btn">NO</button>
                            <button type="submit" class="btn btn-success" id="transaction-acceptance-submit-btn">YES</button>
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
                    <div class="modal-footer d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-success" id="transaction-cancel-close-btn">NO</button>
                        <button type="submit" class="btn btn-danger" id="transaction-cancel-submit-btn">YES</button>
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
    $(document).on('input', '.requestQuantity', function () {
        const $input = $(this);
        const $row = $input.closest('.request-item-row');
        const maxQuantity = Number($row.find('.requestMaxQuantity').val());
        const inputQuantity = Number($input.val());

        if (inputQuantity > maxQuantity) {
            $input.val(maxQuantity);
        }
        if (inputQuantity < 1) {
            $input.val(1);
        }
    });
});



</script>