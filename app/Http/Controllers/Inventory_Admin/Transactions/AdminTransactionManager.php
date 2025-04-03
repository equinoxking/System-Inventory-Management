<?php

namespace App\Http\Controllers\Inventory_Admin\Transactions;

use App\Http\Controllers\Controller;
use App\Models\ClientModel;
use App\Models\InventoryModel;
use App\Models\TransactionDetailModel;
use Illuminate\Http\Request;
use App\Models\TransactionModel;
use App\Models\TransactionStatusModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AdminTransactionManager extends Controller
{
    public function goToTransactions(){
        $transactions = TransactionModel::with([
            'transactionDetail',
            'client',
            'item',
            'item.inventory.unit',
            'status',
            'clientBy'
        ])
        ->where('remark', '!=' , 'Completed')
        ->get();
        $transactionHistories = TransactionModel::with([
            'transactionDetail',
            'client',
            'item',
            'item.inventory.unit',
            'status',
            'clientBy'
        ])
        ->where('remark', 'Completed')
        ->get();
        $statuses = TransactionStatusModel::all();

        return view('admin.transaction' ,[
            'transactions' => $transactions,
            'statuses' => $statuses,
            'transactionHistories' => $transactionHistories
        ]);
    }
        public function updateTransactionStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'status' => 'required', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $data = $request->get('status');
            $transact_id = $request->get('transaction-status-id');
            $client_id = session()->get('loggedInInventoryAdmin')['id'];
            
            $currentDateTime = Carbon::now('Asia/Manila');
            $time = $request->get('time');
            
            // Keep $formattedTime as a Carbon instance (no format yet)
            $formattedTime = Carbon::createFromFormat('H:i', $time, 'Asia/Manila');
            $formattedDateNow = $currentDateTime->format('Y-m-d');
            $formattedTimeNow = $currentDateTime->format('H:i');
            
            // Retrieve client and transaction objects
            $client = ClientModel::where('id', $client_id)->first();
            $transaction = TransactionModel::find($transact_id);

            if ($transaction) {
                switch ($data) {
                    case 2:
                        $allItemsProcessed = true;
                        // Logic for Accepted status
                        $status = TransactionStatusModel::where('name', 'Accepted')->first();
                        $transaction->released_by = $client->id;
                        $transaction->released_time = $formattedTime;
                        $transaction->approved_date = $formattedDateNow;
                        $transaction->approved_time = $formattedTimeNow;
                        
                        // Compare the release time with the current time
                        if ($formattedTime->isBefore($currentDateTime)) {
                            // If release time is in the past, mark as "Completed"
                            $transaction->remark = 'Completed';
                        } else {
                            // Otherwise, mark it as "For Release"
                            $transaction->remark = 'For Release';
                        }
                        
                        $transaction->status_id = $status->id;
                        
                        $transaction->save();

                        $inventory = InventoryModel::where('item_id', $transaction->item_id)->first();
                        $requestItem = TransactionDetailModel::where('transaction_id', $transact_id)->first();
                        if ($inventory) {
                            $newQuantity = $inventory->quantity - $requestItem->request_quantity;
                            $inventory->quantity = $newQuantity;
                            $inventory->save();
                        }
                        if (!$inventory) {
                            $allItemsProcessed = false;
                            break;
                        }
                        if ($allItemsProcessed) {
                            return response()->json([
                                'message' => "Status change successful!",
                                'status' => 200
                            ]);
                        } else {
                            return response()->json([
                                'message' => "Error in changing status!",
                                'status' => 500
                            ]);
                        }  
                        break;

                    case 3:
                        // Logic for Rejected status
                        $status = TransactionStatusModel::where('name', 'Rejected')->first();
                        $transaction->status_id = $status->id;
                        $transaction->reason = ucfirst($request->get('reason'));
                        $transaction->save();
                        break;

                    default:
                        return response()->json([
                            'message' => 'Invalid status!',
                            'status' => 400
                        ]);
                }
            }
        }
    }
    public function getTransactions(Request $request)
    {
        $transactions = TransactionModel::with(['client', 'item', 'transactionDetail', 'status'])
            ->where('remark', "!=", 'Completed')
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedTransactions = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'time_request' => \Carbon\Carbon::parse($transaction->created_at)->format('F d, Y h:i A'),
                'transaction_number' => $transaction->transaction_number,
                'client_name' => $transaction->client ? $transaction->client->full_name : 'No client',
                'item_name' => $transaction->item->name,
                'unit' => $transaction->item->inventory->unit->name,
                'quantity' => $transaction->transactionDetail->request_quantity,
                'released_by' => $transaction->clientBy ? $transaction->clientBy->full_name : '',
                'time_released' => $transaction->released_time ? \Carbon\Carbon::parse($transaction->released_time)->format('h:i A') : '',
                'time_approved' => $transaction->approved_time ? \Carbon\Carbon::parse($transaction->approved_time)->format('h:i A') : '',
                'date_approved' => $transaction->approved_date ? \Carbon\Carbon::parse($transaction->approved_date)->format('F d, Y') : '',
                'status' => $transaction->status ? $transaction->status->name : '',
                'remarks' => $transaction->remark,
            ];
        });
    
        return response()->json([
            'data' => $formattedTransactions
        ]);
    }    
}
