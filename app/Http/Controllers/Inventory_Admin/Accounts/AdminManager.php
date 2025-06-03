<?php

namespace App\Http\Controllers\Inventory_Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;
use App\Models\ClientModel;

class AdminManager extends Controller
{
    public function addAdmin(Request $request){
        // Validate the request input
        $validator = Validator::make($request->all(), [
            'admin_full_name' => [
                'required',
                'unique:admins,full_name',
            ],
            'admin_position' => 'required',
            'system_role' => 'required|exists:roles,id',
            'client_full_name' => 'required|exists:clients,id',
        ]);
    
        // Return validation errors if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }
    
        try {
            // Create and save the new admin
            $admin = new AdminModel();
            $admin->client_id = $request->get('client_full_name');
            $admin->role_id = $request->get('system_role');
            $admin->control_number = $this->generateControlNumber();
            $admin->full_name = $request->get('admin_full_name');
            $admin->position = $request->get('admin_position');
            $admin->status = "Active";
            $admin->save();
    
            // Log the activity (optional enhancement)
            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'] ?? null;
            $activity = "Added new admin: " . $admin->full_name;
            (new TrailManager)->createUserTrail(null, $admin_id, $activity);
    
            // Return success response with created admin data
            return response()->json([
                'message' => 'Admin added successfully!',
                'status' => 200,
                'admin' => $admin
            ]);
        } catch (\Exception $e) {
            // Catch and return server error response
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 500
            ]);
        }
    }    
    public function updateAdmin(Request $request){
        // Validate the request inputs
        $validator = Validator::make($request->all(), [
            'admin_id' => 'required|exists:admins,id',
            'admin_control_number' => 'required|exists:admins,control_number',
            'admin_full_name' => [
                'required',
                'regex:/^[a-zA-Z\s\-]+$/', // Optional: Name should not include numbers or special characters
                Rule::unique('admins', 'full_name')->ignore($request->get('admin_id')),
            ],
            'admin_position' => 'required',
            'admin_status' => 'required'
        ]);
    
        // Return validation error response if needed
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }
    
        try {
            // Retrieve and update admin record
            $admin = AdminModel::findOrFail($request->get('admin_id'));
            $admin->full_name = $request->get('admin_full_name');
            $admin->position = $request->get('admin_position');
            $admin->status = $request->get('admin_status');
            $admin->save();
    
            // Optional: Log update activity
            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'] ?? null;
            $activity = "Updated admin: " . $admin->full_name;
            (new TrailManager)->createUserTrail(null, $admin_id, $activity);
    
            // Return success response
            return response()->json([
                'message' => 'Admin updated successfully!',
                'status' => 200,
                'admin' => $admin
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions that occur
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 500
            ]);
        }
    }    
    public function deleteAdmin(Request $request){
        // Validate the incoming request
        $validator = Validator::make($request->all(), [ 
            'admin_id' => 'required|exists:admins,id', // Ensure admin ID exists in the database
        ]);
    
        // Return validation error response if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }
    
        try {
            // Attempt to delete the admin record
            $adminData = AdminModel::where('id', $request->get('admin_id'))->first();
            $admin = AdminModel::where('id', $request->get('admin_id'))->delete();
            $client = ClientModel::where('id', $adminData->client_id)->update([
                'role_id' => 4
            ]);
            // Check if the deletion was successful
            if($admin && $client){
                // Log deletion activity
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'] ?? null;
                $activity = "Deleted admin role of " . $adminData->full_name . ".";
                (new TrailManager)->createUserTrail(null, $admin_id, $activity);
    
                // Return success message
                return response()->json([
                    'message' => 'Admin deleted successfully!',
                    'status' => 200
                ]);
            } else {
                // Return failure message if deletion fails
                return response()->json([
                    'message' => 'Unable to delete admin. Check your internet connection.',
                    'status' => 500
                ]);
            }
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 500
            ]);
        }
    }    
    private function generateControlNumber() {
        // Get the current year and month in 'YYYY-MM' format
        $currentYearAndMonth = Carbon::now()->format('Y-m');
    
        try {
            // Fetch the latest control number for the current year and month
            $controlNumber = AdminModel::whereYear('created_at', Carbon::now()->year)
                                        ->whereMonth('created_at', Carbon::now()->month)
                                        ->orderBy('control_number', 'desc')
                                        ->pluck('control_number')
                                        ->first();
    
            // If no control number exists for this month, start with '00001'
            if (!$controlNumber) {
                return $currentYearAndMonth . '-00001';
            }
    
            // Increment the last control number, and pad the numeric part with leading zeros
            $numberPart = intval(substr($controlNumber, -5)) + 1;
            $paddedNumber = str_pad($numberPart, 5, '0', STR_PAD_LEFT);
    
            // Return the new control number with the year and month prefix
            return $currentYearAndMonth . '-' . $paddedNumber;
    
        } catch (\Exception $e) {
            // If an error occurs (e.g., database issue), return the default control number
            return $currentYearAndMonth . '-00001';  // Default in case of error
        }
    }
    public function changeUserStatus(Request $request){
        // Validate incoming request to ensure all required fields are provided
        $validator = Validator::make($request->all(), [
            'user_id' => 'required', // Ensure user_id is provided
            'full_name'=> 'required', // Ensure full_name is provided
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
            $admin = AdminModel::where('id' , $request->get('user_id'))->first();
            
            // If client not found, return a 404 error
            if(!$admin){
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
                        $admin->status = "Inactive";
                        $admin->save(); // Save the updated client
    
                        // Log the activity if the status was successfully updated
                        if($admin){
                            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                            $user_id = null; // User ID is null because the action is performed by an admin
                            $activity = "Updated the status of " .  $admin->full_name . " into  " . $admin->status . "."; // Activity log text
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
                        $admin->status = "Active";
                        $admin->save(); // Save the updated client
    
                        // Log the activity if the status was successfully updated
                        if($admin){
                            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                            $user_id = null; // User ID is null because the action is performed by an admin
                            $activity = "Updated the status of " .  $admin->full_name . " into  " . $admin->status . "."; // Activity log text
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
    public function setAdminRole(Request $request){
        // Validate incoming request to ensure all required fields are provided
        $validator = Validator::make($request->all(), [
            'user_id' => 'required', // Ensure user_id is provided
            'full_name'=> 'required', // Ensure full_name is provided
            'role_id' => 'required'   // Ensure role_id is provided
        ]);
    
        // If validation fails, return a 400 error with the validation errors
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            // Find the client by user_id
            $client = ClientModel::where('id', $request->get('user_id'))->first();
            $clientOldRole = $client->role->name;
            $oldRoleLabel = $clientOldRole === 'InventoryAdmin' ? 'Admin' : $clientOldRole;
            if (!$client) {
                return response()->json([
                    'status' => 404,
                    'message' => "Check your id"
                ]);
            }

            // Update the client's role_id
            $client->role_id = $request->get('role_id');
            $client->save();

            $client->load('role');
            $clientNewRole = $client->role ? $client->role->name : 'None';
            $newRoleLabel = $clientNewRole === 'InventoryAdmin' ? 'Admin' : $clientNewRole;
            $admin = new AdminModel();
            $admin->client_id = $client->id;
            $admin->role_id = $client->role_id;
            $admin->full_name = $client->full_name;
            $admin->status = "Active";
            $admin->control_number = $client->employee_number;
            $admin->position = $client->position;
            $admin->save();

            if ($client && $admin) {
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null;
                $activity = "Set a role of " . $client->full_name . " from " . $oldRoleLabel . " to " . $newRoleLabel . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                return response()->json([
                    'status' => 200,
                    'message' => "Set role successful!"
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Check your internet connection!"
                ]);
            }

        }
    }        
}
