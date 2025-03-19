<?php

namespace App\Http\Controllers\Inventory_Admin\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientModel;
class AccountManager extends Controller
{
    public function goToAccounts(){
        $clients = ClientModel::all();
        return view ('admin.accounts.account', [
            'clients' => $clients
        ]);
    }
}
