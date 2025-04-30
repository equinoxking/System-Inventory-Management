<?php

namespace App\Http\Controllers\Inventory_Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientModel;
use App\Models\RoleModel;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;
use App\Models\InventoryModel;
use App\Models\ItemModel;
use App\Models\NotificationModel;
use App\Models\TransactionStatusModel;
use App\Models\TransactionModel;
use App\Models\UserNotificationModel;
use App\Models\TransactionDetailModel;
use App\Models\AdminModel;
use Illuminate\Support\Carbon;

class DashboardAccountManager extends Controller
{
    public function setUserRole(Request $request){
        $validator = Validator::make($request->all(), [
            'full_name'=> 'required',
            'role_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $client = ClientModel::where('id' , $request->get('full_name'))->first();
            if(!$client){
                return response()->json([
                    'message' => "Check your id",
                    'status' => 404
                ]);
            }else{
                $client = ClientModel::where('id' , $request->get('full_name'))->first();
                $client->role_id = $request->get('role_id');
                $client->save();
                
                if($client){
                    $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                    $user_id = null;
                    $activity = "Set a role of " .  $client->full_name . " into " . $client->role->name . ".";
                    (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                    return response()->json([
                        'status' => 200,
                        'message' => "Set role successful!"
                    ]);
                }else{
                    return response()->json([
                        'status' => 500,
                        'message' => "Check your internet connection!"
                    ]);
                }
            }
        }
    }
    public function updateTransactionStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'status' => 'required', 
            'transaction-status-id' => 'required', 
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
                        $checkInventory = InventoryModel::where('item_id', $transaction->item_id)->first();
                        $requestItem = TransactionDetailModel::where('transaction_id', $transact_id)->first();

                        if($checkInventory->quantity < $requestItem->request_quantity){
                            $status = TransactionStatusModel::where('name', 'Denied')->first();
                            $transaction->remark = "Denied";
                            $transaction->status_id = $status->id;
                            $transaction->reason = "Insufficient Inventory";
                            $transaction->save();
                            if($transaction){
                                $message = "Admin: " . $admin->full_name .
                                " | Transaction: " . $transaction->transaction_number .
                                " | Item: " . $requestItem->request_item .
                                " | Quantity: " . $requestItem->request_quantity . ".";
                                $notification = new NotificationModel();
                                $notification->control_number = $this->generateNotificationNumber();
                                $notification->user_id = $transaction->user_id;
                                $notification->admin_id = $admin->id;
                                $notification->message = $message;
                                $notification->status = "Denied";
                                $notification->save();
                                return response()->json([
                                    'message' => "This request will be denied automatically due to insufficient inventory!",
                                    'status' => 501
                                ]);
                            }else{
                                return response()->json([
                                    'message' => "Check your internet connection!",
                                    'status' => 500
                                ]);
                            }
                        }else{
                            $status = TransactionStatusModel::where('name', 'Accepted')->first();
                            $transaction->released_by = $admin->id;
                            $transaction->released_time = $formattedTime;
                            $transaction->approved_date = $formattedDateNow;
                            $transaction->approved_time = $formattedTimeNow;
                            if (Carbon::now()->lessThan(Carbon::parse($time))) {
                                $transaction->remark = "Ready for Release";
                            } else {
                                $transaction->remark = "Released";
                            }
                            $transaction->request_aging = $agingString;
                            $transaction->status_id = $status->id;
                            
                            $transaction->save();
    
                            $inventory = InventoryModel::where('item_id', $transaction->item_id)->first();
                           
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

                            $user = new UserNotificationModel();
                            $user->user_id = $transaction->user_id;
                            $user->control_number = $this->generateUserNotificationNumber();
                            $user->status = "Issued";
                            $message = "Your request with transaction number " . 
                            $transaction->transaction_number . 
                            " has been accepted, and this transaction has been marked as " . $transaction->remark . ".";
                            $user->message = $message;
                            $user->save();

                            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                            $user_id = null;
                            $activity = "Updated the remarks of Transaction No." . $transaction->transaction_number . " into item received.";
                            (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

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
                                    'message' => "Check your internet connection!",
                                    'status' => 500
                                ]);
                            }  
                            break;
                        }
                       
                    case 3:
                        // Logic for Rejected status
                        $validatorForDenied = Validator::make($request->all(), [
                            'reason' => 'required', 
                        ]);
                        if ($validatorForDenied->fails()) {
                            return response()->json([
                                'status' => 400,
                                'message' => $validatorForDenied->errors()
                            ]);
                        } else{
                            $status = TransactionStatusModel::where('name', 'Denied')->first();
                            $transaction->remark = "Denied";
                            $transaction->status_id = $status->id;
                            $transaction->reason = ucfirst($request->get('reason'));
                            $transaction->save();
                            $details = TransactionDetailModel::where('transaction_id', $transaction->id)->first();
                            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                            $user_id = null;
                            $activity = "Updated the remarks of Transaction No." . $transaction->transaction_number . " Into " . $transaction->remark . " Due to " . $transaction->reason . ".";
                            (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                            $message = "Admin: " . $admin->full_name .
                            " | Transaction: " . $transaction->transaction_number .
                            " | Item: " . $details->request_item .
                            " | Quantity: " . $details->request_quantity . ".";

                            $notification = new NotificationModel();
                            $notification->control_number = $this->generateNotificationNumber();
                            $notification->user_id = $transaction->user_id;
                            $notification->admin_id = $admin->id;
                            $notification->message = $message;
                            $notification->status = "Denied";
                            $notification->save();

                            $user = new UserNotificationModel();
                            $user->user_id = $transaction->user_id;
                            $user->control_number = $this->generateUserNotificationNumber();
                            $user->status = "Denied";
                            $message = "Your request with transaction number " . 
                            $transaction->transaction_number . 
                            " has been denied due to ". $transaction->reason . ", and this transaction has been marked as Denied.";
                            $user->message = $message;
                            $user->save();

                            if($transaction){
                                return response()->json([
                                    'message' => "Status change successful!",
                                    'status' => 200
                                ]);
                            }else{
                                return response()->json([
                                    'message' => "Check your internet connection!",
                                    'status' => 500
                                ]);
                            }
                            break;
                        }
                    default:
                        return response()->json([
                            'message' => "Check your internet connection!",
                            'status' => 400
                        ]);
                }
            }
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
}
