<?php

namespace App\Http\Controllers\Inventory_Admin\Transactions;

use App\Http\Controllers\Controller;
use App\Models\ClientModel;
use Illuminate\Http\Request;
use App\Models\TransactionModel;
use App\Models\TransactionStatusModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

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
        
        $statuses = TransactionStatusModel::all();

        return view('admin.transaction' ,[
            'transactions' => $transactions,
            'statuses' => $statuses,
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
            $formattedTime = Carbon::createFromFormat('H:i', $time, 'Asia/Manila')->format('H:i');
            $formattedDateNow = $currentDateTime->format('Y-m-d');
            $formattedTimeNow = $currentDateTime->format('H:i');

            switch ($data){
                case $data = 'Accepted' :
                    $client = ClientModel::where('id', $client_id)->first();
                    $status = TransactionStatusModel::where('name', 'Accepted')->first();

                    $transaction = TransactionModel::find($transact_id);
                    $transaction->released_by = $client->id;
                    $transaction->released_time = $formattedTime;
                    $transaction->approved_date = $formattedDateNow;
                    $transaction->approved_time = $formattedTimeNow;
                    $transaction->status_id = $status->id;
                    $transaction->save();

                    if($transaction){
                        return response()->json([
                            'message' => 'Change transaction status success!',
                            'status' => 200
                        ]);
                    }else{
                        return response()->json([
                            'message' => 'Check your internet connection!',
                            'status' => 500
                        ]);
                    }
                break;
            }
        }
    }
}
