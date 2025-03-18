<?php

namespace App\Http\Controllers\Inventory_Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IA_functionController extends Controller
{
    public function logoutAdmin(Request $request){
        if(session()->has('loggedInInventoryAdmin')){
            session()->pull('loggedInInventoryAdmin');
            return redirect('/');
        }
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }
    
    }
}
