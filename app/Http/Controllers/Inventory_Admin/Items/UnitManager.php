<?php

namespace App\Http\Controllers\Inventory_Admin\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UnitModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;

class UnitManager extends Controller
{
    // This method performs a search for units based on a partial match of the unit name
    public function searchUnit(Request $request){
        // Get the 'query' input from the request (typically from a search field)
        $query = $request->input('query'); 

        // Search for units where the name contains the query string
        $units = UnitModel::where('name', 'like', '%' . $query . '%')->get();

        // Return the matched units as a JSON response
        return response()->json($units);
    }

    // This method fetches a specific unit by ID and returns it
    public function storeUnit(Request $request){
        // Retrieve the selected unit's ID from the request (parameter name may be misleading: 'category_id')
        $unitId = $request->input('category_id');

        // Find the unit in the database
        $unit = UnitModel::find($unitId);

        // Return the selected unit as a JSON response
        return response()->json([
            'message' => 'Unit selected', 
            'unit' => $unit
        ]);
    }
    // Updates an existing unit's information
    public function updateUnit(Request $request){
        // Validate input data
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required|exists:units,id', // Ensure unit ID exists
            'unit_control_number' => 'required|exists:units,control_number', // Ensure control number exists
            'unit_name' => [
                'required',
                Rule::unique('units', 'name')->ignore($request->get('unit_id')), // Ensure name is unique, except for current unit
            ],
        ]);
        
        // Handle validation failure
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            // Fetch the unit and update its attributes
            $unit = UnitModel::findOrFail($request->get('unit_id'));
            $unit->name = $request->get('unit_name');
            $unit->description = $request->get('unit_description');
            $unit->symbol = $request->get('unit_symbol');
            $unit->save();

            if($unit){
                // Audit trail logging
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null;
                $activity = "Updated a unit: " . $unit->name . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                return response()->json([
                    'message' => 'Unit successfully updated!',
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

    // Deletes a unit from the system
    public function deleteUnit(Request $request){
        // Validate that the unit ID is present and exists
        $validator = Validator::make($request->all(), [ 
            'unit_id' => 'required|exists:units,id',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            // Find and delete the unit
            $unit = UnitModel::find($request->get('unit_id'));
            if($unit){
                $unitName = $unit->name;
                $unit->delete();

            // Audit trail logging
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null;
                $activity = "Deleted a unit: " . $unitName . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                return response()->json([
                    'message' => 'Unit successfully deleted!',
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
    // Adds a new unit to the system
    public function addUnit(Request $request){
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'unit_name' => 'required|unique:units,name', // Unit name must be unique
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            // Create and populate the new unit record
            $unit = new UnitModel();
            $unit->name = $request->get('unit_name');
            $unit->control_number = $this->generateControlNumber(); // Generate unique control number
            $unit->symbol = $request->get('unit_symbol');
            $unit->description = ucfirst($request->get('unit_description'));
            $unit->save();

            if($unit){
                // Audit trail
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null;
                $activity = "Added a new unit: " . $unit->name . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                return response()->json([
                    'message' => 'Unit successfully added!',
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
    // Generates a unique control number for a unit
    private function generateControlNumber() {
        $currentYearAndMonth = Carbon::now()->format('Y-m'); // Get current year and month

        // Fetch the latest control number for the current month
        $controlNumber = UnitModel::whereYear('created_at', Carbon::now()->year)
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
    // Retrieves the control number of a unit by its ID
    public function getControlNumber($id){
        $unit = UnitModel::find($id); // Find unit

        if ($unit) {
            return response()->json(['control_number' => $unit->control_number]);
        } else {
            return response()->json(['control_number' => null], 404); // Unit not found
        }
    }

}
