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
    public function setUserRoleDashboard(Request $request){
        // Validate request input
        $validator = Validator::make($request->all(), [
            'full_name' => 'required', // Still assuming this is an ID, consider renaming
            'role_id' => 'required|exists:roles,id' // Validate that the role exists
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }

        // Fetch the client once
        $client = ClientModel::find($request->get('full_name'));

        if (!$client) {
            return response()->json([
                'message' => "Client not found with the provided ID.",
                'status' => 404
            ]);
        }

        // Update role and check for change before saving
        if ($client->role_id !== $request->get('role_id')) {
            $client->role_id = $request->get('role_id');
            $client->save();

            // Fetch admin ID safely
            $adminSession = session()->get('loggedInInventoryAdmin');
            $admin_id = $adminSession['admin_id'] ?? null;
            
            $admin = new AdminModel();
            $admin->client_id = $client->id;
            $admin->role_id = $client->role_id;
            $admin->full_name = $client->full_name;
            $admin->status = "Active";
            $admin->control_number = $client->employee_number;
            $admin->position = $client->position;
            $admin->save();
            // Log the role change activity
            $user_id = null;
            $activity = "Set the role of " . $client->full_name . " to " . optional($client->role)->name . ".";
            (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);
        }

        return response()->json([
            'status' => 200,
            'message' => "Role set successfully."
        ]);
    }
    public function updateTransactionStatus(Request $request){
        // Validate required input fields
        $validator = Validator::make($request->all(), [
            'status' => 'required', 
            'transaction-status-id' => 'required', 
        ]);
    
        // Return validation errors if inputs are invalid
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            // Get status and transaction ID from request
            $data = $request->get('status');
            $transact_id = $request->get('transaction-status-id');
    
            // Get currently logged-in admin ID from session
            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
            
            // Current date and time in Asia/Manila timezone
            $admin = AdminModel::where('id', $admin_id)->first();
            $transaction = TransactionModel::find($transact_id);
            // Proceed if transaction is found
            if ($transaction) {
                switch ($data) {
                    case 2:
                        $allItemsProcessed = true;
                        $currentDateTime = Carbon::now('Asia/Manila');
                        $time = $request->get('time');
                        
                        // Create a Carbon instance from provided time (no formatting applied yet)
                        $formattedTime = Carbon::createFromFormat('H:i', $time, 'Asia/Manila');
                        $formattedDateNow = $currentDateTime->format('Y-m-d');
                        $formattedTimeNow = $currentDateTime->format('H:i');
                        
                        // Retrieve admin and transaction records
                      
                
                        // Calculate aging time from transaction creation to current time
                        $createdAt = $transaction->created_at;
                        $diffInSeconds = $createdAt->diffInSeconds($currentDateTime);
                        $days = floor($diffInSeconds / 86400);
                        $minutes = floor(($diffInSeconds % 86400) / 60);
                        $seconds = $diffInSeconds % 60;
                        $agingString = "{$days} days, {$minutes} minutes, {$seconds} seconds";
                        // Get current inventory and request item details
                        $checkInventory = InventoryModel::where('item_id', $transaction->item_id)->first();
                        $requestItem = TransactionDetailModel::where('transaction_id', $transact_id)->first();
    
                        // Check if inventory is insufficient
                        if($checkInventory->quantity < $requestItem->request_quantity){
                            // Mark transaction as Denied due to insufficient inventory
                            $status = TransactionStatusModel::where('name', 'Denied')->first();
                            $transaction->remark = "Denied";
                            $transaction->status_id = $status->id;
                            $transaction->reason = "Insufficient Inventory";
                            $transaction->save();
    
                            // Send notification about denial
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
                            } else {
                                return response()->json([
                                    'message' => "Check your internet connection!",
                                    'status' => 500
                                ]);
                            }
                        } else {
                            // Inventory is sufficient, proceed to Accept the transaction
                            $status = TransactionStatusModel::where('name', 'Accepted')->first();
                            $transaction->released_by = $admin->id;
                            $transaction->released_time = $formattedTime;
                            $transaction->approved_date = $formattedDateNow;
                            $transaction->approved_time = $formattedTimeNow;
    
                            // Determine transaction remark based on time comparison
                            if (Carbon::now()->lessThan(Carbon::parse($time))) {
                                $transaction->remark = "Ready for Release";
                            } else {
                                $transaction->remark = "Released";
                            }
    
                            $transaction->request_aging = $agingString;
                            $transaction->status_id = $status->id;
                            $transaction->save();
    
                            // Update inventory quantity
                            $inventory = InventoryModel::where('item_id', $transaction->item_id)->first();
                            if ($inventory) {
                                $newQuantity = $inventory->quantity - $requestItem->request_quantity;
                                $inventory->quantity = $newQuantity;
                                $inventory->save();
                            }
    
                            // Create admin notification
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
    
                            // Create user notification
                            $user = new UserNotificationModel();
                            $user->user_id = $transaction->user_id;
                            $user->control_number = $this->generateUserNotificationNumber();
                            $user->status = "Issued";
                            $message = "Your request with transaction number " . 
                            $transaction->transaction_number . 
                            " has been approved, and this transaction has been marked as " . $transaction->remark . ".";
                            $user->message = $message;
                            $user->save();
    
                            // Log the action
                            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                            $user_id = null;
                            $activity = "Updated the remarks of Transaction No." . $transaction->transaction_number . " into item received.";
                            (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);
    
                            // Final status checks
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
                        // Validation for Rejected case
                        $validatorForDenied = Validator::make($request->all(), [
                            'reason' => 'required', 
                        ]);
                        if ($validatorForDenied->fails()) {
                            return response()->json([
                                'status' => 400,
                                'message' => $validatorForDenied->errors()
                            ]);
                        } else {
                            // Mark transaction as Denied with reason
                            $status = TransactionStatusModel::where('name', 'Denied')->first();
                            $transaction->remark = "Denied";
                            $transaction->status_id = $status->id;
                            $transaction->reason = ucfirst($request->get('reason'));
                            $transaction->save();
    
                            // Log the action
                            $details = TransactionDetailModel::where('transaction_id', $transaction->id)->first();
                            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                            $user_id = null;
                            $activity = "Updated the remarks of Transaction No." . $transaction->transaction_number . " Into " . $transaction->remark . " Due to " . $transaction->reason . ".";
                            (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);
    
                            // Create admin notification
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
    
                            // Create user notification
                            $user = new UserNotificationModel();
                            $user->user_id = $transaction->user_id;
                            $user->control_number = $this->generateUserNotificationNumber();
                            $user->status = "Denied";
                            $message = "Your request with transaction number " . 
                            $transaction->transaction_number . 
                            " has been denied due to ". $transaction->reason . ", and this transaction has been marked as Disapproved.";
                            $user->message = $message;
                            $user->save();
    
                            // Final response
                            if($transaction){
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
    
                    default:
                        // Handle unsupported status value
                        return response()->json([
                            'message' => "Check your internet connection!",
                            'status' => 400
                        ]);
                }
            }
        }
    }    
    // Function to generate a unique control number for notifications based on the current year and month
    private function generateNotificationNumber(){
        // Get current year and month in 'YYYY-MM' format
        $currentYearAndMonth = Carbon::now()->format('Y-m'); 

        // Retrieve the latest control number for the current year and month from NotificationModel
        $lastControlNumber = NotificationModel::whereYear('created_at', Carbon::now()->year)
                                            ->whereMonth('created_at', Carbon::now()->month)
                                            ->orderBy('control_number', 'desc')
                                            ->pluck('control_number')
                                            ->first();

        // If no control number exists for the current month, return the first control number
        if (!$lastControlNumber) {
            return $currentYearAndMonth . '-00001';
        }

        // Extract the last five digits of the latest control number
        $lastFiveDigits = substr($lastControlNumber, -5);

        // Convert to integer and increment by 1
        $incrementedNumber = intval($lastFiveDigits) + 1;

        // Pad the number with leading zeros to maintain 5-digit format
        $paddedNumber = str_pad($incrementedNumber, 5, '0', STR_PAD_LEFT);

        // Return the new control number in the same format
        return $currentYearAndMonth . '-' . $paddedNumber;
    }

    // Function to generate a unique control number for user notifications based on the current year and month
    private function generateUserNotificationNumber(){
        // Get current year and month in 'YYYY-MM' format
        $currentYearAndMonth = Carbon::now()->format('Y-m'); 

        // Retrieve the latest control number for the current year and month from UserNotificationModel
        $lastControlNumber = UserNotificationModel::whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->month)
                                                ->orderBy('control_number', 'desc')
                                                ->pluck('control_number')
                                                ->first();

        // If no control number exists for the current month, return the first control number
        if (!$lastControlNumber) {
            return $currentYearAndMonth . '-00001';
        }

        // Extract the last five digits of the latest control number
        $lastFiveDigits = substr($lastControlNumber, -5);

        // Convert to integer and increment by 1
        $incrementedNumber = intval($lastFiveDigits) + 1;

        // Pad the number with leading zeros to maintain 5-digit format
        $paddedNumber = str_pad($incrementedNumber, 5, '0', STR_PAD_LEFT);

        // Return the new control number in the same format
        return $currentYearAndMonth . '-' . $paddedNumber;
    }
    public function changeUserStatus(Request $request){
        // Validate incoming request to ensure all required fields are provided
        $validator = Validator::make($request->all(), [
            'user_id' => 'required', // Ensure user_id is provided
            'status' => 'required'    // Ensure status is provided (Active or Inactive)
        ]);
    
        // If validation fails, return a 400 error with the validation errors
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors() // Return validation errors
            ]);
        } else {
            // Find the client by user_id
            $client = ClientModel::where('id' , $request->get('user_id'))->first();
            
            // If client not found, return a 404 error
            if(!$client){
                return response()->json([
                    'message' => "Check your id", // Inform the user that the id was not found
                    'status' => 404
                ]);
            } else {
                // Get the status value from the request
                $sendData = $request->get('status');
    
                // Perform actions based on the provided status value (Active or Inactive)
                switch ($sendData) {
                    case 'Inactive':
                        // Set the client's status to Inactive
                        $client->status = "Inactive";
                        $client->save(); // Save the updated client
    
                        // Log the activity if the status was successfully updated
                        if($client){
                            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                            $user_id = null; // User ID is null because the action is performed by an admin
                            $activity = "Updated the status of " .  $client->full_name . " into  " . $client->status . "."; // Activity log text
                            (new TrailManager)->createUserTrail($user_id, $admin_id, $activity); // Log the action
    
                            // Return a success response
                            return response()->json([
                                'status' => 200,
                                'message' => "Change user status successful!" // Success message
                            ]);
                        } else {
                            // If status update failed, return a 500 error
                            return response()->json([
                                'status' => 500,
                                'message' => "Check your internet connection!" // Inform the user to check the connection
                            ]);
                        }
                        break;
                    
                    case 'Active':
                        // Set the client's status to Active
                        $client->status = "Active";
                        $client->save(); // Save the updated client
    
                        // Log the activity if the status was successfully updated
                        if($client){
                            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                            $user_id = null; // User ID is null because the action is performed by an admin
                            $activity = "Updated the status of " .  $client->full_name . " into  " . $client->status . "."; // Activity log text
                            (new TrailManager)->createUserTrail($user_id, $admin_id, $activity); // Log the action
    
                            // Return a success response
                            return response()->json([
                                'status' => 200,
                                'message' => "Change user status successful!" // Success message
                            ]);
                        } else {
                            // If status update failed, return a 500 error
                            return response()->json([
                                'status' => 500,
                                'message' => "Check your internet connection!" // Inform the user to check the connection
                            ]);
                        }
                        break;
    
                    default:
                        // Return a 500 error for unexpected status values
                        return response()->json([
                            'status' => 500,
                            'message' => "Check your internet connection!" // Inform the user to check the connection
                        ]);
                }
            }
        }
    }    
}
