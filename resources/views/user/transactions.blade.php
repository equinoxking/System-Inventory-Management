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
    <div class="row">
        <div class="col-md-12" style="text-align: left">
            <h4><strong >AVAILABLE ITEMS</strong></h4>
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
<div class="container-fluid mb-3" style="background-color: whitesmoke">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover table-striped" id="itemsTable">
                <thead>
                    <th>Category</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Status</th>
                    <th>Stock Level</th>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td style="text-align: left">{{ $item->category->name }}</td>
                            <td style="text-align: left">{{ $item->name }}</td>
                            <td>{{ $item->inventory->quantity }}</td>
                            <td>{{ $item->inventory->unit->name }}</td>
                            <td>
                                @if($item->inventory->quantity === 0) 
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle"></i> Unavailable
                                    </span>
                                @elseif($item->status && $item->status->name == 'Available')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Available
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle"></i> Unavailable
                                    </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $quantity = $item->inventory->quantity;
                                    $maxQuantity = $item->inventory->max_quantity;
                                    $percentage = ($quantity / $maxQuantity) * 100;
                                @endphp
                                @if($item->inventory->quantity == 0)
                                    <span class="badge badge-noStock">
                                        <i class="fas fa-times-circle"></i> No Stock
                                    </span>
                                @elseif($percentage <= 20)
                                    <span class="badge badge-lowStock">
                                        <i class="fas fa-triangle-exclamation"></i> Low Stock
                                    </span>
                                @elseif($percentage <= 50)
                                    <span class="badge badge-moderateStock">
                                        <i class="fas fa-info-circle"></i> Moderate Stock
                                    </span>
                                @else
                                    <span class="badge badge-highStock">
                                        <i class="fas fa-check-circle"></i> High Stock
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
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12" style="text-align: left">
            <h4><strong>TRANSACTION RECORDS</strong></h4>
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
                    @foreach ($transactions as $transaction)
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
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/user/items/request-item.js') }}"></script>
<script src="{{ asset('assets/js/user/items/search-items.js') }}"></script>
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