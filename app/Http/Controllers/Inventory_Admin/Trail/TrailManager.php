<?php

namespace App\Http\Controllers\Inventory_Admin\Trail;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\ClientModel;
use Illuminate\Http\Request;
use App\Models\TrailModel;
use Illuminate\Support\Carbon;
use App\Models\TransactionModel;
class TrailManager extends Controller
{
    public function createUserTrail($user_id, $admin_id, $activity){
        $activity_timestamp = Carbon::now('Asia/Manila');
        $activity_timestamp_philippines = $activity_timestamp->toDateTimeString();
        $save = TrailModel::insert([
            'user_id' => $user_id,
            'admin_id' => $admin_id,
            'activity' => $activity,
            'created_at' => $activity_timestamp_philippines,
            'updated_at' => null
        ]);
    }
    public function goToTrails(){
        $trails = TrailModel::with(['client', 'admin'])->get();
        $admins = AdminModel::all();
        $transactionUsers = TransactionModel::with([
            'transactionDetail',
            'client',
            'item',
            'item.inventory.unit',
            'status',
            'adminBy',
            'admin'
        ])
        ->where(function ($query) {
            $query->where('remark', 'Completed');
        })
        ->get();  
        $clients = ClientModel::all();
        return view('admin.trails/user', compact('trails', 'admins', 'transactionUsers', 'clients'));
    }
     public function goToTrailsAdmin(){
        $trails = TrailModel::with(['client', 'admin'])->get();
        $admins = AdminModel::all();
        $transactionUsers = TransactionModel::with([
            'transactionDetail',
            'client',
            'item',
            'item.inventory.unit',
            'status',
            'adminBy',
            'admin'
        ])
        ->where(function ($query) {
            $query->where('remark', 'Completed');
        })
        ->get();  
        $clients = ClientModel::all();
        return view('admin.trails/admin', compact('trails', 'admins', 'transactionUsers', 'clients'));
    }
}
