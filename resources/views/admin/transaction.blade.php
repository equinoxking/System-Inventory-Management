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
        <div class="row mt-2">
            <div class="col-md-12" style="text-align: left">
                <h4><strong >CURRENT TRANSACTIONS</strong></h4>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid card w-100">
    <div class="row">
        <div class="col-md-12">
            <table id="transactionTable" style="font-size: 11px">
                <thead>
                    <th>Time Request</th>
                    <th>Transaction Number</th>
                    <th>Stock On Hand</th>
                    <th>Quantity</th>
                    <th>UoM</th>
                    <th>Item Name</th>
                    <th>Requestor</th>
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
                <tfoot>

                </tfoot>
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
<div class="container-fluid card w-100">
    <div class="row">
        <div class='col-md-12'>
            <table id="transactionHistoryTable" style="font-size: 11px">
                <thead>
                    <th>Time Request</th>
                    <th>Transaction Number</th>
                    <th>Stock On Hand</th>
                    <th>Quantity</th>
                    <th>UoM</th>
                    <th>Item Name</th>
                    <th>Requestor</th>
                    <th>Date/Time Acted</th>
                    <th>Request Aging</th>
                    <th>Released by</th>
                    <th>Time Released</th>
                    <th>Acceptance Aging</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </thead>
                <tbody>
                    @foreach ($transactionHistories as $transaction)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('F d, Y h:i A') }}</td>
                        <td>{{ $transaction->transaction_number }}</td>
                        <td>{{ $transaction->item->inventory->quantity }}</td>
                        <td>{{ $transaction->transactionDetail->request_quantity }}</td>
                        <td>{{ $transaction->item->inventory->unit->name }}</td>
                        <td>{{ $transaction->item->name }}</td>
                        <td>{{ $transaction->client->full_name }}</td>
                        <td>{{ $transaction->approved_time ? \Carbon\Carbon::parse($transaction->approved_date)->format('F d, Y') . ' ' . \Carbon\Carbon::parse($transaction->approved_time)->format('h:i A')  : '' }}</td>
                        <td>{{ $transaction->request_aging }}</td>
                        <td>{{ $transaction->clientBy->full_name }}</td>
                        <td>{{ $transaction->released_time ? \Carbon\Carbon::parse($transaction->released_time)->format('h:i A') : '' }}</td>
                        <td>{{ $transaction->released_aging }}</td>
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
                <tfoot>

                </tfoot>
            </table>
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
                                <option value="">Select Status</option>
                                @foreach ($statuses as $status)
                                    @if ($status->name != 'Pending')
                                        <option value="{{ $status->id }}">{{ $status->name }}</option> 
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="timeDivision" style="display:none;">
                            <label for="releaseTime">Release Time</label><br>
                            <input type="time" class="form-control" id="time" value="" name="time">
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
<script>
window.onload = function() {
    const options = { timeZone: 'Asia/Manila', hour12: false };
    const now = new Date();
    const timeInManila = new Intl.DateTimeFormat('en-GB', {
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'Asia/Manila'
    }).format(now);
    const [hours, minutes] = timeInManila.split(':');
    const currentTime = `${hours}:${minutes}`;
    document.getElementById('time').value = currentTime;
};
function toggleSelection() {
    const status = document.getElementById('status').value;
    const timeDivision = document.getElementById('timeDivision');
    const reasonDivision = document.getElementById('reasonDivision');
    // const dateDivision = document.getElementById('dateDivision');
    if (status === '3') {
        reasonDivision.style.display = 'block';
        timeDivision.style.display = 'none';
        // dateDivision.style.display = 'none';
    } else if (status === '2') {
        reasonDivision.style.display = 'none';
        timeDivision.style.display = 'block';
        // dateDivision.style.display = 'block';
    } else {
        timeDivision.style.display = 'none';
        reasonDivision.style.display = 'none';
        // dateDivision.style.display = 'none';
    }
}
</script>