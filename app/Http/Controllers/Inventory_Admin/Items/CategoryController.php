<?php

namespace App\Http\Controllers\Inventory_Admin\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryModel;
use Illuminate\Support\Facades\Log;
class CategoryController extends Controller
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
}
