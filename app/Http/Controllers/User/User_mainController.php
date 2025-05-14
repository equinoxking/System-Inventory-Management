<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserNotificationModel;

use Illuminate\Http\Request;

class User_mainController extends Controller
{
    public function goToDashboard()
    {
        $client_id = session()->get('loginCheckUser')['id'];
        $notifications = UserNotificationModel::with(['client', 'admin'])->where('user_id', $client_id)->get();
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
