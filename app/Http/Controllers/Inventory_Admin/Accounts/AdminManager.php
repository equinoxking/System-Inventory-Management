<?php

namespace App\Http\Controllers\Inventory_Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class AdminManager extends Controller
{
    public function addAdmin(Request $request){
        $validator = Validator::make($request->all(), [
            'admin_full_name' => 'required|unique:admins,full_name',
            'admin_position' => 'required',
            'system_role' => 'required|exists:roles,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $admin = new AdminModel();
            $admin->role_id = $request->get('system_role');
            $admin->control_number = $this->generateControlNumber();
            $admin->full_name = $request->get('admin_full_name');
            $admin->position = $request->get('admin_position');
            $admin->status = "Active";
            $admin->save();

            if($admin){
                return response()->json([
                    'message' => 'Admin added successful!',
                    'status' => 200
                ]);
            }else{
                return response()->json([
                    'message' => 'Check your internet connection!',
                    'status' => 500
                ]);
            }
        }
    }
    public function updateAdmin(Request $request){
        $validator = Validator::make($request->all(), [
            'admin_id' => 'required|exists:admins,id',
            'admin_control_number' => 'required|exists:admins,control_number',
            'admin_full_name' => [
                'required',
                Rule::unique('admins', 'full_name')->ignore($request->get('admin_id')),
            ],
            'admin_position' => 'required',
            'admin_status' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $admin = AdminModel::findOrFail($request->get('admin_id'));
            $admin->full_name = $request->get('admin_full_name');
            $admin->position = $request->get('admin_position');
            $admin->status = $request->get('admin_status');
            $admin->save();

            if($admin){
                return response()->json([
                    'message' => 'Admin updated successful!',
                    'status' => 200
                ]);
            }else{
                return response()->json([
                    'message' => 'Check your internet connection!',
                    'status' => 500
                ]);
            }
        }
    }
    public function deleteAdmin(Request $request){
        $validator = Validator::make($request->all(), [ 
            'admin_id' => 'required|exists:admins,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $admin = AdminModel::where('id', $request->get('admin_id'))->delete();
            if($admin){
                return response()->json([
                    'message' => 'Admin deleted successful!',
                    'status' => 200
                ]);
            }else{
                return response()->json([
                    'message' => 'Check your internet connection!',
                    'status' => 500
                ]);
            }
        }
    }
    private function generateControlNumber() {
        $currentYearAndMonth = Carbon::now()->format('Y-m');
        $controlNumber = AdminModel::whereYear('created_at', Carbon::now()->year)
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->orderBy('control_number', 'desc')
                                ->pluck('control_number')
                                ->first();
    
        if (!$controlNumber) {
            return $currentYearAndMonth . '-00001';
        }
    
        $numberPart = intval(substr($controlNumber, -5)) + 1; 
        $paddedNumber = str_pad($numberPart, 5, '0', STR_PAD_LEFT);
    
        return $currentYearAndMonth . '-' . $paddedNumber;
    }  
}
