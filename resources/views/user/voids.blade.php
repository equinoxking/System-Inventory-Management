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
            <h4><strong>VOIDED TRANSACTIONS</strong></h4>
        </div>
    </div>
</div>
<div class="container-fluid" style="background-color: whitesmoke">
    <div class="row">
        <div class="col-md-12">
            <table id="transactionsVoided" style="font-size: 11px">
                <thead>
                    <th width="10%">Transaction Number</th>
                    <th width="30%">Item Name</th>
                    <th width="10%">Requested Quantity</th>
                    <th width="10%">Status</th>
                    <th width="10%">Remarks</th>
                    <th width="30%">Reason</th>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        @if ($transaction->status_id === 3 || $transaction->status_id === 4)
                        <tr>
                            <td style="text-align: left">{{ $transaction->transaction_number }}</td>
                            <td style="text-align: left">{{ $transaction->item->name }}</td>
                            <td class="text-right">{{ $transaction->transactionDetail->request_quantity }}</td>
                            <td class="text-center">
                                @if($transaction->status && $transaction->status_id == 3)
                                    <span class="badge badge-danger">
                                        <i class="fas fa-check-circle"></i> Denied
                                    </span>
                                @elseif($transaction->status && $transaction->status_id == 4)
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle mr-1"></i> Canceled
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
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
                            <td>{{ $transaction->reason }}</td>    
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
