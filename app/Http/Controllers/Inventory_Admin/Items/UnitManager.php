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
    public function searchUnit(Request $request){
        $query = $request->input('query'); 
        $units = UnitModel::where('name', 'like', '%' . $query . '%')
        ->get();
        return response()->json($units);
    }
    public function storeUnit(Request $request){
        $unitId = $request->input('category_id');
        $unit = UnitModel::find($unitId);

        return response()->json(['
            message' => 'Unit selected', 
            'unit' => $unit
        ]);
    }
    public function updateUnit(Request $request){
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required|exists:units,id',
            'unit_control_number' => 'required|exists:units,control_number',
            'unit_name' => [
                'required',
                Rule::unique('units', 'name')->ignore($request->get('unit_id')),
            ],
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $unit = UnitModel::findOrFail($request->get('unit_id'));
            $unit->name = $request->get('unit_name');
            $unit->description = $request->get('unit_description');
            $unit->symbol = $request->get('unit_symbol');
            $unit->save();

            if($unit){
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null;
                $activity = "Updated a unit: " .   $unit->name . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                return response()->json([
                    'message' => 'Unit updated successful!',
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
    public function deleteUnit(Request $request){
        $validator = Validator::make($request->all(), [ 
            'unit_id' => 'required|exists:units,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $unit = UnitModel::find($request->get('unit_id'));
            if($unit){
                $unitName = $unit->name;
                $unit->delete();
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null;
                $activity = "Deleted a unit: " .   $unitName . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);
                return response()->json([
                    'message' => 'Unit deleted successful!',
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
    public function addUnit(Request $request){
        $validator = Validator::make($request->all(), [
            'unit_name' => 'required|unique:units,name',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $unit = new UnitModel();
            $unit->name = $request->get('unit_name');
            $unit->control_number = $this->generateControlNumber();
            $unit->symbol = $request->get('unit_symbol');
            $unit->description = ucfirst($request->get('unit_description'));
            $unit->save();

            if($unit){
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null;
                $activity = "Added a new unit: " .   $unit->name . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);
                return response()->json([
                    'message' => 'Unit added successful!',
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
        $controlNumber = UnitModel::whereYear('created_at', Carbon::now()->year)
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
