<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\ClientModel;
class LoginController extends Controller
{
    public function loginUser(Request $request){
        $validator = Validator::make($request->all(), [
            'username' =>'required|string|max:30',
            'password' => 'required|min:6|max:50',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->getMessageBag(),
                'status' => 400
            ]);
        }else{
            $username = $request->input('username');
            $client = ClientModel::where('username', $username)->first();
            if(!$client){
                return response()->json([
                    'status' => 404,
                    'message' => "Invalid username or password!"
                ]);
            }
            if($client->status == 'Inactive'){
                return response()->json([
                    'status' => 423,
                    'message' => "Account Locked!"
                ]);
            }
            if($client){
                if(Hash::check($request->password, $client->password)){
                    $roles = [
                        'InventoryAdmin' => 'loggedInInventoryAdmin',
                        'CheckerAdmin' => 'loggedInCheckerAdmin',
                        'HeadAdmin' => 'loggedInHeadAdmin',
                        'User' => 'loggedInUser',
                    ];
                
                    $roleKey = $client->role;
                    $sessionKey = $roles[$roleKey] ?? null;

                    $request->session()->put($sessionKey, [
                        'id' => $client->id,
                        'firstName' => $client->first_name,
                        'lastName' => $client->last_name,
                        'email' => $client->email,
                        'username' => $client->username,
                        'role' => $client->role,
                        'division' => $client->division,
                    ]);
    
                    $roleIds = [
                        'InventoryAdmin' => 1,
                        'CheckerAdmin' => 2,
                        'HeadAdmin' => 3,
                        'User' => 4
                    ];
                
                    return response()->json([
                        'roleId' => $roleIds[$roleKey],
                        'message' => "Welcome",
                        'status' => 200
                    ]);
                }else{
                    return response()->json([
                        'status' => 404, 
                        'message' => "Invalid username or password!"
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 404, 
                    'message' => "Invalid username or password!"
                ]);
            }
        }
        return response()->json([
            'status' => 500, 
            'message' => "Internal Server Error!"
        ]);
    }
}
