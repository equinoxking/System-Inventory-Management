@extends('admin.layout.admin-layout')
@section('content')

<div class="container-fluid mt-3 mb-3">
    <div class="row align-items-center">
        <div class="col-md-12">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lookup Tables</li>
                    <li class="breadcrumb-item active" aria-current="page">Items</li>
                </ol>
            </nav>
        </div>
        <div class="row mt-2">
            <div class="col-md-9" style="text-align: left">
                <h4><strong >PENDING TRANSACTIONS</strong></h4>
            </div>
            <div class="col-md-3" style="text-align: right">
                <button type="button" class="btn btn-success" id="requestBtn" title="Request item button">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
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
        @csrf
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" id="requestItemReceived-btn" class="btn btn-primary rounded px-4 py-2">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
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
                    <button type="button" class="remove-request-item btn btn-danger"> <i class="fa-solid fa-eraser mr-1"></i></button>
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
<div class="container-fluid card w-100">
    <div class="row">
        <div class="col-md-12">
            <div style="overflow-x: auto; width: 100%;">
                <table id="transactionTable" class="table-hover" style="font-size: 11px; white-space: nowrap;">
                    <thead>
                        <th width="5%">Transaction Number</th>
                        <th width="7%">Stock On Hand</th>
                        <th width="7%">Number of Items Requested</th>
                        <th width="5%">UoM</th>
                        <th>Item Name</th>
                        <th>Requested By</th>
                        <th width="10%">Date/Time Requested</th>
                        <th width="5%">Action</th>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>

                    </tfoot>
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
                <table id="transactionHistoryTable" class="table-hover" style="font-size: 11px; white-space: nowrap;">
                    <thead>
                        <tr>
                            <th>Transaction Number</th>
                            <th>Stock On Hand</th>
                            <th>Number of Items Requested</th>
                            <th>UoM</th>
                            <th>Item Name</th>
                            <th>Requested By</th>
                            <th>Date/Time Requested</th>
                            <th>Date/Time Acted</th>
                            <th>Request Aging</th>
                            <th>Released by</th>
                            <th>Time Released</th>
                            <th>Date/Time Received</th>
                            <th>Receive Aging</th>
                            <th class="text-center">Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Your data rows go here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
<div class="modal fade" id="transactionStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" style="color:white;">TRANSACTION STATUS FORM</h5>
                    <button type="button" id="transaction-status-close-btn" data-dismiss="modal" class="btn btn-danger" aria-label="Close">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                    <form id="transaction-status-form">
                        <div class="form-group" hidden>
                            <label for="transactionStatusID">Transaction ID</label>
                            <input type="text" class="form-control" name="transaction-status-id" id="transaction-status-id">
                        </div>
                        <div class="form-group" >
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" onchange="toggleSelection()">
                                <option value="2">Release</option>
                                <option value="3">Disapprove</option>
                            </select>
                        </div>
                        <div class="form-group" id="timeDivision1">
                            <label for="releaseTime">Release Time</label><br>
                            <input type="time" class="form-control" id="timeRelease" value="" name="time" readonly>
                        </div>
                        <div class="form-group" style="display:none;" id="reasonDivision">
                            <label for="reason">Reason</label><br>
                            <textarea name="reason" id="reason" cols="5" rows="5" class="form-control"></textarea>
                        </div>                        
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-warning" id="transaction-status-submit-btn">SUBMIT</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/admin/transactions/status.js') }}"></script>
<script src="{{ asset('assets/js/admin/transactions/request-item.js') }}"></script>
<script src="{{ asset('assets/js/admin/transactions/search-items.js') }}"></script>

<script>
    document.addEventListener('input', function (event) {
        if (event.target.classList.contains('requestQuantity')) {
            const quantityInput = event.target;
            const row = quantityInput.closest('.request-item-row');
            const maxInput = row.querySelector('.requestMaxQuantity');
    
            const enteredQty = parseInt(quantityInput.value, 10);
            const maxQty = parseInt(maxInput.value, 10);
    
            if (!isNaN(enteredQty) && !isNaN(maxQty) && enteredQty > maxQty) {
                quantityInput.value = maxQty;
            }
        }
    });
</script>
    