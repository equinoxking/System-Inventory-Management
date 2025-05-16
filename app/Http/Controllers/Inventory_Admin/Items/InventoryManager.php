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
use App\Models\SupplierModel;
use Illuminate\Support\Carbon;
use App\Models\TransactionModel;
class InventoryManager extends Controller
{
    // Show items with relevant transaction and inventory data
    public function showItems(Request $request){
        // Get current date and extract the current month and year
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // Determine the current quarter based on the current month
        $currentQuarter = ceil($currentMonth / 3);
        if ($currentQuarter > 1) {
            // For quarters 2, 3, and 4, calculate the start month of the previous quarter
            $startMonth = ($currentQuarter - 2) * 3 + 1;
            $quarterMonths = [$startMonth, $startMonth + 1, $startMonth + 2];
            $year = $currentYear;
        } else {
            // For the first quarter, the previous quarter is in the last year (October to December)
            $quarterMonths = [10, 11, 12];
            $year = $currentYear - 1;
        }

        // Load items and their related transactions and inventory
        $items = ItemModel::with(['transacts.transactionDetail', 'inventory'])->get();

        // Iterate through all items
        foreach ($items as $item) {
            // Filter only 'Completed' transactions for this item
            $filteredTransacts = $item->transacts->filter(function ($transact) {
                return $transact->remark === 'Completed';
            })->flatMap(function ($transact) {
                // Get transaction details if available
                return $transact->transactionDetail ? collect([$transact->transactionDetail]) : collect();
            })->filter(function ($detail) use ($quarterMonths, $year) {
                // Filter details based on the quarter months and year
                return in_array((int) $detail->request_month, $quarterMonths) && $detail->request_year == $year;
            });

            // Group filtered transaction details by month and sum the request quantity for each month
            $monthlyRequests = $filteredTransacts->groupBy('request_month')->map(function ($group) {
                return $group->sum('request_quantity');
            });

            // Calculate the total requested quantity over the quarter
            $total = $monthlyRequests->sum();

            if ($total === 0) {
                // If no transactions found, fallback to existing min_quantity from inventory
                $minQuantity = optional($item->inventory)->min_quantity;
            } else {
                // If there are requests, calculate a new min_quantity based on the total request
                $minQuantity = round(($total / 3) * 2);
            }
            // Only update inventory if the min_quantity has changed
            if ($item->inventory && $minQuantity !== optional($item->inventory)->min_quantity) {
                $item->inventory->min_quantity = $minQuantity;
            }
        }
       

        // Load other necessary data for view (categories, units, statuses, etc.)
        $categories = CategoryModel::all();
        $units = UnitModel::all();
        $statuses = ItemStatusModel::all();
        $clients = ClientModel::all();
        $admins = AdminModel::with('role')->get();
        $reports = ReportModel::all();
        $roles = RoleModel::all();
        $sub_categories = SubCategoryModel::all();

        // Load completed transactions with related details
        $transactionUsers = TransactionModel::with([
            'transactionDetail',
            'client',
            'item',
            'item.inventory.unit',
            'status',
            'adminBy',
            'admin'
        ])
        ->where(function ($query) {
            // Only include completed transactions
            $query->where('remark', 'Completed');
        })
        ->get();

        // Get the active section query parameter (default to 'items')
        $activeSection = $request->query('section', 'items');

        // Return view with all necessary data
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
            'transactionUsers' => $transactionUsers
        ], compact('activeSection'));
    }
    // Show deliveries and related data
    public function showDeliveries(Request $request){
        // Fetch items with their associated category, inventory, status, and receives relationships
        $items = ItemModel::with(['category', 'inventory', 'status', 'receives'])
            ->get();
        $itemsReceives = ItemModel::with(['receives', 'inventory.unit', 'inventory'])->get();

        // Transform each item and its receives into a structured array
        $data = $itemsReceives->map(function ($item) {
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
        })->flatten(1);
        // Fetch all necessary related data
        $categories = CategoryModel::all();
        $units = UnitModel::all();
        $statuses = ItemStatusModel::all();
        $clients = ClientModel::all();
        $admins = AdminModel::with('role')->get(); // Include the related role for each admin
        $reports = ReportModel::all();
        $roles = RoleModel::all();
        $sub_categories = SubCategoryModel::all();
        $suppliers = SupplierModel::all();
        // Get the active section from the query parameter (default is 'items')
        $activeSection = $request->query('section', 'items');

        // Fetch completed transactions with related details like transaction details, client, item, etc.
        $transactionUsers = TransactionModel::with([
            'transactionDetail',
            'client',
            'item',
            'item.inventory.unit',
            'status',
            'adminBy', // The admin who performed the transaction
            'admin' // The admin who approved or handled the transaction
        ])
        ->where(function ($query) {
            // Only include transactions marked as 'Completed'
            $query->where('remark', 'Completed');
        })
        ->get();  

        // Return the view with all the necessary data passed to it
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
            'transactionUsers' => $transactionUsers,
            'data' => $data,
            'suppliers' => $suppliers
        ], compact('activeSection'));
    }
    // Show categories and related data
    public function showCategories(Request $request){
        // Fetch items with their associated category, inventory, status, and receives relationships
        $items = ItemModel::with(['category', 'inventory', 'status', 'receives'])
            ->get();

        // Fetch all related data
        $categories = CategoryModel::all();
        $units = UnitModel::all();
        $statuses = ItemStatusModel::all();
        $clients = ClientModel::all();
        $admins = AdminModel::with('role')->get(); // Include roles for admins
        $reports = ReportModel::all();
        $roles = RoleModel::all();
        $sub_categories = SubCategoryModel::all();

        // Fetch completed transactions with all related data
        $transactionUsers = TransactionModel::with([
            'transactionDetail',
            'client',
            'item',
            'item.inventory.unit',
            'status',
            'adminBy', // Admin who performed the transaction
            'admin' // Admin who approved the transaction
        ])
        ->where(function ($query) {
            // Only fetch transactions with 'Completed' remark
            $query->where('remark', 'Completed');
        })
        ->get();  

        // Get the active section query parameter (default is 'items')
        $activeSection = $request->query('section', 'items');

        // Return the view with all the data
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
            'transactionUsers' => $transactionUsers
        ], compact('activeSection'));
    }
    // Show units and related data
    public function showUnits(Request $request){
        // Fetch items with their associated category, inventory, status, and receives relationships
        $items = ItemModel::with(['category', 'inventory', 'status', 'receives'])
            ->get();

        // Fetch all related data
        $categories = CategoryModel::all();
        $units = UnitModel::all();
        $statuses = ItemStatusModel::all();
        $clients = ClientModel::all();
        $admins = AdminModel::with('role')->get();
        $reports = ReportModel::all();
        $roles = RoleModel::all();
        $sub_categories = SubCategoryModel::all();

        // Fetch completed transactions with all related data
        $transactionUsers = TransactionModel::with([
            'transactionDetail',
            'client',
            'item',
            'item.inventory.unit',
            'status',
            'adminBy',
            'admin'
        ])
        ->where(function ($query) {
            // Only fetch transactions with 'Completed' remark
            $query->where('remark', 'Completed');
        })
        ->get();  

        // Get the active section query parameter (default is 'items')
        $activeSection = $request->query('section', 'items');

        // Return the view with all the data
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
            'transactionUsers' => $transactionUsers
        ], compact('activeSection'));
    }
    // Show accounts and related data
    public function showAccounts(Request $request){
        // Fetch items with their associated category, inventory, status, and receives relationships
        $items = ItemModel::with(['category', 'inventory', 'status', 'receives'])
            ->get();

        // Fetch all related data
        $categories = CategoryModel::all();
        $units = UnitModel::all();
        $statuses = ItemStatusModel::all();
        $clients = ClientModel::all();
        $admins = AdminModel::with('role')->get();
        $reports = ReportModel::all();
        $roles = RoleModel::all();
        $sub_categories = SubCategoryModel::all();

        // Fetch completed transactions with all related data
        $transactionUsers = TransactionModel::with([
            'transactionDetail',
            'client',
            'item',
            'item.inventory.unit',
            'status',
            'adminBy',
            'admin'
        ])
        ->where(function ($query) {
            // Only fetch transactions with 'Completed' remark
            $query->where('remark', 'Completed');
        })
        ->get();  

        // Get the active section query parameter (default is 'items')
        $activeSection = $request->query('section', 'items');

        // Return the view with all the data
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
            'transactionUsers' => $transactionUsers
        ], compact('activeSection'));
    }
    public function showSuppliers(Request $request){
        // Fetch items with their associated category, inventory, status, and receives relationships
        $items = ItemModel::with(['category', 'inventory', 'status', 'receives'])
            ->get();
        $itemsReceives = ItemModel::with(['receives', 'inventory.unit', 'inventory'])->get();

        // Transform each item and its receives into a structured array
        $data = $itemsReceives->map(function ($item) {
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
        })->flatten(1);
        // Fetch all necessary related data
        $categories = CategoryModel::all();
        $units = UnitModel::all();
        $statuses = ItemStatusModel::all();
        $clients = ClientModel::all();
        $admins = AdminModel::with('role')->get(); // Include the related role for each admin
        $reports = ReportModel::all();
        $roles = RoleModel::all();
        $sub_categories = SubCategoryModel::all();
        $suppliers = SupplierModel::all();
        // Get the active section from the query parameter (default is 'items')
        $activeSection = $request->query('section', 'items');

        // Fetch completed transactions with related details like transaction details, client, item, etc.
        $transactionUsers = TransactionModel::with([
            'transactionDetail',
            'client',
            'item',
            'item.inventory.unit',
            'status',
            'adminBy', // The admin who performed the transaction
            'admin' // The admin who approved or handled the transaction
        ])
        ->where(function ($query) {
            // Only include transactions marked as 'Completed'
            $query->where('remark', 'Completed');
        })
        ->get();  

        // Return the view with all the necessary data passed to it
        return view('admin.items.suppliers', [
            'items' => $items,
            'categories' => $categories,
            'units' => $units,
            'statuses' => $statuses,
            'clients' => $clients,
            'admins' => $admins,
            'reports' => $reports,
            'roles' => $roles,
            'sub_categories' => $sub_categories,
            'transactionUsers' => $transactionUsers,
            'data' => $data,
            'suppliers' => $suppliers
        ], compact('activeSection'));
    }
}
