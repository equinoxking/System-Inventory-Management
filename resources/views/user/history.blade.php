@extends('user.layout.layout')
@section('content')
<div class="container-fluid mt-3">
    <div class="row align-items-center">
        <div class="col-md-2">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('user/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">History</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12" style="text-align: left">
            <h4><strong>TRANSACTION HISTORY</strong></h4>
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
