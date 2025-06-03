<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\ClientModel;
use App\Models\AdminModel;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller {
    // Function for login of the users
    public function login(Request $request){
        // Validate all input first
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:6|max:50',
            'password' => 'required|min:6|max:30',
        ]);
        // Send error to the user
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->getMessageBag(),
                'status' => 400
            ]);
        }else{
            // If success
            $username = $request->input('username');
            $client = ClientModel::where('username', $username)->first();
            // Invalid credentials
            if (!$client) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Invalid username or password!'
                ]);
            }
            // Inactive Status
            if ($client->status == 'Inactive') {
                return response()->json([
                    'status' => 423,
                    'message' => 'Account Locked!'
                ]);
            }
            // If success
            if (Hash::check($request->password, $client->password)) {
                // Declare all roles
                $roles = [
                    1 => 'loggedInInventoryAdmin',
                    2 => 'loggedInCheckerAdmin',
                    3 => 'loggedInHeadAdmin',
                    4 => 'loginCheckUser',
                ];
                // Get and restore keys
                $roleKey = $client->role_id;
                $sessionKey = $roles[$roleKey] ?? null;
                // Store data on session
                $request->session()->put($sessionKey, [
                    'id' => $client->id,
                    'full_name' => $client->full_name,
                    'email' => $client->email,
                    'username' => $client->username,
                    'role' => $client->role->name,
                    'division' => $client->division,
                    'position' => $client->position,
                ]);
                // Declare an array with key
                $roleIds = [
                    1 => 'InventoryAdmin',
                    2 => 'CheckerAdmin',
                    3 => 'HeadAdmin',
                    4 => 'User'
                ];
                // If client is a user
                if($client->role->name === "User"){
                    // Store activity log
                    $admin_id = null;
                    $user_id = session()->get('loginCheckUser')['id'];
                    $activity = "Logged in into the system.";
                    (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);
                }
                // Successful response
                return response()->json([
                    'roleId' => $roleKey,
                    'roleName' => $roleIds[$roleKey] ?? 'Unknown',
                    'message' => 'Welcome',
                    'status' => 200,
                    'username' => $username,
                ]);
            }else{
                // Error response
                return response()->json([
                    'status' => 500,
                    'message' => 'Internal Server Error!'
                ]);
            }
        }
    }
    public function setAdminSession(Request $request){
        // Get the client ID from the session
        $sessionKey = 'loggedInInventoryAdmin';
        $sessionData = $request->session()->get($sessionKey, []);

        // If session has no data
        if (empty($sessionData)) {
            return response()->json(['error' => 'Client session not found'], 404);
        }

        // Retrieve the client ID from the session
        $clientId = $sessionData['id'];

        // Retrieve the admin associated with the client_id
        $admin = AdminModel::where('client_id', $clientId)->first();

        if (!$admin) {
            return response()->json(['error' => 'Admin not found for this client'], 404);
        }

        // Merge admin details into the existing session data
        $sessionData = array_merge($sessionData, [
            'admin_id' => $admin->id,
            'admin_full_name' => $admin->full_name,
            'admin_position' => $admin->position,
        ]);

        // Update the session with the merged data
        $request->session()->put($sessionKey, $sessionData);

        // Log the activity
        $admin_id = $admin->id;
        $user_id = null;  
        $activity = "Logged in into the system.";
        (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);
        // Successful response
        return response()->json(['message' => 'Admin session updated successfully']);
    }
    public function getAvailableAdmins(){
        // Retrieve session
        $sessionKey = 'loggedInInventoryAdmin';
        $sessionID = session()->get($sessionKey)['id'];
        // If the query is match
        $admins = AdminModel::select('id', 'full_name')
        ->where('client_id', $sessionID)
        ->get();
        // Successful response
        return response()->json($admins);
    }
}
