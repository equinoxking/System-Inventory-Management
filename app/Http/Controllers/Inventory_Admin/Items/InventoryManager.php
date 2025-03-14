<?php

namespace App\Http\Controllers\Inventory_Admin\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemModel;
class InventoryManager extends Controller
{
    public function showItems(){
        $items = ItemModel::with(['category', 'inventory', 'status'])
        ->get();
        return view('admin.items.view-items', ['items' => $items]);
    }
}
