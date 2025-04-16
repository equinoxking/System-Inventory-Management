<?php

namespace App\Http\Controllers\Inventory_Admin\Transactions;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\ClientModel;
use App\Models\InventoryModel;
use App\Models\TransactionDetailModel;
use Illuminate\Http\Request;
use App\Models\TransactionModel;
use App\Models\TransactionStatusModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\ItemModel;
use App\Models\NotificationModel;

class AdminTransactionManager extends Controller
{
    public function goToTransactions(){
        $transactions = TransactionModel::with([
            'transactionDetail',
            'client',
            'item',
            'item.inventory.unit',
            'status',
            'adminBy',
            'admin'
        ])
        ->where('remark', '!=' , 'Completed')
        ->get();
        $transactionHistories = TransactionModel::with([
            'transactionDetail',
            'client',
            'item',
            'item.inventory',
            'item.inventory.unit',
            'status',
            'adminBy',
            'admin'
        ])
        ->where('remark', 'Completed')
        ->orWhere('remark', 'Rejected')
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
            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
            
            $currentDateTime = Carbon::now('Asia/Manila');
            $time = $request->get('time');
            
            // Keep $formattedTime as a Carbon instance (no format yet)
            $formattedTime = Carbon::createFromFormat('H:i', $time, 'Asia/Manila');
            $formattedDateNow = $currentDateTime->format('Y-m-d');
            $formattedTimeNow = $currentDateTime->format('H:i');
            
            // Retrieve client and transaction objects
            $admin = AdminModel::where('id', $admin_id)->first();
            $transaction = TransactionModel::find($transact_id);
            $createdAt = $transaction->created_at;
            $diffInSeconds = $createdAt->diffInSeconds($currentDateTime);
            $days = floor($diffInSeconds / 86400); // 1 day = 86400 seconds
            $minutes = floor(($diffInSeconds % 86400) / 60); // Remaining minutes after dividing by days
            $seconds = $diffInSeconds % 60; // Remaining seconds after dividing by minutes
            $agingString = "{$days} days, {$minutes} minutes, {$seconds} seconds";

            if ($transaction) {
                switch ($data) {
                    case 2:
                        $allItemsProcessed = true;
                        // Logic for Accepted status
                        $status = TransactionStatusModel::where('name', 'Accepted')->first();
                        $transaction->released_by = $admin->id;
                        $transaction->released_time = $formattedTime;
                        $transaction->approved_date = $formattedDateNow;
                        $transaction->approved_time = $formattedTimeNow;
                        $transaction->remark = 'For Release';
                        $transaction->request_aging = $agingString;
                        $transaction->status_id = $status->id;
                        
                        $transaction->save();

                        $inventory = InventoryModel::where('item_id', $transaction->item_id)->first();
                        $requestItem = TransactionDetailModel::where('transaction_id', $transact_id)->first();
                        if ($inventory) {
                            $newQuantity = $inventory->quantity - $requestItem->request_quantity;
                            $inventory->quantity = $newQuantity;
                            $inventory->save();
                        }
                        $message = "Admin: " . $admin->full_name .
                        " | Transaction: " . $transaction->transaction_number .
                        " | Item: " . $requestItem->request_item .
                        " | Quantity: " . $requestItem->request_quantity . ".";
                        $notification = new NotificationModel();
                        $notification->control_number = $this->generateNotificationNumber();
                        $notification->user_id = $transaction->user_id;
                        $notification->admin_id = $admin->id;
                        $notification->message = $message;
                        $notification->status = "Issued";
                        $notification->save();
                        
                        if (!$inventory || !$notification) {
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
        $transactions = TransactionModel::with(['client', 'item', 'transactionDetail', 'status', 'item.inventory', 'admin', 'adminBy'])
            ->where('remark', "!=", 'Completed')
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedTransactions = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'time_request' => \Carbon\Carbon::parse($transaction->created_at)->format('F d, Y h:i A'),
                'transaction_number' => $transaction->transaction_number,
                'client_name' => $transaction->client ? $transaction->client->full_name : $transaction->admin->full_name,
                'item_name' => $transaction->item->name,
                'unit' => $transaction->item->inventory->unit->name,
                'stock_on_hand' => $transaction->item->inventory->quantity,
                'quantity' => $transaction->transactionDetail->request_quantity,
                'released_by' => $transaction->adminBy ? $transaction->adminBy->full_name : '',
                'request_aging' => $transaction->request_aging,
                'time_released' => $transaction->released_time ? \Carbon\Carbon::parse($transaction->released_time)->format('h:i A') : '',
                'time_approved' => $transaction->approved_time ? \Carbon\Carbon::parse($transaction->approved_time)->format('h:i A') : '',
                'date_approved' => $transaction->approved_date ? \Carbon\Carbon::parse($transaction->approved_date)->format('F d, Y') : '',
                'released_aging' => $transaction->released_aging,
                'status' => $transaction->status ? $transaction->status->name : '',
                'remarks' => $transaction->remark,
            ];
        });
    
        return response()->json([
            'data' => $formattedTransactions
        ]);
    }
    public function requestItemAdmin(Request $request) {
        $validator = Validator::make($request->all(), [
            'requestItemId' => 'required|array',
            'requestItemId.*' => 'required|exists:items,id',
            'requestQuantity' => 'required|array',
            'requestQuantity.*' => 'required|numeric|min:1'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }
    
        $allItemsRequested = true;
    
        foreach ($request->requestItemId as $index => $requestItemId) {
            $selectedItemId = ItemModel::find($requestItemId);
            $status = TransactionStatusModel::where('name', 'Accepted')->first();
            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
            $admin = AdminModel::where('id', $admin_id)->first();
    
            if (!$selectedItemId) {
                continue;
            }
    
            $formattedDateNow = Carbon::now('Asia/Manila')->format('Y-m-d');
            $formattedTimeNow = Carbon::now('Asia/Manila')->format('H:i:s');
            $item = ItemModel::find($requestItemId);
    
            $transaction = new TransactionModel();
            $transaction->admin_id = $admin->id;
            $transaction->item_id = $item->id;
            $transaction->status_id = $status->id;
            $transaction->transaction_number = $this->generateTransactionNumber();
            $transaction->approved_date = $formattedDateNow;
            $transaction->approved_time = $formattedTimeNow;
            $transaction->released_time = $formattedTimeNow;
            $transaction->released_by = $admin->id;
            $transaction->request_aging = "0 days, 0 minutes, 1 seconds";
            $transaction->released_aging = "0 days, 0 minutes, 1 seconds";
            $transaction->remark = "Completed";
            $transaction->save();
    
            $monthToInt = [
                'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4, 'May' => 5, 'June' => 6,
                'July' => 7, 'August' => 8, 'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12,
            ];
    
            $day = Carbon::now('Asia/Manila')->format('d');
            $month = Carbon::now('Asia/Manila')->format('F');
            $year = Carbon::now('Asia/Manila')->format('Y');
            $monthInt = $monthToInt[$month];
    
            $detail = new TransactionDetailModel();
            $detail->transaction_id = $transaction->id;
            $detail->item_id = $item->id;
            $detail->request_item = $item->name;
            $detail->request_quantity = $request->requestQuantity[$index];
            $detail->request_day = $day;
            $detail->request_month = $monthInt;
            $detail->request_year = $year;
            $detail->save();
    
            $inventory = InventoryModel::where('item_id', $item->id)->first();
    
            if ($inventory && $inventory->quantity >= $detail->request_quantity) {
                $inventory->quantity -= $detail->request_quantity;
                $inventory->save();
            } else {
                $allItemsRequested = false;
                break;
            }
    
            $notification = new NotificationModel;
            $notification->admin_id = $admin->id;
            $notification->control_number = $this->generateNotificationNumber();
            $message = "Requestor: " . $admin->full_name .
                " | Transaction: " . $transaction->transaction_number .
                " | Item: " . $detail->request_item .
                " | Quantity: " . $detail->request_quantity . ".";
            $notification->message = $message;
            $notification->status = "Accepted";
            $notification->save();
        }
    
        if ($allItemsRequested) {
            return response()->json([
                'message' => "All items successfully requested!",
                'status' => 200
            ]);
        } else {
            return response()->json([
                'message' => "Check your internet connection or some items failed to be processed.",
                'status' => 500
            ]);
        }
    }
    
    private function generateTransactionNumber(){
        $currentYearAndMonth = Carbon::now()->format('Y-m'); 
        $lastTransactionNumber = TransactionModel::whereYear('created_at', Carbon::now()->year)
                                                  ->whereMonth('created_at', Carbon::now()->month)
                                                  ->orderBy('transaction_number', 'desc')
                                                  ->pluck('transaction_number')
                                                  ->first();
        if (!$lastTransactionNumber) {
            return $currentYearAndMonth . '-00001';
        }
        $lastFiveDigits = substr($lastTransactionNumber, -5);
        $incrementedNumber = intval($lastFiveDigits) + 1;
        $paddedNumber = str_pad($incrementedNumber, 5, '0', STR_PAD_LEFT);
        return $currentYearAndMonth . '-' . $paddedNumber;
    }
    private function generateNotificationNumber(){
        $currentYearAndMonth = Carbon::now()->format('Y-m'); 
        $lastControlNumber = NotificationModel::whereYear('created_at', Carbon::now()->year)
                                                  ->whereMonth('created_at', Carbon::now()->month)
                                                  ->orderBy('control_number', 'desc')
                                                  ->pluck('control_number')
                                                  ->first();
        if (!$lastControlNumber) {
            return $currentYearAndMonth . '-00001';
        }
        $lastFiveDigits = substr($lastControlNumber, -5);
        $incrementedNumber = intval($lastFiveDigits) + 1;
        $paddedNumber = str_pad($incrementedNumber, 5, '0', STR_PAD_LEFT);
        return $currentYearAndMonth . '-' . $paddedNumber;
    }     
}
