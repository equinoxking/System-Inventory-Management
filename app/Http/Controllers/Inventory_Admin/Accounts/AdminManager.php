<?php

namespace App\Http\Controllers\Inventory_Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;

class AdminManager extends Controller
{
    public function addAdmin(Request $request){
        // Validate the request input
        $validator = Validator::make($request->all(), [
            'admin_full_name' => [
                'required',
                'unique:admins,full_name',
            ],
            'admin_position' => 'required',
            'system_role' => 'required|exists:roles,id',
            'client_full_name' => 'required|exists:clients,id',
        ]);
    
        // Return validation errors if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }
    
        try {
            // Create and save the new admin
            $admin = new AdminModel();
            $admin->client_id = $request->get('client_full_name');
            $admin->role_id = $request->get('system_role');
            $admin->control_number = $this->generateControlNumber();
            $admin->full_name = $request->get('admin_full_name');
            $admin->position = $request->get('admin_position');
            $admin->status = "Active";
            $admin->save();
    
            // Log the activity (optional enhancement)
            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'] ?? null;
            $activity = "Added new admin: " . $admin->full_name;
            (new TrailManager)->createUserTrail(null, $admin_id, $activity);
    
            // Return success response with created admin data
            return response()->json([
                'message' => 'Admin added successfully!',
                'status' => 200,
                'admin' => $admin
            ]);
        } catch (\Exception $e) {
            // Catch and return server error response
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 500
            ]);
        }
    }    
    public function updateAdmin(Request $request){
        // Validate the request inputs
        $validator = Validator::make($request->all(), [
            'admin_id' => 'required|exists:admins,id',
            'admin_control_number' => 'required|exists:admins,control_number',
            'admin_full_name' => [
                'required',
                'regex:/^[a-zA-Z\s\-]+$/', // Optional: Name should not include numbers or special characters
                Rule::unique('admins', 'full_name')->ignore($request->get('admin_id')),
            ],
            'admin_position' => 'required',
            'admin_status' => 'required'
        ]);
    
        // Return validation error response if needed
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }
    
        try {
            // Retrieve and update admin record
            $admin = AdminModel::findOrFail($request->get('admin_id'));
            $admin->full_name = $request->get('admin_full_name');
            $admin->position = $request->get('admin_position');
            $admin->status = $request->get('admin_status');
            $admin->save();
    
            // Optional: Log update activity
            $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'] ?? null;
            $activity = "Updated admin: " . $admin->full_name;
            (new TrailManager)->createUserTrail(null, $admin_id, $activity);
    
            // Return success response
            return response()->json([
                'message' => 'Admin updated successfully!',
                'status' => 200,
                'admin' => $admin
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions that occur
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 500
            ]);
        }
    }    
    public function deleteAdmin(Request $request){
        // Validate the incoming request
        $validator = Validator::make($request->all(), [ 
            'admin_id' => 'required|exists:admins,id', // Ensure admin ID exists in the database
        ]);
    
        // Return validation error response if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }
    
        try {
            // Attempt to delete the admin record
            $admin = AdminModel::where('id', $request->get('admin_id'))->delete();
    
            // Check if the deletion was successful
            if($admin){
                // Log deletion activity
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'] ?? null;
                $activity = "Deleted admin with ID: " . $request->get('admin_id');
                (new TrailManager)->createUserTrail(null, $admin_id, $activity);
    
                // Return success message
                return response()->json([
                    'message' => 'Admin deleted successfully!',
                    'status' => 200
                ]);
            } else {
                // Return failure message if deletion fails
                return response()->json([
                    'message' => 'Unable to delete admin. Check your internet connection.',
                    'status' => 500
                ]);
            }
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 500
            ]);
        }
    }    
    private function generateControlNumber() {
        // Get the current year and month in 'YYYY-MM' format
        $currentYearAndMonth = Carbon::now()->format('Y-m');
    
        try {
            // Fetch the latest control number for the current year and month
            $controlNumber = AdminModel::whereYear('created_at', Carbon::now()->year)
                                        ->whereMonth('created_at', Carbon::now()->month)
                                        ->orderBy('control_number', 'desc')
                                        ->pluck('control_number')
                                        ->first();
    
            // If no control number exists for this month, start with '00001'
            if (!$controlNumber) {
                return $currentYearAndMonth . '-00001';
            }
    
            // Increment the last control number, and pad the numeric part with leading zeros
            $numberPart = intval(substr($controlNumber, -5)) + 1;
            $paddedNumber = str_pad($numberPart, 5, '0', STR_PAD_LEFT);
    
            // Return the new control number with the year and month prefix
            return $currentYearAndMonth . '-' . $paddedNumber;
    
        } catch (\Exception $e) {
            // If an error occurs (e.g., database issue), return the default control number
            return $currentYearAndMonth . '-00001';  // Default in case of error
        }
    }    
}
