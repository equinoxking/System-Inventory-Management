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
class ReceivedManager extends Controller
{
    public function searchItem(Request $request){
        $query = $request->input('query'); 
        $items = ItemModel::where('name', 'like', '%' . $query . '%')
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
}