<?php

namespace App\Http\Controllers\Checker_Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CA_mainController extends Controller
{
    public function goToDashboard(){
        return view ('head_admin/dashboard');
    }
    public function goToTransactions(){
        return view ('head_admin.transaction');
    }
    public function goToRequest(){
        return view ('head_admin.request');
    }
}
