<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\ClientModel;
use Illuminate\Support\Facades\Http;
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
}
