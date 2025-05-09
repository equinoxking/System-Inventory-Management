<?php

namespace App\Http\Controllers\Inventory_Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;
use App\Models\AdminModel;

class IA_functionController extends Controller
{
    public function logoutAdmin(Request $request){
        if(session()->has('loggedInInventoryAdmin')){
            $admin = AdminModel::where('id', session()->get('loggedInInventoryAdmin')['admin_id'])->first();
            $user_id = null;
            $activity = "Logged out into the system.";
            (new TrailManager)->createUserTrail($user_id, $admin->id, $activity);

            session()->pull('loggedInInventoryAdmin');
            return redirect('/');
        }
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }
        
    }
}
