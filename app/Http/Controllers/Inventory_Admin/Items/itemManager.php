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
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;
use Illuminate\Support\Facades\Log;


class itemManager extends Controller
{
    // Add new item to the inventory
    public function addItem(Request $request){
        // Validate the incoming data from the item form
        $validator = Validator::make($request->all(), [
            'category' => 'required|array', 
            'category.*' => 'required|exists:categories,name', // Ensure each category exists
            'categoryId' => 'required|array', 
            'categoryId.*' => 'required|exists:categories,id', // Ensure each category ID exists
            'itemName' => 'required|array|unique:items,name', // Ensure the item name is unique
            'itemName.*' => 'required|min:3|max:60', // Ensure the item name length is between 3 and 60 characters
            'unit' => 'required|array', 
            'unit.*' => 'required', // Ensure each unit is present
            'unitId' => 'required|array', 
            'unitId.*' => 'required|exists:units,id', // Ensure each unit ID exists
            'quantity' => 'required|array', 
            'quantity.*' => 'required|numeric|min:0', // Ensure quantity is numeric and greater than or equal to 0
        ]);
        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors() // Return validation errors to the user
            ]);
        } else {
            // Loop through each category ID to process the corresponding item data
            foreach ($request->categoryId as $index => $categoryId) {
                // Get the status for 'Available' item
                $status = ItemStatusModel::where('name', 'Available')->first();
                // Find the category by ID
                $selectedCategory = CategoryModel::findOrFail($categoryId);
                // Find the unit by ID
                $selectedUnit = UnitModel::findOrFail($request->unitId[$index]);                

                // If category or unit is not found, continue to the next iteration
                if (!$selectedCategory || !$selectedUnit) {
                    continue;  
                }
            
                // Create a new item and set its properties
                $item = new ItemModel();
                $item->category_id = $selectedCategory->id;
                $item->status_id = $status->id;
                $item->name = ucwords($request->itemName[$index]); // Capitalize the first letter of each word
                $item->controlNumber = $this->generateControlNumber(); // Generate a control number
                $item->save();

                // Create a new inventory record for the item
                $inventory = new InventoryModel();
                $inventory->item_id = $item->id;
                $inventory->quantity = $request->quantity[$index];
                $inventory->unit_id = $selectedUnit->id;
                $inventory->min_quantity = $request->buffer[$index]; // Minimum quantity or buffer value
                $inventory->save();

                // Define months in an integer format for easier mapping
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

                // Get current date and time in Manila timezone
                $day = Carbon::now('Asia/Manila')->format('d');
                $month = Carbon::now('Asia/Manila')->format('F'); // Month name
                $year = Carbon::now('Asia/Manila')->format('Y'); // Current year
                $monthInt = $monthToInt[$month]; // Get the month as an integer

                // Record the item receipt details
                $receive = new ReceiveModel();
                $receive->item_id = $item->id;
                $receive->received_quantity = $request->quantity[$index];
                $receive->delivery_type = "Receipt for Stock"; // Do not customized this
                $receive->received_day = $day;
                $receive->received_month = $monthInt;
                $receive->received_year = $year;
                $receive->save();

                // Log the admin activity (adding a new item)
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null; // Assuming null for now, replace if needed
                $activity = "Added a new item: " . $item->name . "."; // Activity log message
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity); // Create user trail for audit

            }
            // Return success message if item was successfully added
            return response()->json([
                'message' => 'Item successfully added!',
                'status' => 200
            ]);
        }
        // Return failure message in case of an unexpected error
        return response()->json([
            'message' => 'Check your internet connection!',
            'status' => 500
        ]);
    }
    // Generate a unique control number for items based on the current year and month
    private function generateControlNumber() {
        // Get the current year and month in 'Y-m' format (e.g., '2025-05')
        $currentYearAndMonth = Carbon::now()->format('Y-m');

        // Query the most recent item by year and month, ordered by control number in descending order
        $controlNumber = ItemModel::whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->orderBy('controlNumber', 'desc')
                                    ->pluck('controlNumber') // Pluck only the control number of the most recent item
                                    ->first();

        // If no control number is found (first item of the month), return the first control number with a '00001' suffix
        if (!$controlNumber) {
            return $currentYearAndMonth . '-00001';
        }

        // Extract the numeric part of the control number (last 5 digits), increment it by 1
        $numberPart = intval(substr($controlNumber, -5)) + 1;

        // Pad the incremented number with leading zeros (to ensure it's always 5 digits)
        $paddedNumber = str_pad($numberPart, 5, '0', STR_PAD_LEFT);

        // Return the control number with the format 'YYYY-MM-XXXXX'
        return $currentYearAndMonth . '-' . $paddedNumber;
    }  
    // Delete an item from the inventory
    public function deleteItem(Request $request){
        // Validate that 'delete-item-id' is provided in the request
        $validator = Validator::make($request->all(), [
            'delete-item-id' => 'required'
        ]);

        // If validation fails, return error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            // Find the item by its ID, using the provided 'delete-item-id'
            $item = ItemModel::find($request->get('delete-item-id'));

            // If the item exists, delete it
            if($item){
                // Store the item's name for the activity log
                $itemName = $item->name; 
                // Delete the item from the database
                $item->delete();

                // Get the logged-in admin's ID from the session
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null; // User ID is set to null here, assuming no user activity is logged
                // Create an activity log for deleting the item
                $activity = "Deleted an item: " .  $itemName . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                // Return a success response to the client
                return response()->json([
                    'message' => 'Item successfully deleted.',
                    'status' => 200
                ]);
            } else {
                // If the item doesn't exist, return an error response
                return response()->json([
                    'message' => 'Check your internet connection!',
                    'status' => 500
                ]);
            }
        }
    }
    // Edit existing items or create new ones with updated information and inventory details
    public function editItem(Request $request){
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'edit-item-id' => 'required|array', 
            'edit-item-id.*' => 'required|exists:items,id',  // Ensure each item ID exists in the 'items' table
            'edit-category' => 'required|exists:categories,id',
            'item_name' => 'required|array',
            'item_name.*' => 'required|min:3|max:60',  // Validate item names with a minimum length of 3 and maximum length of 60
            'edit-unit.*' => 'required|exists:units,id',  // Validate unit IDs exist in the 'units' table
            'buffer' => 'required|array',
            'buffer.*' => 'required',  // Ensure buffer values are provided
        ]);

        // If validation fails, return a response with the validation errors
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            // Loop through each item to be edited
            foreach ($request->get('edit-item-id') as $index => $editItemId) {
                // Fetch the 'Available' item status
                $status = ItemStatusModel::where('name', 'Available')->first();
                // Fetch the category and unit based on provided IDs
                $selectedCategory = CategoryModel::findOrFail($request->get('edit-category'));
                $selectedUnit = UnitModel::findOrFail($request->get('edit-unit'));

                // If either category or unit is invalid, skip the current iteration
                if (!$selectedCategory || !$selectedUnit) {
                    continue; 
                }

                // Attempt to find the existing item, or create a new one if not found
                $item = ItemModel::find($editItemId);
                if ($item) {
                    $itemNameOld = $item->name;
                    // If item exists, update its details
                    $item->category_id = $selectedCategory->id;
                    $item->status_id = $status->id;
                    $item->name = ucwords($request->get('item_name')[$index]);  
                } else {
                    // If item does not exist, create a new item and generate a control number
                    $item = new ItemModel();
                    $item->category_id = $selectedCategory->id;
                    $item->status_id = $status->id;
                    $item->name = ucwords($request->get('item_name')[$index]);
                    $item->controlNumber = $this->generateControlNumber();  
                }

                // Save the item details, with exception handling
                try {
                    $item->save();
                } catch (\Exception $e) {
                    continue;  // If save fails, continue with the next item
                }

                // Check if the inventory for the item already exists
                $inventory = InventoryModel::where('item_id', $item->id)->first();
                if (!$inventory) {
                    // If no inventory found, create a new inventory entry
                    $inventory = new InventoryModel();
                    $inventory->item_id = $item->id;          
                }

                // Update the inventory's minimum quantity (buffer value) and unit ID
                $inventory->min_quantity = $request->get('buffer')[$index];
                $inventory->unit_id = $selectedUnit->id;

                // Save the inventory details, with exception handling
                try {
                    // Log the activity of editing an item
                    $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                    $user_id = null;  // User ID is null here, assuming no specific user is being tracked
                    $activity = "Edited an item " .  'from ' . $itemNameOld . " to " . $item->name . '.';
                    (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                    // Save the updated inventory
                    $inventory->save();
                } catch (\Exception $e) {
                    continue;  // If save fails, continue with the next item
                }
            }

            // Return a success response once all items and inventories have been updated
            return response()->json([
                'status' => 200,
                'message' => 'Items and inventories have been updated successfully.',
            ]);
        }
    }
    public function getItemInfo($id){
    $item = ItemModel::with(['category', 'inventory.unit'])->findOrFail($id);

    return response()->json([
        'category_id' => $item->category_id,
        'unit_id' => $item->inventory->unit_id ?? null,
        'min_quantity' => $item->inventory->min_quantity ?? 0,
        'item_name' => $item->name,
    ]);
    }
}
