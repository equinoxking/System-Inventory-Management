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
        $clients = ClientModel::all();
        $roles = RoleModel::all();
        return view ('admin.accounts.account', [
            'clients' => $clients,
            'roles' => $roles
        ]);
    }
    public function setUserRole(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'full_name'=> 'required',
            'role_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $client = ClientModel::where('id' , $request->get('user_id'))->first();
            if(!$client){
                return response()->json([
                    'message' => "Check your id",
                    'status' => 404
                ]);
            }else{
                $client = ClientModel::where('id' , $request->get('user_id'))->first();
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
    public function changeUserStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'full_name'=> 'required',
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $client = ClientModel::where('id' , $request->get('user_id'))->first();
            if(!$client){
                return response()->json([
                    'message' => "Check your id",
                    'status' => 404
                ]);
            }else{
                $sendData = $request->get('status');
                switch ($sendData) {
                case 'Inactive' :

                    $client = ClientModel::where('id' , $request->get('user_id'))->first();
                    $client->status = "Inactive";
                    $client->save();

                    if($client){
                        $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                        $user_id = null;
                        $activity = "Updated the status of " .  $client->full_name . " into  " . $client->status . ".";
                        (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                        return response()->json([
                            'status' => 200,
                            'message' => "Change user status successful!"
                        ]);
                    }else{
                        return response()->json([
                            'status' => 500,
                            'message' => "Check your internet connection!"
                        ]);
                    }
                break;
                case 'Active' : 

                    $client = ClientModel::where('id' , $request->get('user_id'))->first();
                    $client->status = "Active";
                    $client->save();

                    if($client){
                        $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                        $user_id = null;
                        $activity = "Updated the status of " .  $client->full_name . " into  " . $client->status . ".";
                        (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);
                        return response()->json([
                            'status' => 200,
                            'message' => "Change user status successful!"
                        ]);
                    }else{
                        return response()->json([
                            'status' => 500,
                            'message' => "Check your internet connection!"
                        ]);
                    }
                default : 
                    return response()->json([
                        'status' => 500,
                        'message' => "Check your internet connection!"
                    ]);
                }
            }
        }
    }
}
