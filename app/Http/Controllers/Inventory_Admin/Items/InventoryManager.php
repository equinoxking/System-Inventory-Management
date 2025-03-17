<?php

namespace App\Http\Controllers\Inventory_Admin\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemModel;
use App\Models\CategoryModel;
use App\Models\UnitModel;
use App\Models\ItemStatusModel;
class InventoryManager extends Controller
{
    public function showItems(){
        $items = ItemModel::with(['category', 'inventory', 'status'])
        ->get();
        $categories = CategoryModel::all();
        $units = UnitModel::all();
        $statuses = ItemStatusModel::all();
        return view('admin.items.view-items', [
            'items' => $items,
            'categories' => $categories,
            'units' => $units,
            'statuses' => $statuses
        ]);
    }
}
