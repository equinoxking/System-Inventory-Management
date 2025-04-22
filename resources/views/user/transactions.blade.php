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
            <table class="table table-hover table-striped" id="transactionsTable">
                <thead>
                    <th>Transaction Number</th>
                    <th>Item Name</th>
                    <th>Requested Quantity</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($currentTransactions as $transaction)
                        <tr>
                            <td style="text-align: left">{{ $transaction->transaction_number }}</td>
                            <td style="text-align: left">{{ $transaction->item->name }}</td>
                            <td>{{ $transaction->transactionDetail->request_quantity }}</td>
                            <td>
                                @if($transaction->status && $transaction->status->name == 'Accepted')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Accepted
                                    </span>
                                @elseif($transaction->status && $transaction->status->name == 'Pending')
                                    <span class="badge badge-pending">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{-- {{ $transaction->remark }} --}}
                                @if($transaction->remark && $transaction->remark == 'For Review')
                                    <span class="badge badge-forReview">
                                        <i class="fas fa-search"></i> For Review
                                    </span>
                                @elseif($transaction->remark && $transaction->remark == 'For Release')
                                    <span class="badge badge-release">
                                        <i class="fas fa-cloud-upload-alt"></i> For Release
                                    </span>
                                @else
                                    <span class="badge badge-completed">
                                        <i class="fas fa-check-circle"></i> Completed
                                    </span>
                                @endif
                            </td>     
                            @php
                                $approvedMet = \Carbon\Carbon::parse($transaction->approved_date)->isPast();
                                $releasedMet = \Carbon\Carbon::parse($transaction->released_time)->isPast();
                            @endphp
                            <td>
                                @if ($transaction->remark === "Released" && $approvedMet && $releasedMet)
                                    <button type="button" class="btn btn-warning edit-btn" onclick="userAcceptance('{{ addslashes(json_encode($transaction)) }}')"><i class="fa fa-edit"></i></button>
                                @endif
                            </td>
   
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12" style="text-align: left">
            <h4><strong>TRANSACTION ACTED</strong></h4>
        </div>
    </div>
</div>
<div class="container-fluid" style="background-color: whitesmoke">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover table-striped" id="transactionsTable">
                <thead>
                    <th>Transaction Number</th>
                    <th>Item Name</th>
                    <th>Requested Quantity</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </thead>
                <tbody>
                    @foreach ($actedTransactions as $transaction)
                        <tr>
                            <td style="text-align: left">{{ $transaction->transaction_number }}</td>
                            <td style="text-align: left">{{ $transaction->item->name }}</td>
                            <td>{{ $transaction->transactionDetail->request_quantity }}</td>
                            <td>
                                @if($transaction->status && $transaction->status->name == 'Accepted')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Accepted
                                    </span>
                                @elseif($transaction->status && $transaction->status->name == 'Pending')
                                    <span class="badge badge-pending">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{-- {{ $transaction->remark }} --}}
                                @if($transaction->remark && $transaction->remark == 'For Review')
                                    <span class="badge badge-forReview">
                                        <i class="fas fa-search"></i> For Review
                                    </span>
                                @elseif($transaction->remark && $transaction->remark == 'For Release')
                                    <span class="badge badge-release">
                                        <i class="fas fa-cloud-upload-alt"></i> For Release
                                    </span>
                                @else
                                    <span class="badge badge-completed">
                                        <i class="fas fa-check-circle"></i> Completed
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
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
                        <div class="form-group">
                            <label for="transactionAcceptanceID">Transaction ID</label>
                            <input type="text" class="form-control" name="transaction-acceptance-id" id="transaction-acceptance-id">
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
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/user/items/request-item.js') }}"></script>
<script src="{{ asset('assets/js/user/items/search-items.js') }}"></script>
<script src="{{ asset('assets/js/user/transactions/acceptance.js') }}"></script>
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