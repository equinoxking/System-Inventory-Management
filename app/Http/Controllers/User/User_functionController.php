<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class User_functionController extends Controller
{
    public function logoutUser(Request $request){
        // Handle session logout
        if (session()->has('loginCheckUser')) {
            session()->forget('loginCheckUser');
        }

        if (session()->has('loggedInInventoryAdmin')) {
            session()->forget('loggedInInventoryAdmin');
        }

        // Handle API token logout (if applicable)
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        // Final redirect
        return redirect('/');
    }
}
