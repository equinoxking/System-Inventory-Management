<?php

namespace App\Http\Controllers\Inventory_Admin\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryModel;
use App\Models\SubCategoryModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;

class CategoryManager extends Controller
{
   // Controller method to search categories based on a query string
    public function searchCategory(Request $request){
        // Get the 'query' input from the request
        $query = $request->input('query'); 

        // Search categories where the name or description contains the query string (case-insensitive)
        $categories = CategoryModel::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->get();

        // Return the matching categories as a JSON response
        return response()->json($categories);
    }

    // Controller method to retrieve a single category based on its ID
    public function store(Request $request){
        // Get the 'category_id' input from the request
        $categoryId = $request->input('category_id');

        // Find the category by ID
        $category = CategoryModel::find($categoryId);

        // Return the selected category along with a message as a JSON response
        return response()->json([
            'message' => 'Category selected', 
            'category' => $category
        ]);
    }
    // Update a category
    public function updateCategory(Request $request){
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'main_category' => 'required', // Main category is required
            'category_id' => 'required|exists:categories,id', // Must exist in categories table
            'category_control_number' => 'required|exists:categories,control_number', // Must exist in categories table
            'category_name' => [
                'required', // Name is required
                Rule::unique('categories', 'name')->ignore($request->get('category_id')), // Unique except for current category
            ],
        ]);
        
        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            // Fetch the category record
            $category = CategoryModel::findOrFail($request->get('category_id'));

            // Update the category's attributes
            $category->name = $request->get('category_name');
            $category->description = $request->get('category_description');
            $category->sub_category_id = $request->get('main_category');
            $category->save(); // Save updated category

            if($category){
                // Log admin activity
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null;
                $activity = "Edited a category: " .   $category->name . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                return response()->json([
                    'message' => 'Category successfully updated!',
                    'status' => 200
                ]);
            }else{
                // If saving fails
                return response()->json([
                    'message' => 'Check your internet connection!',
                    'status' => 500
                ]);
            }
        }
    }
    // Delete a category
    public function deleteCategory(Request $request){
        // Validate request to ensure category_id is valid
        $validator = Validator::make($request->all(), [ 
            'category_id' => 'required|exists:categories,id',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            // Find the category to delete
            $category = CategoryModel::find($request->get('category_id'));
            if($category){
                $categoryName = $category->name; // Store name for logging
                $category->delete(); // Delete the category

                // Log admin activity
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null;
                $activity = "Deleted a category: " .   $categoryName . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                return response()->json([
                    'message' => 'Category successfully deleted!',
                    'status' => 200
                ]);
            }else{
                // If category not found or deletion fails
                return response()->json([
                    'message' => 'Check your internet connection!',
                    'status' => 500
                ]);
            }
        }
    }
    // Add a new category
    public function addCategory(Request $request){
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'main_category' => 'required|exists:sub_categories,id', // Main category must exist in sub_categories table
            'category_name' => 'required|unique:categories,name', // Category name must be unique in categories table
        ]);
        
        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            // Create a new category record
            $category = new CategoryModel();
            $category->name = ucwords($request->get('category_name')); // Capitalize category name
            $category->control_number = $this->generateControlNumber(); // Generate a unique control number
            $category->sub_category_id = $request->get('main_category'); // Set the sub category ID
            $category->description = ucfirst($request->get('category_description')); // Capitalize category description
            $category->save(); // Save the category to the database

            if($category){
                // Log admin activity for adding a new category
                $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                $user_id = null;
                $activity = "Added a category: " .    $category->name . ".";
                (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                return response()->json([
                    'message' => 'Category successfully added!',
                    'status' => 200
                ]);
            }else{
                // If saving fails, return error response
                return response()->json([
                    'message' => 'Check your internet connection!',
                    'status' => 500
                ]);
            }
        }
    }
    // Generate a unique control number for the new category
    private function generateControlNumber() {
        // Get current year and month in 'Y-m' format (e.g., 2025-05)
        $currentYearAndMonth = Carbon::now()->format('Y-m');

        // Get the highest control number for the current year and month
        $controlNumber = CategoryModel::whereYear('created_at', Carbon::now()->year)
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->orderBy('control_number', 'desc')
                                ->pluck('control_number')
                                ->first();

        // If no control number exists, return the first control number for this year/month
        if (!$controlNumber) {
            return $currentYearAndMonth . '-00001';
        }

        // Increment the control number by 1 and pad it to 5 digits
        $numberPart = intval(substr($controlNumber, -5)) + 1; 
        $paddedNumber = str_pad($numberPart, 5, '0', STR_PAD_LEFT);

        // Return the new control number in the format 'YYYY-MM-XXXXX'
        return $currentYearAndMonth . '-' . $paddedNumber;
    } 

    // Get the control number for a specific category by ID
    public function getControlNumber($id){
        // Find the category by ID
        $category = CategoryModel::find($id);

        // If the category exists, return its control number
        if ($category) {
            return response()->json(['control_number' => $category->control_number]);
        } else {
            // If category not found, return 404 response with null control number
            return response()->json(['control_number' => null], 404);
        }
    }
}
