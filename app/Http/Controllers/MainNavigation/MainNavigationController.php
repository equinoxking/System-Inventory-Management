<?php

namespace App\Http\Controllers\MainNavigation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientModel;
class MainNavigationController extends Controller
{
    public function goToIndex(){
        if(session()->has('loggedInInventoryAdmin') || session()->has('loggedInUser')){
            return back();
        }else{
            $users = ClientModel::count();
            return view('index', ['users' => $users]);
        }
    }
}
