<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserNotificationModel;

use Illuminate\Http\Request;

class User_mainController extends Controller
{
    public function goToDashboard()
    {
        $notifications = UserNotificationModel::with(['client', 'admin'])->get();
        return view('user.index' , compact('notifications'));
    }

    public function goToHistory()
    {
        return view('user.voids');
    }

    public function goToProfile()
    {
        return view('user.profile');
    }
}
