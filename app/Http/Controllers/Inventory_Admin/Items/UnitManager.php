<?php

namespace App\Http\Controllers\Inventory_Admin\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UnitModel;
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
}
