<?php

namespace App\Http\Controllers\User\Transactions;

use App\Http\Controllers\Controller;
use App\Models\ClientModel;
use Illuminate\Http\Request;
use App\Models\ItemModel;
use App\Models\TransactionModel;
use App\Models\TransactionStatusModel;
use App\Models\TransactionDetailModel;
use App\Models\NotificationModel;
use App\Models\UserNotificationModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;

class TransactionsManager extends Controller
{
    public function goToTransactions(){
        $items = ItemModel::all();
        $client_id = session()->get('loginCheckUser')['id'];
        $currentTransactions = TransactionModel::with('transactionDetail')
        ->where('user_id', $client_id)
        ->where('remark', '!=', 'Completed')
        ->orderBy('transaction_number', 'desc')
        ->get();
        $actedTransactions = TransactionModel::with('transactionDetail')
        ->where('user_id', $client_id)
        ->where('remark', 'Completed')
        ->orderBy('transaction_number', 'desc')
        ->get();
        return view('user.transactions', [
                'items' => $items,
                'currentTransactions' => $currentTransactions,
                'actedTransactions' => $actedTransactions,
        ]);
    }
    public function searchItem(Request $request){
        $query = $request->input('query'); 
        $items = ItemModel::where('name', 'like', '%' . $query . '%')
        ->with('inventory')
        ->get();
        return response()->json($items);
    }
    public function requestItem(Request $request) {
        $validator = Validator::make($request->all(), [
            'requestItemName' => 'required|array', 
            'requestItemName.*' => 'required',
            'requestQuantity' => 'required|array',
            'requestQuantity.*' => 'required|numeric|min:1'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }
    
        // Initialize success flag
        $allItemsRequested = true;
    
        // Process the requestItemId array
        foreach ($request->requestItemId as $index => $requestItemId) {
            $selectedItemId = ItemModel::findOrFail($requestItemId);
            $status = TransactionStatusModel::where('name', 'Pending')->first();

            $client_id = null;

            // Determine the client ID from session
            if (session()->has('loginCheckUser')) {
                $client_id = session()->get('loginCheckUser')['id'];
            } elseif (session()->has('loggedInInventoryAdmin')) {
                $client_id = session()->get('loggedInInventoryAdmin')['id'];
            }

            // Optional: handle missing session or client_id
            if (!$client_id) {
                abort(403, 'Unauthorized access.');
            }

            // Now fetch client based on resolved ID
            $client = ClientModel::where('id', $client_id)->first();

            
            if (!$selectedItemId) {
                continue;  
            }
    
            $item = ItemModel::find($requestItemId);
            $transaction = new TransactionModel();
            $transaction->user_id = $client->id;
            $transaction->item_id = $item->id;
            $transaction->status_id = $status->id;
            $transaction->transaction_number = $this->generateTransactionNumber();
            $transaction->remark = "For Review";
            $transaction->save();
    
            $monthToInt = [
                'January' => 1,
                'February' => 2,
                'March' => 3,
                'April' => 4,
                'May' => 5,
                'June' => 6,
                'July' => 7,
                'August' => 8,
                'September' => 9,
                'October' => 10,
                'November' => 11,
                'December' => 12,
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

            $notification = new NotificationModel();
            $notification->user_id = $client->id;
            $notification->control_number = $this->generateNotificationNumber();
            $message = "Requestor: " . $client->full_name . 
            " | Transaction: " . $transaction->transaction_number . 
            " | Item: " . $detail->request_item . 
            " | Quantity: " . $detail->request_quantity . ".";
            $notification->message = $message;
            $notification->status = "Pending";
            $notification->save();
            
            $user = new UserNotificationModel();
            $user->user_id = $client->id;
            $user->control_number = $this->generateUserNotificationNumber();
            $user->status = "Pending";
            $message = "Your request with transaction number " . 
            $transaction->transaction_number . 
            " is now on pending, and this transaction has been marked as " . $transaction->remark . ".";
            $user->message = $message;
            $user->save();

            $admin_id = null;
            $user_id =  session()->get('loginCheckUser')['id'];
            $activity = "Created a new request with a transaction number of " . $transaction->transaction_number . ".";
            (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

            // If anything fails during the transaction processing, mark the flag as false
            if (!$transaction || !$detail || !$notification || !$user) {
                $allItemsRequested = false;
            }
        }
    
        // After loop finishes, check if all items were successfully requested
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
    private function generateUserNotificationNumber(){
        $currentYearAndMonth = Carbon::now()->format('Y-m'); 
        $lastControlNumber = UserNotificationModel::whereYear('created_at', Carbon::now()->year)
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
    public function goToHistory(){
        $transactions = TransactionModel::where(function ($query) {
            $client_id = session()->get('loginCheckUser')['id'];
            $query->where(function ($subQuery) {
                $subQuery->where('status_id', 2)
                         ->orWhere('status_id', 3)
                         ->orWhere('status_id', 4);
            })->where('user_id', $client_id);
        })->get();
        
        return view('user.voids', [
            'transactions' => $transactions
        ]);
    }
    public function updateTransaction(Request $request){
        $validator = Validator::make($request->all(), [
            'transaction-acceptance-id' => 'required|exists:transactions,id', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }else{
            $transaction = TransactionModel::findOrFail($request->get('transaction-acceptance-id'));
            
            $approved_date = $transaction->approved_date; // Example: "2025-05-07"
            $released_time = $transaction->released_time; // Example: "15:37:20.0000000"
            
            // Remove microseconds if present
            $released_time = preg_replace('/\.\d+$/', '', $released_time);  // Remove microseconds part (if any)

            try {
                // Try to create the datetime object using the format without microseconds
                $releasedDateTime = Carbon::createFromFormat(
                    'Y-m-d H:i:s', // Format without microseconds
                    "{$approved_date} {$released_time}", // Combine date and time
                    'Asia/Manila'
                );
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 500,
                    'message' => "Error parsing date and time: " . $e->getMessage()
                ]);
            }

            // Get the current datetime (when status is being updated)
            $now = Carbon::now('Asia/Manila');

            // Calculate the difference in minutes (signed)
            $minutesDifference = $releasedDateTime->diffInMinutes($now, false); // false gives signed result

            // Optionally: breakdown
            $diffInSeconds = $releasedDateTime->diffInSeconds($now, false);
            $days = floor(abs($diffInSeconds) / 86400);
            $minutes = floor((abs($diffInSeconds) % 86400) / 60);
            $seconds = abs($diffInSeconds) % 60;

            // Format it
            $direction = $minutesDifference >= 0 ? 'after' : 'before';
            $agingString = "{$days} days, {$minutes} minutes, {$seconds} seconds";

            // Check if the transaction is valid and save the updated data
            if($transaction){
                $transaction->remark = "Completed";
                $transaction->released_aging = $agingString;
                $transaction->accepted_date_time = $now;
                $transaction->save();
                $detail = TransactionDetailModel::where('transaction_id', $transaction->id)->first();
                $notification = new NotificationModel;
      
                $notification->control_number = $this->generateNotificationNumber();
                $message = "Requestor: " . $transaction->client->full_name .
                    " | Transaction: " . $transaction->transaction_number .
                    " | Item: " . $detail->request_item .
                    " | Quantity: " . $detail->request_quantity . ".";
                $notification->message = $message;
                $notification->status = "Accepted";
                $notification->save();

                $user = new UserNotificationModel();
                $user->user_id = $transaction->user_id;
                $user->control_number = $this->generateUserNotificationNumber();
                $user->status = "Accepted";
                $message = "Your request with transaction number " . 
                $transaction->transaction_number . 
                " has been received, and this transaction has been marked as Completed.";
                $user->message = $message;
                $user->save();

                $admin_id = null;
                $user_id =  session()->get('loginCheckUser')['id'];
                $activity = "Updated own request with a transaction number of " . $transaction->transaction_number . " into " . $transaction->remark . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                return response()->json([
                    'message' => "Transaction successfully updated!",
                    'status' => 200
                ]);
            }else{
                return response()->json([
                    'message' => "Check your internet connection!",
                    'status' => 500
                ]);
            }

        }
    }
    public function cancelTransaction(Request $request){
        $validator = Validator::make($request->all(), [
            'transaction-cancel-id' => 'required|exists:transactions,id', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }else{
            $transaction = TransactionModel::findOrFail($request->get('transaction-cancel-id'));
            $transaction->status_id = 4;
            $transaction->remark = "Canceled";
            $transaction->save();

            $detail = TransactionDetailModel::where('transaction_id', $transaction->id)->first();
            if($transaction){
                $user = new UserNotificationModel();
                $user->user_id = $transaction->user_id;
                $user->control_number = $this->generateUserNotificationNumber();
                $user->status = "Canceled";
                $message = "Your request with transaction number " . 
                $transaction->transaction_number . 
                " has been canceled, and this transaction has been marked as Canceled.";
                $user->message = $message;
                $user->save();

                $notification = new NotificationModel();
                $notification->control_number = $this->generateNotificationNumber();
                $message = "Requestor: " . $transaction->client->full_name .
                    " | Transaction: " . $transaction->transaction_number .
                    " | Item: " . $detail->request_item .
                    " | Quantity: " . $detail->request_quantity . ".";
                $notification->message = $message;
                $notification->status = "Canceled";
                $notification->save();

                $admin_id = null;
                $user_id =  session()->get('loginCheckUser')['id'];
                $activity = "Updated the status of transaction No." . $transaction->transaction_number . " into canceled.";

                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);
                return response()->json([
                    'message' => "Transaction successfully updated!",
                    'status' => 200
                ]);
            }else{
                return response()->json([
                    'message' => "Check your internet connection!",
                    'status' => 500
                ]);
            }
        }
    }
    public function getTransactions(Request $request)
    {
        $client_id = session()->get('loginCheckUser')['id'];

        $transactions = TransactionModel::with([
            'client', 'item', 'transactionDetail', 'status', 'item.inventory', 'admin', 'adminBy'
        ])
        ->where(function ($query) use ($client_id) {
            $query->where(function ($q) {
                $q->where('status_id', 1)
                ->orWhere('status_id', 2);
            })
            ->where('user_id', $client_id)
            ->where('remark', '!=', 'Completed');
        })
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
    public function getActedTransactions(Request $request)
    {
        $client_id = session()->get('loginCheckUser')['id'];

        $transactions = TransactionModel::with([
        'client', 'item', 'transactionDetail', 'status', 'item.inventory', 'admin', 'adminBy'
        ])
        ->where('user_id', $client_id)
        ->where(function ($query) {
            $query->whereIn('status_id', [3, 4])
                ->orWhere('remark', 'Completed');
            })
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
                'acceptance' => $transaction->accepted_date_time ?  \Carbon\Carbon::parse($transaction->accepted_date_time)->format('F d, Y h:i A') : '',
                'status' => $transaction->status ? $transaction->status->name : '',
                'remarks' => $transaction->remark,
                'reason' => $transaction->reason,
            ];
        });
        return response()->json([
            'data' => $formattedTransactions
        ]);
    }
}