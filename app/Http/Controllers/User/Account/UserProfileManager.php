<?php

namespace App\Http\Controllers\User\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientModel;

class UserProfileManager extends Controller
{
    public function goToProfile(){
        $client_id = session()->get('loginCheckUser')['id'];
        $client = ClientModel::where('id', $client_id)->first();

        return view('user.profile', [
            'client' => $client
        ]);
    }
}
