<?php

namespace App\Http\Controllers\User\Transactions;

use App\Http\Controllers\Controller;
use App\Models\ClientModel;
use Illuminate\Http\Request;
use App\Models\ItemModel;
use App\Models\TransactionModel;
use App\Models\TransactionStatusModel;
use App\Models\TransactionDetailModel;
use App\Models\InventoryModel;
use App\Models\NotificationModel;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class TransactionsManager extends Controller
{
    public function goToTransactions(){
        if (session()->has('loginCheckUser') || session()->has('loggedInInventoryAdmin')) {
            $user = session()->get('loginCheckUser');
            $client_id = null;
            if ($user) {
                $client_id = $user['id'];
            }
            $items = ItemModel::all();
            if ($client_id) {
                $currentTransactions = TransactionModel::with('transactionDetail')
                    ->where('user_id', $client_id)
                    ->where('remark', '!=', 'Completed')
                    ->orderBy('transaction_number', 'desc')
                    ->get();
                $actedTransactions = TransactionModel::with('transactionDetail')
                ->where('user_id', $client_id,)
                ->where('remark', 'Completed')
                ->orderBy('transaction_number', 'desc')
                ->get();
            } else {
                // If no user (or admin), fetch all transactions (admin or global transactions)
                $transactions = TransactionModel::with('transactionDetail')
                    ->orderBy('transaction_number', 'desc')
                    ->get();
            }

            // Return the view with both items and transactions
            return view('user.transactions', [
                'items' => $items,
                'currentTransactions' => $currentTransactions,
                'actedTransactions' => $actedTransactions,
            ]);
        } 
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

            $notification = new NotificationModel;
            $notification->user_id = $client->id;
            $notification->control_number = $this->generateNotificationNumber();
            $message = "Requestor: " . $client->full_name . 
            " | Transaction: " . $transaction->transaction_number . 
            " | Item: " . $detail->request_item . 
            " | Quantity: " . $detail->request_quantity . ".";
            $notification->message = $message;
            $notification->status = "Pending";
            $notification->save();
            
            // If anything fails during the transaction processing, mark the flag as false
            if (!$transaction || !$detail || !$notification) {
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
    public function goToHistory(){
        $transactions = TransactionModel::where(function ($query) {
            $query->where('remark', 'Completed')
                  ->orWhere('status_id', 3);
        })->get();
        return view('user.history', [
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

            // Get the date and time from the transaction
            $date = $transaction->approved_date; // Example: '2025-04-07'
            $time = $transaction->approved_time; // Example: '09:37:00'

            // Combine the date and time into a single string
            $completeDateTimeString = $date . ' ' . $time; // Example: '2025-04-07 09:37:00'

            // Create a Carbon instance from the combined date and time string
            $completeTime = Carbon::createFromFormat('Y-m-d H:i:s', $completeDateTimeString);

            // Get the current time in your desired timezone (e.g., Asia/Manila)
            $currentDateTime = Carbon::now('Asia/Manila');

            // Calculate the difference in seconds between the two dates
            $diffInSeconds = $completeTime->diffInSeconds($currentDateTime);

            // Break down the difference into days, minutes, and seconds
            $days = floor($diffInSeconds / 86400); // 1 day = 86400 seconds
            $minutes = floor(($diffInSeconds % 86400) / 60); // Remaining minutes after dividing by days
            $seconds = $diffInSeconds % 60; // Remaining seconds after dividing by minutes

            // Format the result as 'X days, Y minutes, Z seconds'
            $agingString = "{$days} days, {$minutes} minutes, {$seconds} seconds";

            // Check if the transaction is valid and save the updated data
            if($transaction){
                $transaction->remark = "Completed";
                $transaction->released_aging = $agingString;
                $transaction->save();

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
}