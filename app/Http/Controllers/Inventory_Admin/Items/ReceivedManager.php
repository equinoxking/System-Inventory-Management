<?php

namespace App\Http\Controllers\Inventory_Admin\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\ItemModel;
use App\Models\InventoryModel;
use App\Models\ReceiveModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
class ReceivedManager extends Controller
{
    public function searchItem(Request $request){
        $query = $request->input('query'); 
        $items = ItemModel::with('inventory')->where('name', 'like', '%' . $query . '%')
        ->get();
        return response()->json($items);
    }
    public function storeItem(Request $request){
        $itemId = $request->input('item_id');
        $item = ItemModel::find($itemId);

        return response()->json(['
            message' => 'Item selected', 
            'item' => $item
        ]);
    }
    public function receivedItem(Request $request){
        $validator = Validator::make($request->all(), [
            'receivedItemName' => 'required|array', 
            'receivedItemName.*' => 'required', 
            'receivedQuantity' => 'required|array',
            'receivedQuantity.*' => 'required|numeric|min:1',
            'control_number' => 'required|array', 
            'control_number.*' => 'required',
            'supplier' => 'required|array', 
            'supplier.*' => 'required',  
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $allItemsProcessed = true;
            foreach ($request->receivedItemId as $index => $receivedItemId) {
                try {
                    $item = ItemModel::findOrFail($receivedItemId);
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
                    $receive->control_number = $request->get('control_number')[$index];
                    $receive->received_quantity = $request->receivedQuantity[$index];
                    $receive->received_day = $day;
                    $receive->received_month = $monthInt;
                    $receive->received_year = $year;
                    $receive->delivery_type = "Receipt for Stock";
                    $receive->supplier = $request->get('supplier')[$index];
                    $receive->save();

                    $inventory = InventoryModel::where('item_id', $receivedItemId)->first();
                    if ($inventory) {
                        $newQuantity = $inventory->quantity + $receive->received_quantity;
                        $inventory->quantity = $newQuantity;
                        $inventory->save();
                    }
                    if (!$inventory) {
                        $allItemsProcessed = false;
                        break;
                    }
                } catch (\Exception $e) {
                    $allItemsProcessed = false;
                    break;
                }
            }

            if ($allItemsProcessed) {
                return response()->json([
                    'message' => "All items successfully received!",
                    'status' => 200
                ]);
            } else {
                return response()->json([
                    'message' => "Error in receiving an item!",
                    'status' => 500
                ]);
            }            
        }
    }
    public function refreshReceivables(Request $request){
        // Fetch all items with their associated 'receives' and 'inventory.unit' relationships.
        $items = ItemModel::with(['receives', 'inventory.unit', 'inventory'])->get();
    
        // Map each item and its related receives to the data array.
        $data = $items->map(function ($item) {
            return $item->receives->map(function ($receive) use ($item) {
                return [
                    'item_id' => $item->id,
                    'category' => $item->category->name,
                    'supplier' => $receive->supplier,
                    'remaining_quantity' => $item->inventory->quantity,
                    'received_id' => $receive->id,
                    'remark' => $receive->remark,
                    'max_quantity' => $item->inventory->max_quantity,
                    'control_number' => $receive->control_number ?? '',
                    'item_name' => $item->name,
                    'unit_name' => $item->inventory->unit->name ?? '',
                    'received_quantity' => $receive->received_quantity ?? 0,
                    'created_at' => $receive->created_at->format('F d, Y H:i A'),
                    'updated_at' => $receive->updated_at->format('F d, Y H:i A'),
                ];
            });
        });
    
        // Flatten the array of arrays into a single array of data.
        $flattenedData = $data->collapse();
    
        // Return the response as JSON.
        return response()->json([
            'draw' => $request->draw,  // Ensure this matches the draw parameter sent by DataTable
            'recordsTotal' => $flattenedData->count(),
            'recordsFiltered' => $flattenedData->count(),
            'data' => $flattenedData
        ]);
    }
    public function updateReceivedQuantity(Request $request){
        $validator = Validator::make($request->all(), [
            'edit-received-quantity' => 'required|numeric',
            'edit-received-id' => 'required|exists:receivables,id', 
            'item_id' => 'required|exists:items,id', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }
        $receive = ReceiveModel::findOrFail($request->get('edit-received-id'));
        if ($receive) {
            $receive->delivery_type = "Receipt for Stock";
            $receive->save();
            
            $inventory = InventoryModel::where('item_id', $request->item_id)->first();
            if ($inventory) {
                // Update the inventory quantity
                $inventory->quantity += $request->get('edit-received-quantity');
                $inventory->save();
    
                return response()->json([
                    'message' => "Edit receivables and inventory update success!",
                    'status' => 200
                ]);
            } else {
                return response()->json([
                    'message' => "Inventory not found for the given item.",
                    'status' => 404
                ]);
            }
    
        } else {
            return response()->json([
                'message' => "Received record not found.",
                'status' => 404
            ]);
        }
    }    
}