<?php

namespace App\Http\Controllers\Access;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\ClientModel;
use App\Models\RoleModel;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;

class RegisterController extends Controller{
    // Function for registration of the users
    public function registration(Request $request){
        //validate input first
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|max:60',
            'office' => 'required',
            'username' => 'required|min:6|max:16',
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
        // Send error to the user
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }else{
            // Determine role based on number of existing users
            $roleInventoryAdmin = RoleModel::where('name', 'InventoryAdmin')->first();
            $roleUser = RoleModel::where('name', 'User')->first();
            $userCount = ClientModel::count();
            $role = $userCount == 0 ? $roleInventoryAdmin->id : $roleUser->id;

            // Create the user
            $client = new ClientModel();
            $client->full_name = strtolower($request->get('fullName'));
            $client->office = $request->get('office');
            $client->position = $request->get('position');
            $client->email = $request->get('email');
            $client->username = $request->get('username');
            $client->password = Hash::make($request->get('password')); 
            $client->status = "Active";
            $client->employee_number = $request->get('employee_number');
            $client->role_id = $role;
            $client->save();

            if($client){
                // Log activity
                $admin_id = null;
                $user_id = $client->id;
                $activity = "Registered into the system.";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                // Successful response
                return response()->json([
                    'message' => 'Registration successful!',
                    'status' => 200,
                    'client' => $client
                ]);
            }else{
                // Error response
                return response()->json([
                    'message' => 'Check your internet connection!',
                    'status' => 500,
                    'client' => $client
                ]);
            }
        }
    }
}
