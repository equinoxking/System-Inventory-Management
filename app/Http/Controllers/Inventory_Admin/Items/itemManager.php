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
use App\Models\ReceiveModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
class itemManager extends Controller
{
    public function addItem(Request $request){
        //validate data in item form
        $validator = Validator::make($request->all(), [
            'category' => 'required|array', 
            'category.*' => 'required|exists:categories,name', 
            'categoryId' => 'required|array', 
            'categoryId.*' => 'required|exists:categories,id', 
            'itemName' => 'required|array', 
            'itemName.*' => 'required|min:3|max:60', 
            'unit' => 'required|array', 
            'unit.*' => 'required', 
            'unitId' => 'required|array', 
            'unitId.*' => 'required|exists:units,id', 
            'quantity' => 'required|array', 
            'quantity.*' => 'required|numeric|min:0', 
            'maxQuantity' => 'required|array',
            'maxQuantity.*' => 'required|numeric|min:1'
        ]);
        //if validator fails it will send information to the user
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            //mapping incoming array of data
            foreach ($request->categoryId as $index => $categoryId) {
                //select query for item status
                $status = ItemStatusModel::where('name', 'Available')->first();
                //query to find the selected category id
                $selectedCategory = CategoryModel::findOrFail($categoryId);
                //query to find the selected unit id
                $selectedUnit = UnitModel::findOrFail($request->unitId[$index]);                
                //condition for 
                if (!$selectedCategory || !$selectedUnit) {
                    continue;  
                }
             
                $item = new ItemModel();
                $item->category_id = $selectedCategory->id;
                $item->status_id = $status->id;
                $item->name = ucwords($request->itemName[$index]);
                $item->controlNumber = $this->generateControlNumber();
                $item->save();
        

                $inventory = new InventoryModel();
                $inventory->item_id = $item->id;
                $inventory->quantity = $request->quantity[$index];
                $inventory->unit_id = $selectedUnit->id;
                $inventory->max_quantity = $request->maxQuantity[$index];
                $inventory->save();

                $day = Carbon::now('Asia/Manila')->format('d');
                $month = Carbon::now('Asia/Manila')->format('F');
                $year = Carbon::now('Asia/Manila')->format('Y');
                $receive = new ReceiveModel();
                $receive->item_id = $item->id;
                $receive->received_quantity = $request->quantity[$index];
                $receive->received_day = $day;
                $receive->received_month = $month;
                $receive->received_year = $year;
                $receive->save();
                
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
    public function deleteItem(Request $request){
        $validator = Validator::make($request->all(), [
            'delete-item-id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $item = ItemModel::where('id', $request->get('delete-item-id'))->delete();
            if($item){
                return response()->json([
                    'message' => 'Item successfully deleted.',
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
    public function editItem(Request $request){
        $validator = Validator::make($request->all(), [
            'edit-item-id' => 'required|array', 
            'edit-item-id.*' => 'required|exists:items,id', 
            'edit-category' => 'required|array',
            'edit-category.*' => 'required|exists:categories,name',
            'edit-categoryId' => 'required|array',
            'edit-categoryId.*' => 'required|exists:categories,id',
            'edit-itemName' => 'required|array',
            'edit-itemName.*' => 'required|min:3|max:60',
            'edit-unit' => 'required|array',
            'edit-unit.*' => 'required',
            'edit-unitId' => 'required|array',
            'edit-unitId.*' => 'required|exists:units,id',
            'edit-quantity' => 'required|array',
            'edit-quantity.*' => 'required|numeric|min:0',
            'edit-maxQuantity' => 'required|array',
            'edit-maxQuantity.*' => 'required|numeric|min:1',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            foreach ($request->get('edit-item-id') as $index => $editItemId) {
                $status = ItemStatusModel::where('name', 'Available')->first();
                $selectedCategory = CategoryModel::findOrFail($request->get('edit-categoryId')[$index]);
                $selectedUnit = UnitModel::findOrFail($request->get('edit-unitId')[$index]);
        
                if (!$selectedCategory || !$selectedUnit) {
                    continue; 
                }
        
                $item = ItemModel::find($editItemId);
        
                if ($item) {
                    $item->category_id = $selectedCategory->id;
                    $item->status_id = $status->id;
                    $item->name = ucwords($request->get('edit-itemName')[$index]);  
                    Log::info("Updating item: ", [$item]);
                } else {
     
                    $item = new ItemModel();
                    $item->category_id = $selectedCategory->id;
                    $item->status_id = $status->id;
                    $item->name = ucwords($request->get('edit-itemName')[$index]);
                    $item->controlNumber = $this->generateControlNumber();  
                    Log::info("Saving new item: ", [$item]);
                }
        
                try {
                    $item->save();
                } catch (\Exception $e) {
                    continue;  
                }
        
                $inventory = InventoryModel::where('item_id', $item->id)->first();
        
                if (!$inventory) {
                    $inventory = new InventoryModel();
                    $inventory->item_id = $item->id;
                }
        
                $inventory->quantity = $request->get('edit-quantity')[$index];
                $inventory->unit_id = $selectedUnit->id;
                $inventory->max_quantity = $request->get('edit-maxQuantity')[$index];
                Log::info("Saving inventory: ", [$inventory]);
        
                try {
                    $inventory->save();
                } catch (\Exception $e) {
                    Log::error("Error saving inventory: " . $e->getMessage());
                    continue;  
                }
            }
        
            return response()->json([
                'status' => 200,
                'message' => 'Items and inventories have been updated successfully.',
            ]);
        }
        
    }
}
