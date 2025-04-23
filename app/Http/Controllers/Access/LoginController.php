<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\ClientModel;
use Illuminate\Support\Facades\Http;
use App\Models\AdminModel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;


class LoginController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:30',
            'password' => 'required|min:6|max:50',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->getMessageBag(),
                'status' => 400
            ]);
        }
    
        $username = $request->input('username');
        $client = ClientModel::where('username', $username)->first();
    
        if (!$client) {
            return response()->json([
                'status' => 404,
                'message' => 'Invalid username or password!'
            ]);
        }
    
        if ($client->status == 'Inactive') {
            return response()->json([
                'status' => 423,
                'message' => 'Account Locked!'
            ]);
        }
    
        $response = Http::post('http://authentication.local/api/login', [
            'username' => $request->username,
            'password' => $request->password,
        ]);
        if ($response->successful()) {
            if (Hash::check($request->password, $client->password)) {
                $roles = [
                    1 => 'loggedInInventoryAdmin',
                    2 => 'loggedInCheckerAdmin',
                    3 => 'loggedInHeadAdmin',
                    4 => 'loginCheckUser',
                ];
    
                $roleKey = $client->role_id;
                $sessionKey = $roles[$roleKey] ?? null;
                
                $request->session()->put($sessionKey, [
                    'id' => $client->id,
                    'full_name' => $client->full_name,
                    'email' => $client->email,
                    'username' => $client->username,
                    'role' => $client->role->name,
                    'division' => $client->division,
                    'position' => $client->position,
                ]);

                $roleIds = [
                    1 => 'InventoryAdmin',
                    2 => 'CheckerAdmin',
                    3 => 'HeadAdmin',
                    4 => 'User'
                ];
    
                $data = $response->json();
                $token = $data['token'] ?? null;
                $username = $data['username'] ?? null; 
                if ($token) {
                    if($client->role->name === "User"){
                        $admin_id = null;
                        $user_id = session()->get('loginCheckUser')['id'];
                        $activity = "Logged in into the system.";
                        (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);
                    }
                    $request->session()->put('token', $token); 
                    return response()->json([
                        'roleId' => $roleKey,
                        'roleName' => $roleIds[$roleKey] ?? 'Unknown',
                        'message' => 'Welcome',
                        'status' => 200,
                        'username' => $username,
                        'token' => $token, 
                    ]);
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Token not found in the authentication response.',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Invalid username or password!'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid Credentials.'
            ]);
        }
        return response()->json([
            'status' => 500,
            'message' => 'Internal Server Error!'
        ]);
    }
    public function setSelectedAdmin(Request $request)
    {
    // Validate that the selected admin exists
    $request->validate([
        'admin_id' => 'required|exists:admins,id',
    ]);

    // Retrieve the admin
    $admin = AdminModel::find($request->admin_id);

    // Define session key and get existing session data if any
    $sessionKey = 'loggedInInventoryAdmin';
    $sessionData = $request->session()->get($sessionKey, []); 

    // Merge new admin data into session
    $sessionData = array_merge($sessionData, [
        'admin_id' => $admin->id, 
        'admin_full_name' => $admin->full_name,
        'admin_position' => $admin->position
    ]);

    // Save updated session data
    $request->session()->put($sessionKey, $sessionData);

    // Prepare trail values
    $admin_id  = $admin->id;
    $user_id   = null; 
    $activity  = "Logged in into the system.";
    
    // Create trail record
    (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);
    return response()->json(['message' => 'Admin selected successfully.']);
    }

    public function getAvailableAdmins(){

    $admins = AdminModel::select('id', 'full_name')->get();

    return response()->json($admins);
    }

}
