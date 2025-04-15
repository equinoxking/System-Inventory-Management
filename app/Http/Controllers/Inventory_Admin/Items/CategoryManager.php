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

class CategoryManager extends Controller
{
    public function searchCategory(Request $request){
        $query = $request->input('query'); 
        $categories = CategoryModel::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->get();
        return response()->json($categories);
    }
    public function store(Request $request){
        $categoryId = $request->input('category_id');
        $category = CategoryModel::find($categoryId);

        return response()->json(['
            message' => 'Category selected', 
            'category' => $category
        ]);
    }
    public function updateCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'main_category' => 'required', 
            'category_id' => 'required|exists:categories,id',
            'category_control_number' => 'required|exists:categories,control_number',
            'category_name' => [
                'required',
                Rule::unique('categories', 'name')->ignore($request->get('category_id')),
            ],
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $category = CategoryModel::findOrFail($request->get('category_id'));
            $category->name = $request->get('category_name');
            $category->description = $request->get('category_description');
            $category->sub_category_id = $request->get('main_category');
            $category->save();

            if($category){
                return response()->json([
                    'message' => 'Category updated successful!',
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
    public function deleteCategory(Request $request){
        $validator = Validator::make($request->all(), [ 
            'category_id' => 'required|exists:categories,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $category = CategoryModel::where('id', $request->get('category_id'))->delete();
            if($category){
                return response()->json([
                    'message' => 'Category deleted successful!',
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
    public function addCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'main_category' => 'required|exists:sub_categories,id', 
            'category_name' => 'required|unique:categories,name',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $category = new CategoryModel();
            $category->name = ucwords($request->get('category_name'));
            $category->control_number = $this->generateControlNumber();
            $category->sub_category_id = $request->get('main_category');
            $category->description = ucfirst($request->get('category_description'));
            $category->save();

            if($category){
                return response()->json([
                    'message' => 'Category deleted successful!',
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
    private function generateControlNumber() {
        $currentYearAndMonth = Carbon::now()->format('Y-m');
        $controlNumber = CategoryModel::whereYear('created_at', Carbon::now()->year)
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
}
