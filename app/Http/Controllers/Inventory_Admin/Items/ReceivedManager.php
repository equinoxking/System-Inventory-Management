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
            'receivedQuantity.*' => 'required|numeric|min:1'
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
                    $receive->control_number = $this->generateControlNumber();
                    $receive->received_quantity = $request->receivedQuantity[$index];
                    $receive->received_day = $day;
                    $receive->received_month = $monthInt;
                    $receive->received_year = $year;
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
    private function generateControlNumber() {
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
    public function refreshReceivables(Request $request)
    {
        // Fetch all items with their associated 'receives' and 'inventory.unit' relationships.
        $items = ItemModel::with(['receives', 'inventory.unit', 'inventory'])->get();
    
        // Map each item and its related receives to the data array.
        $data = $items->map(function ($item) {
            return $item->receives->map(function ($receive) use ($item) {
                return [
                    'item_id' => $item->id,
                    'received_id' => $receive->id,
                    'max_quantity' => $item->inventory->max_quantity,
                    'control_number' => $receive->control_number ?? '',
                    'item_name' => $item->name,
                    'unit_name' => $item->inventory->unit->name ?? '',
                    'received_quantity' => $receive->received_quantity ?? 0,
                    'created_at' => $item->created_at->format('F d, Y H:i A'),
                    'updated_at' => $item->updated_at->format('F d, Y H:i A'),
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
    public function updateReceivedQuantity(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'edit-received-quantity' => 'required|numeric', // Ensure the quantity is numeric
            'edit-received-id' => 'required|exists:receivables,id', // Ensure the receive exists
            'item_id' => 'required|exists:items,id', // Ensure the item exists
        ]);
        
        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }
    
        // Find the receive by ID
        $receive = ReceiveModel::where('id', $request->get('edit-received-id'))->first();
    
        if ($receive) {
            // Update the received quantity for the receive record
            $receive->received_quantity = $request->get('edit-received-quantity');
            $receive->save();
    
            // Calculate the total received quantity for the item
            $totalReceivedQuantity = ReceiveModel::where('item_id', $request->item_id)
                ->sum('received_quantity');
    
            // Calculate the total transaction quantity for the item (using TransactionDetailModel)
            $totalTransactionQuantity = TransactionDetailModel::where('item_id', $request->item_id)
                ->sum('request_quantity');  // Assumes 'quantity' is stored in the transaction_details table
    
            // Calculate the final inventory quantity
            $finalInventoryQuantity = $totalReceivedQuantity - $totalTransactionQuantity;
    
            // Find the inventory associated with the item
            $inventory = InventoryModel::where('item_id', $request->item_id)->first();
    
            if ($inventory) {
                // Update the inventory quantity
                $inventory->quantity = $finalInventoryQuantity;
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