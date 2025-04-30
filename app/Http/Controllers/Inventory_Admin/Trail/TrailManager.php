<?php

namespace App\Http\Controllers\Inventory_Admin\Trail;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Illuminate\Http\Request;
use App\Models\TrailModel;
use Illuminate\Support\Carbon;
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
        return view('admin.trails', compact('trails', 'admins'));
    }
}
