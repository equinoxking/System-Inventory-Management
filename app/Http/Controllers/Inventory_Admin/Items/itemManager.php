<?php

namespace App\Http\Controllers\Inventory_Admin\Items;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\InventoryModel;
use App\Models\ItemModel;
use App\Models\ItemStatusModel;
use App\Models\UnitModel;
use Illuminate\Support\Carbon;
class itemManager extends Controller
{
    public function addItem(Request $request){
        $validator = Validator::make($request->all(), [
            'category' => 'required|max:60',
            'itemName' => 'required|min:3|max:60',
            'unit' => 'required|max:30',
            'quantity' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $status = ItemStatusModel::where('name', 'Available')->first();
            $selectedCategory = CategoryModel::where('id', $request->get('categoryId'))->first();
            $item = new ItemModel();
            $item->category_id = $selectedCategory->id;
            $item->status_id = $status->id;
            $item->name = ucwords($request->get('itemName'));
            $item->controlNumber = $this->generateControlNumber();
            $item->save();

            $selectedUnit = UnitModel::where('id', $request->get('unitId'))->first();
            $inventory = new InventoryModel();
            $inventory->item_id = $item->id;
            $inventory->quantity = $request->get('quantity');
            $inventory->unit_id = $selectedUnit->id;
            $inventory->save();

            return response()->json([
                'message' => 'Item successfully added!',
                'status' => 200
            ]);
        }
        return response()->json([
            'message' => 'Error in adding an item!',
            'status' => 500
        ]);
    }
    private function generateControlNumber(){
        $currentYearAndMonth = Carbon::now()->format('Y-m');
        $controlNumber = ItemModel::whereYear('created_at', Carbon::now()->year)
                            ->whereMonth('created_at', Carbon::now()->month)
                            ->orderBy('controlNumber', 'desc')
                            ->pluck('controlNumber')
                            ->first();
        if (!$controlNumber) {
            return $currentYearAndMonth . '-0001';
        }
        $numberPart = intval(substr($controlNumber, -4)) + 1;
        $paddedNumber = str_pad($numberPart, 4, '0', STR_PAD_LEFT);
        return $currentYearAndMonth . '-' . $paddedNumber;
    }
}
