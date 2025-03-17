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
use Illuminate\Support\Facades\Log;
class itemManager extends Controller
{
    public function addItem(Request $request){
        $validator = Validator::make($request->all(), [
            'category' => 'required|array', // Expecting an array of categories
            'category.*' => 'required|exists:categories,name', // Validate each category name
            'categoryId' => 'required|array', // Expecting an array of category IDs
            'categoryId.*' => 'required|exists:categories,id', // Validate each category ID
            'itemName' => 'required|array', // Expecting an array of item names
            'itemName.*' => 'required|min:3|max:60', // Validate each item name
            'unit' => 'required|array', // Expecting an array of units
            'unit.*' => 'required', // Validate each unit (this depends on your validation rule)
            'unitId' => 'required|array', // Expecting an array of unit IDs
            'unitId.*' => 'required|exists:units,id', // Validate each unit ID
            'quantity' => 'required|array', // Expecting an array of quantities
            'quantity.*' => 'required|numeric|min:0', // Validate each quantity
            'maxQuantity' => 'required|array',
            'maxQuantity.*' => 'required|numeric|min:1'

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            foreach ($request->categoryId as $index => $categoryId) {
                // Get the status for the item
                $status = ItemStatusModel::where('name', 'Available')->first();
                
                // Get the category and unit based on IDs
                $selectedCategory = CategoryModel::findOrFail($categoryId);
                $selectedUnit = UnitModel::findOrFail($request->unitId[$index]);                

                if (!$selectedCategory || !$selectedUnit) {
                    continue;  // Skip invalid categories or units
                }
             
                // Create a new item
                $item = new ItemModel();
                $item->category_id = $selectedCategory->id;
                $item->status_id = $status->id;
                $item->name = ucwords($request->itemName[$index]);
                $item->controlNumber = $this->generateControlNumber();
                Log::info("Saving item: ", [$item]);
                $item->save();
        
                // Create a new inventory entry for the item
                $inventory = new InventoryModel();
                $inventory->item_id = $item->id;
                $inventory->quantity = $request->quantity[$index];
                $inventory->unit_id = $selectedUnit->id;
                $inventory->max_quantity = $request->maxQuantity[$index];
                Log::info("Saving inventory: ", [$inventory]);
                $inventory->save();
                
            }
            Log::info('Request Data:', $request->all());
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
