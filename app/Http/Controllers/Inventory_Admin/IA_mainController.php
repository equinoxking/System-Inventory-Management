<?php

namespace App\Http\Controllers\Inventory_Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IA_mainController extends Controller
{
    public function goToDashboard(){
        return view('admin.index');
    }
    public function goToItems(){
        return view('admin.items');
    }
    public function goToTransactions(){
        return view('admin.transaction');
    }
    public function goToRequest(){
        return view('admin.request');
    }
    public function goToReport(){
        return view('admin.report');
    }
    public function goToAccounts(){
        return view ('admin.account');
    }
    public function goToAudits(){
        return view ('admin.audit');
    }
    public function goToProfile(){
        return view ('admin.profile');
    }
}
