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
use Illuminate\Support\Carbon;
class InventoryManager extends Controller
{
    public function showItems(Request $request){
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // Determine previous quarter months
        $currentQuarter = ceil($currentMonth / 3);
        if ($currentQuarter > 1) {
            $startMonth = ($currentQuarter - 2) * 3 + 1;
            $quarterMonths = [$startMonth, $startMonth + 1, $startMonth + 2];
            $year = $currentYear;
        } else {
            $quarterMonths = [10, 11, 12];
            $year = $currentYear - 1;
        }

        // Load necessary relationships
        $items = ItemModel::with(['transacts.transactionDetail', 'inventory'])->get();

        foreach ($items as $item) {
            // Only include 'Completed' transactions
            $filteredTransacts = $item->transacts->filter(function ($transact) {
                return $transact->remark === 'Completed';
            })->flatMap(function ($transact) {
                return $transact->transactionDetail ? collect([$transact->transactionDetail]) : collect();
            })->filter(function ($detail) use ($quarterMonths, $year) {
                return in_array((int) $detail->request_month, $quarterMonths) && $detail->request_year == $year;
            });

            // Group by month and sum the request_quantity
            $monthlyRequests = $filteredTransacts->groupBy('request_month')->map(function ($group) {
                return $group->sum('request_quantity');
            });

            // Calculate total from the monthly requests
            $total = $monthlyRequests->sum();

            if ($total === 0) {
                // If no data found for the 'Completed' transactions, use the existing min_quantity from inventory
                $minQuantity = optional($item->inventory)->min_quantity;
            } else {
                // If data exists, calculate the new min_quantity
                $minQuantity = round(($total / 3) * 2);
            }

            // No need to save to DB if no new data was calculated
            if ($item->inventory && $minQuantity !== optional($item->inventory)->min_quantity) {
                $item->inventory->min_quantity = $minQuantity;
            }
        }

        


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
    public function showDeliveries(Request $request){
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
        return view('admin.items.deliveries', [
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
    public function showCategories(Request $request){
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
        return view('admin.items.categories', [
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
    public function showUnits(Request $request){
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
        return view('admin.items.units', [
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
    public function showAccounts(Request $request){
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
        return view('admin.items.accounts', [
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
