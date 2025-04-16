<?php

namespace App\Http\Controllers\Inventory_Admin\Items;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemModel;
use App\Models\CategoryModel;
use App\Models\UnitModel;
use App\Models\ItemStatusModel;
use App\Models\ClientModel;
use App\Models\AdminModel;
use App\Models\ReportModel;
use App\Models\RoleModel;
use App\Models\SubCategoryModel;

class InventoryManager extends Controller
{
    public function showItems(Request $request){
        $items = ItemModel::with(['category', 'inventory', 'status' , 'receives'])
        ->get();
        $categories = CategoryModel::all();
        $units = UnitModel::all();
        $statuses = ItemStatusModel::all();
        $clients = ClientModel::all();
        $admins = AdminModel::with('role')->get();
        $reports = ReportModel::all();
        $roles = RoleModel::all();
        $sub_categories = SubCategoryModel::all();
        $activeSection = $request->query('section', 'items');
        return view('admin.items.view-items', [
            'items' => $items,
            'categories' => $categories,
            'units' => $units,
            'statuses' => $statuses,
            'clients' => $clients,
            'admins' => $admins,
            'reports' => $reports,
            'roles' => $roles,
            'sub_categories' => $sub_categories,
        ], compact('activeSection'));
    }
}
