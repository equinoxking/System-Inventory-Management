<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\RoleModel;
class RegisterController extends Controller
{
    public function registration (Request $request){
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|max:60',
            'office' => 'required',
            'username' => 'required',
            'position' => 'required',
            'email' => [
                'required',
                'email',
                'max:100',
                'unique:clients,email',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/',
            ],
            'password' => 'required|min:6|max:30',
            're-password' => 'required|same:password'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $response = Http::post('http://authentication.local/api/register', [
                'username' => $request->username,
                'password' => $request->password,
            ]);
        
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['token'])) {
                    $token = $data['token'];
                    $roleInventoryAdmin = RoleModel::where('name', 'InventoryAdmin')->first();
                    $roleUser = RoleModel::where('name', 'User')->first();
                    $userCount = ClientModel::count();
                    $role = $userCount == 0 ? $roleInventoryAdmin->id : $roleUser->id;
                    
                    $client = new ClientModel();
                    $client->full_name = strtolower($request->get('fullName'));
                    $client->office = $request->get('office');
                    $client->position = $request->get('position');
                    $client->email = $request->get('email');
                    $client->username = $request->get('username');
                    $client->password = Hash::make($request->get('password')); 
                    $client->status = "Active";
                    $client->role_id = $role;
                    $client->save();
        
                    return response()->json([
                        'message' => 'Registration successful!',
                        'status' => 200,
                        'client' => $client,
                        'token' => $token
                    ]);
                } else {

                    return response()->json([
                        'message' => 'Incorrect Credentials.',
                        'status' => 400
                    ]);
                }
            } else {
                return response()->json([
                    'message' => 'Failed to authenticate.',
                    'status' => 400
                ]);
            }
            return response()->json([
                'message' => 'Registration Error!',
                'status' => 500
            ]);
        }
    }
}
