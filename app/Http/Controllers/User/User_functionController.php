<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class User_functionController extends Controller
{
    public function logoutUser(Request $request){
        if(session()->has('loginCheckUser')){
            session()->pull('loginCheckUser');
            return redirect('/');
        }
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }
    
    }
}
