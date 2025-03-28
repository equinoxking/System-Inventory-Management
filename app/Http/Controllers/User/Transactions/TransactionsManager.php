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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class TransactionsManager extends Controller
{
    public function goToTransactions()
    {
        $client_id = session()->get('loginCheckUser')['id'];
        $items = ItemModel::all();
        $transactions = TransactionModel::with('transactionDetail')
        ->where('user_id' , $client_id)
        ->orderBy('transaction_number', 'desc')
        ->get();
        return view('user.transactions', [
            'items' => $items,
            'transactions' => $transactions
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
            $client_id = session()->get('loginCheckUser')['id'];
            $status = TransactionStatusModel::where('name', 'Pending')->first();
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
            $detail->request_item = $item->name;
            $detail->request_quantity = $request->requestQuantity[$index];
            $detail->request_day = $day;
            $detail->request_month = $monthInt;
            $detail->request_year = $year;
            $detail->save();
    
            // If anything fails during the transaction processing, mark the flag as false
            if (!$transaction || !$detail) {
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

        // Get the current year and month in the format YYYY-MM
        $currentYearAndMonth = Carbon::now()->format('Y-m'); 
        
        // Get the most recent transaction_number for the current year and month
        $lastTransactionNumber = TransactionModel::whereYear('created_at', Carbon::now()->year)
                                                  ->whereMonth('created_at', Carbon::now()->month)
                                                  ->orderBy('transaction_number', 'desc')
                                                  ->pluck('transaction_number')
                                                  ->first();
        
        // If no transaction number exists, start from 00001
        if (!$lastTransactionNumber) {
            return $currentYearAndMonth . '-00001';
        }
        
        // Extract the numeric part from the transaction_number, which is the last 5 digits
        $lastFiveDigits = substr($lastTransactionNumber, -5);
        
        // Increment the numeric part
        $incrementedNumber = intval($lastFiveDigits) + 1;
        
        // Ensure the incremented number is always 5 digits long, padding with leading zeros if necessary
        $paddedNumber = str_pad($incrementedNumber, 5, '0', STR_PAD_LEFT);
        
        // Return the new transaction number in the format YYYY-MM-XXXXX
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
}