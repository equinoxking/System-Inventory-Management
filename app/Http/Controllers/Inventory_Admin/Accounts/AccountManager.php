<?php

namespace App\Http\Controllers\Inventory_Admin\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientModel;
use App\Models\RoleModel;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;


class AccountManager extends Controller
{
    public function goToAccounts(){
        // Retrieve all clients from the ClientModel
        $clients = ClientModel::all();
        
        // Retrieve all roles from the RoleModel
        $roles = RoleModel::all();
        
        // Return the view for 'admin.accounts.account' with the clients and roles data
        return view('admin.accounts.account', [
            'clients' => $clients, // Pass the clients to the view
            'roles' => $roles      // Pass the roles to the view
        ]);
    }    
    public function setUserRole(Request $request){
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
            $client = ClientModel::where('id' , $request->get('user_id'))->first();
            
            // If client not found, return a 404 error
            if(!$client){
                return response()->json([
                    'message' => "Check your id", // Inform the user that the id was not found
                    'status' => 404
                ]);
            } else {
                // Update the client's role_id
                $client->role_id = $request->get('role_id');
                $client->save(); // Save the updated client details
                
                // If the client role was successfully updated
                if($client){
                    // Get the logged-in admin id from the session
                    $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                    $user_id = null; // User ID is null because the action is performed by an admin
                    $activity = "Set a role of " .  $client->full_name . " into " . $client->role->name . "."; // Activity log text
                    (new TrailManager)->createUserTrail($user_id, $admin_id, $activity); // Log the action
                    
                    // Return a success response
                    return response()->json([
                        'status' => 200,
                        'message' => "Set role successful!" // Success message
                    ]);
                } else {
                    // If role update failed, return a 500 error
                    return response()->json([
                        'status' => 500,
                        'message' => "Check your internet connection!" // Inform the user to check the connection
                    ]);
                }
            }
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
