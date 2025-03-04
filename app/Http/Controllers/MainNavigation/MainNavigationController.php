<?php

namespace App\Http\Controllers\MainNavigation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainNavigationController extends Controller
{
    public function goToIndex(){
        if(session()->has('loggedInInventoryAdmin') || session()->has('loggedInCheckerAdmin') || session()->has('loggedInHeadAdmin') || session()->has('loggedInUser')){
            return back();
        }else{
            return view('index');
        }
    }
}
