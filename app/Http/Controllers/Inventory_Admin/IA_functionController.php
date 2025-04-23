<?php

namespace App\Http\Controllers\Inventory_Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;
class IA_functionController extends Controller
{
    public function logoutAdmin(Request $request){
        if(session()->has('loggedInInventoryAdmin')){
            
            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
            $user_id = null;
            $activity = "Logged out into the system.";
            (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

            session()->pull('loggedInInventoryAdmin');
            return redirect('/');
        }
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }
        
    }
}
