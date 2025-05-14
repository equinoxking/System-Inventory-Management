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
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;
use Illuminate\Validation\Rule;

class ReceivedManager extends Controller
{
    // Search for items based on a partial name match
    public function searchItem(Request $request){
        // Get the search query from the request
        $query = $request->input('query');

        // Fetch items that match the query (case-insensitive partial match) along with their inventory relationship
        $items = ItemModel::with('inventory')
            ->where('name', 'like', '%' . $query . '%')
            ->get();

        // Return matching items as a JSON response
        return response()->json($items);
    }
    // Retrieve a specific item by its ID and return it
    public function storeItem(Request $request){
        // Get the item ID from the request input
        $itemId = $request->input('item_id');

        // Find the item by ID in the database
        $item = ItemModel::find($itemId);

        // Return a JSON response with the item details
        return response()->json([
            'message' => 'Item selected',
            'item' => $item
        ]);
    }
    // Handles receiving of inventory items, updating their quantity and logging the transaction
    public function receivedItem(Request $request){
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'receivedItemName' => 'required|array',
            'receivedItemName.*' => 'required',  // Each item name is required
            'receivedQuantity' => 'required|array',
            'receivedQuantity.*' => 'required|numeric|min:1',  // Each quantity must be at least 1
            'control_number' => 'required|array',
            'control_number.0' =>'required',
            'supplier' => 'required|array',
            'supplier.*' => 'required',  // Supplier info is required for each item
        ]);

        // If validation fails, return error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            // Flag to track if all items were processed successfully
            $allItemsProcessed = true;

            // Iterate over each received item
            foreach ($request->receivedItemId as $index => $receivedItemId) {
                try {
                    // Find the item by its ID
                    $item = ItemModel::findOrFail($receivedItemId);

                    // Map month names to integers
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

                    // Get current date information
                    $day = Carbon::now('Asia/Manila')->format('d');
                    $month = Carbon::now('Asia/Manila')->format('F');
                    $year = Carbon::now('Asia/Manila')->format('Y');
                    $monthInt = $monthToInt[$month];

                    // Create a new receive entry
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

                    // Update the corresponding inventory quantity
                    $inventory = InventoryModel::where('item_id', $receivedItemId)->first();
                    if ($inventory) {
                        $newQuantity = $inventory->quantity + $receive->received_quantity;
                        $inventory->quantity = $newQuantity;
                        $inventory->save();

                        // Log the admin activity
                        $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                        $user_id = null;
                        $activity = "Delivered an item: " . $item->name . " Quantity:" . $receive->received_quantity;
                        (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);
                    }

                    // If no inventory was found, mark as failed and break
                    if (!$inventory) {
                        $allItemsProcessed = false;
                        break;
                    }
                } catch (\Exception $e) {
                    // If any error occurs during processing, mark as failed and break
                    $allItemsProcessed = false;
                    break;
                }
            }

            // Return appropriate response based on processing outcome
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
    // This method fetches and returns receivables data with associated item, inventory, and unit info
    public function refreshReceivables(Request $request){
        // Fetch all items including their 'receives', 'inventory.unit', and 'inventory' relationships
        $items = ItemModel::with(['receives', 'inventory.unit', 'inventory'])->get();

        // Transform each item and its receives into a structured array
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

        // Flatten the nested collections into a single-level collection
        $flattenedData = $data->collapse();

        // Return the result as a JSON response (commonly used for DataTables integration)
        return response()->json([
            'draw' => $request->draw,  // Ensures response matches the DataTables draw counter
            'recordsTotal' => $flattenedData->count(),
            'recordsFiltered' => $flattenedData->count(),
            'data' => $flattenedData
        ]);
    }
    // This method updates a received quantity and the inventory associated with the item
    public function updateReceivedQuantity(Request $request){
        // Validate required fields from the request
        $validator = Validator::make($request->all(), [
            'edit-received-quantity' => 'required|numeric',
            'edit-received-id' => 'required|exists:receivables,id',  // Check if receive record exists
            'item_id' => 'required|exists:items,id',  // Check if item exists
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }
        // Find the ReceiveModel record to update
        $receive = ReceiveModel::findOrFail($request->get('edit-received-id'));
        if ($receive) {
            // Update delivery type
            $receive->delivery_type = "Receipt for Stock";
            $receive->save();

            // Update inventory quantity by adding the newly received amount
            $inventory = InventoryModel::where('item_id', $request->item_id)->first();
            if ($inventory) {
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
