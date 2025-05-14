<?php

namespace App\Http\Controllers\Inventory_Admin\Items;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;
use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SupplierManager extends Controller
{
    public function addSupplier(Request $request){
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'supplier_name' => 'required|unique:suppliers,name', // Unit name must be unique
        ]);
        // Handle validation errors
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            // Create and populate the new unit record
            $supplier = new SupplierModel();
            $supplier->name = ucwords($request->get('supplier_name'));
            $supplier->control_number = $this->generateControlNumber(); // Generate unique control number
            $supplier->save();

            if($supplier){
                // Audit trail
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null;
                $activity = "Added a new unit: " . $supplier->name . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                return response()->json([
                    'message' => 'Supplier successfully added!',
                    'status' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Check your internet connection!',
                    'status' => 500
                ]);
            }
        }
    }
     public function updateSupplier(Request $request){
        // Validate input data
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id', // Ensure unit ID exists
            'supplier_name' => [
                'required',
                Rule::unique('suppliers', 'name')->ignore($request->get('supplier_id')), // Ensure name is unique, except for current unit
            ],
        ]);
        
        // Handle validation failure
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            // Fetch the supplier and update its attributes
            $supplier = SupplierModel::findOrFail($request->get('supplier_id'));
            $supplier->name = $request->get('supplier_name');
            $supplier->save();

            if($supplier){
                // Audit trail logging
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null;
                $activity = "Updated a supplier: " . $supplier->name . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                return response()->json([
                    'message' => 'Supplier successfully updated!',
                    'status' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Check your internet connection!',
                    'status' => 500
                ]);
            }
        }
    }
    public function deleteSupplier(Request $request){
        // Validate that the unit ID is present and exists
        $validator = Validator::make($request->all(), [ 
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            // Find and delete the unit
            $supplier = SupplierModel::find($request->get('supplier_id'));
            if($supplier){
                $supplierName = $supplier->name;
                $supplier->delete();

            // Audit trail logging
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null;
                $activity = "Deleted a supplier: " . $supplierName . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                return response()->json([
                    'message' => 'supplier successfully deleted!',
                    'status' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Check your internet connection!',
                    'status' => 500
                ]);
            }
        }
    } 
    private function generateControlNumber() {
        $currentYearAndMonth = Carbon::now()->format('Y-m'); // Get current year and month

        // Fetch the latest control number for the current month
        $controlNumber = SupplierModel::whereYear('created_at', Carbon::now()->year)
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->orderBy('control_number', 'desc')
                                ->pluck('control_number')
                                ->first();

        // If none found, start from 00001
        if (!$controlNumber) {
            return $currentYearAndMonth . '-00001';
        }

        // Extract the numeric part, increment it, and format it with padding
        $numberPart = intval(substr($controlNumber, -5)) + 1; 
        $paddedNumber = str_pad($numberPart, 5, '0', STR_PAD_LEFT);

        return $currentYearAndMonth . '-' . $paddedNumber;
    }
}
