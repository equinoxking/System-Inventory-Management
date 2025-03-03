<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class User_mainController extends Controller
{
    public function goToDashboard()
    {
        return view('user.dashboard');
    }

    public function goToRequest()
    {
        return view('user.request');
    }

    public function goToHistory()
    {
        return view('user.history');
    }

    public function goToProfile()
    {
        return view('user.profile');
    }
}
