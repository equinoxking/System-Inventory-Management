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

                $monthToInt = [
                    'January' => 1,
                    'February' => 2,
                    'March' => 3,
                    'April' => 4,
                    'May' => 5,
                    'June' => 6,
                    'July' => 7,
                    'August' => 8,
                    'September' => 9,
                    'October' => 10,
                    'November' => 11,
                    'December' => 12,
                ];
                $day = Carbon::now('Asia/Manila')->format('d');
                $month = Carbon::now('Asia/Manila')->format('F');
                $year = Carbon::now('Asia/Manila')->format('Y');
                $monthInt = $monthToInt[$month];

                $receive = new ReceiveModel();
                $receive->item_id = $item->id;
                $receive->control_number = $this->generateControlNumberReceived();
                $receive->received_quantity = $request->quantity[$index];
                $receive->received_day = $day;
                $receive->received_month = $monthInt;
                $receive->received_year = $year;
                $receive->save();
                
            }
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
    private function generateControlNumber() {
        $currentYearAndMonth = Carbon::now()->format('Y-m');
        $controlNumber = ItemModel::whereYear('created_at', Carbon::now()->year)
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->orderBy('controlNumber', 'desc')
                                ->pluck('controlNumber')
                                ->first();
    
        if (!$controlNumber) {
            return $currentYearAndMonth . '-00001';
        }
    
        $numberPart = intval(substr($controlNumber, -5)) + 1; 
        $paddedNumber = str_pad($numberPart, 5, '0', STR_PAD_LEFT);
    
        return $currentYearAndMonth . '-' . $paddedNumber;
    }
    private function generateControlNumberReceived() {
        $currentYearAndMonth = Carbon::now()->format('Y-m');
        $controlNumber = ReceiveModel::whereYear('created_at', Carbon::now()->year)
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
                } else {
     
                    $item = new ItemModel();
                    $item->category_id = $selectedCategory->id;
                    $item->status_id = $status->id;
                    $item->name = ucwords($request->get('edit-itemName')[$index]);
                    $item->controlNumber = $this->generateControlNumber();  
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
                $inventory->unit_id = $selectedUnit->id;
                try {
                    $inventory->save();
                } catch (\Exception $e) {
                    continue;  
                }
            }
        
            return response()->json([
                'status' => 200,
                'message' => 'Items and inventories have been updated successfully.',
            ]);
        }
        
    }
    public function getItem(Request $request){
    // Start the query with eager loading
    $itemsQuery = ItemModel::with(['category', 'category.subCategory', 'inventory', 'inventory.unit', 'status']);
    
    // Handle search filter (if present)
    if ($request->has('search') && $request->search['value']) {
        $search = $request->search['value'];
        $itemsQuery->where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhereHas('category', function ($query) use ($search) {
                      $query->where('name', 'like', "%$search%");
                  })
                  ->orWhereHas('status', function ($query) use ($search) {
                      $query->where('name', 'like', "%$search%");
                  });
        });
    }

    // Apply category filter if specified
    if ($request->category) {
        $itemsQuery->whereHas('category', function ($query) use ($request) {
            $query->where('name', $request->category); // Filter based on the category name
        });
    }


    // Apply unit filter if specified
    if ($request->minQuantity) {
        $itemsQuery->whereHas('inventory', function ($query) use ($request) {
            $query->where('quantity', '>=', $request->minQuantity); // Filter by minimum quantity in inventory
        });
    }
    // Apply status filter if specified
    if ($request->unit) {
        $itemsQuery->whereHas('inventory', function ($query) use ($request) {
            $query->whereHas('unit', function ($query) use ($request) {
                $query->where('name', $request->unit); // Filter by unit name inside inventory
            });
        });
    }

    // Apply quantity filters
    if ($request->minQuantity) {
        $itemsQuery->whereHas('inventory', function ($query) use ($request) {
            $query->where('quantity', '>=', $request->minQuantity); // Filter by minimum quantity in inventory
        });
    }
    if ($request->maxQuantity) {
        $itemsQuery->whereHas('inventory', function ($query) use ($request) {
            $query->where('quantity', '<=', $request->maxQuantity); // Filter by maximum quantity in inventory
        });
    }
    if ($request->status) {
        $itemsQuery->whereHas('status', function ($query) use ($request) {
            $query->where('name', $request->status); // Filter by maximum quantity in inventory
        });
    }
    $items = $itemsQuery
    ->orderBy('controlNumber', 'desc')
    ->get();

    // Handle stock level filtering in memory
    if ($request->level) {
        $items = $items->filter(function ($item) use ($request) {
            // Calculate the percentage based on quantity and max_quantity
            $quantity = $item->inventory ? $item->inventory->quantity : 0;
            $maxQuantity = $item->inventory ? $item->inventory->max_quantity : 0;
            $percentage = $maxQuantity > 0 ? ($quantity / $maxQuantity) * 100 : 0;

            // Filter based on the level requested
            if ($request->level == 'No Stock' && $percentage == 0) {
                return true;  // No stock condition
            } elseif ($request->level == 'Low Stock' && $percentage <= 20) {
                return true;  // Low stock condition
            } elseif ($request->level == 'Moderate Stock' && $percentage > 20 && $percentage <= 50) {
                return true;  // Moderate stock condition
            } elseif ($request->level == 'High Stock' && $percentage > 50) {
                return true;  // High stock condition
            }

            return false;  // Exclude items that don't match the level condition
        });
    }



    // Get the filtered records count (for recordsFiltered)
    $totalFilteredRecords = $itemsQuery->count();

    // Apply pagination (skip and take) for the DataTable
    $items = $items->slice($request->start, $request->length);

    // Map the items to the required format
    $formatItem = $items->map(function ($item) {
        $quantity = $item->inventory ? $item->inventory->quantity : 0;
        $maxQuantity = $item->inventory ? $item->inventory->max_quantity : 0;
        $percentage = $maxQuantity > 0 ? ($quantity / $maxQuantity) * 100 : 0;

        return [
            'item_id' => $item->id,
            'category_name' => $item->category ? $item->category->name : null,
            'item_name' => $item->name,
            'quantity' => $quantity,
            'max_quantity' => $item->inventory->max_quantity,
            'unit_name' => $item->inventory && $item->inventory->unit ? $item->inventory->unit->name : null,
            'status_name' => $item->status ? $item->status->name : null,
            'percentage' => $percentage,
            'control_number' => $item->controlNumber,
            'created_at' => \Carbon\Carbon::parse($item->created_at)->format('F d, Y H:i A'),
            'updated_at' => \Carbon\Carbon::parse($item->updated_at)->format('F d, Y H:i A')
        ];
    });

    // Return the response in the DataTable expected format
    return response()->json([
        'draw' => (int)$request->draw, // Echo the draw count from the request
        'recordsTotal' => $totalFilteredRecords, // Total records after filtering
        'recordsFiltered' => $totalFilteredRecords, // Records after applying filter
        'data' => $formatItem // Data to populate the table
    ]);
}

}
