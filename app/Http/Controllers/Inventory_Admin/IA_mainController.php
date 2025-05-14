<?php

namespace App\Http\Controllers\Inventory_Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Illuminate\Http\Request;
use App\Models\CategoryModel;
use App\Models\UnitModel;
use App\Models\ClientModel;
use App\Models\ReceiveModel;
use App\Models\InventoryModel;
use App\Models\TransactionModel;
use App\Models\ItemModel;
use App\Models\NotificationModel;
use App\Models\ReportModel;
use App\Models\RoleModel;
use App\Models\SubCategoryModel;
use App\Models\SupplierModel;
use App\Models\TransactionDetailModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\TrailModel;
use App\Models\TransactionStatusModel;
use GuzzleHttp\Client;

class IA_mainController extends Controller
{
    public function goToDashboard(){
        $clients = ClientModel::all();
        $countclients = ClientModel::count();
        $transaction = TransactionModel::where('remark', 'For Review')->count();
        $receive = ReceiveModel::sum('received_quantity');
        $items = ItemModel::with('transacts.TransactionDetail')->get();
        // $itemsWithTransactionSums = $items->map(function ($item) {
        //     $totalTransactionSum = $item->transacts->sum(function ($transact) {
        //         return $transact->transactionDetail ? $transact->transactionDetail->request_quantity : 0;
        //     });
        //     return [
        //         'item' => $item,
        //         'total_transaction_sum' => $totalTransactionSum
        //     ];
        // });
        $itemCount = ItemModel::count();
        $categories = CategoryModel::all();
        $transactions = TransactionModel::all();
        $notifications = NotificationModel::all();
        $countCategories = CategoryModel::count();
        $currentMonth = Carbon::now()->month;
        $countUnits = UnitModel::count();
        $countReports = ReportModel::count();
        $countTrails = TrailModel::count();
        $roles = RoleModel::all();
        $sub_categories = SubCategoryModel::all();
        $items1 = ItemModel::with(['inventory', 'transacts.transactionDetail'])->get();
        $statuses = TransactionStatusModel::all();
        $units = UnitModel::all();
        $admins = AdminModel::all();
        $suppliers = SupplierModel::all();
        $itemsForMonth = ItemModel::with(['transacts.transactionDetail' => function ($query) use ($currentMonth) {
            $query->where('request_month', $currentMonth);
        }])->get();
        
        $top10IssuedItems = $items1->map(function ($item) {
            $totalIssuedQty = 0;
            $requestCount = 0; // Variable to track the request count
        
            $item->transacts->each(function ($transaction) use (&$totalIssuedQty, &$requestCount, $item) {
                // Skip the transaction if the status is 3
                if ($transaction->status_id == 3) return;
        
                // Only process completed transactions (status_id == 2, remark 'Completed')
                if ($transaction->status_id == 2 && $transaction->remark === 'Completed') {
                    // Get the first transactionDetail that belongs to this item
                    $detail = $transaction->transactionDetail
                        ->firstWhere('item_id', $item->id);
        
                    if ($detail) {
                        // Accumulate the total issued quantity
                        $totalIssuedQty += $detail->request_quantity ?? 0;
        
                        // Increment the request count
                        $requestCount++;
                    }
                }
            });
        
            // Return the item with the total issued quantity and request count
            return [
                'item' => $item,
                'total_issued' => $totalIssuedQty,
                'request_count' => $requestCount, // Add the request count here
            ];
        })
        ->filter(fn($data) => $data['total_issued'] > 0) // Only keep items that have been issued
        ->sortByDesc('total_issued') // Sort by total issued in descending order
        ->take(10) // Get the top 10 items
        ->values(); // Reset the keys
        
        // Separate out the names and issued quantities
        $topItemsNames = [];
        $topItemsIssuedQty = [];
        $topItemsRequestCount = []; // Add an array to store request counts
        
        foreach ($top10IssuedItems as $data) {
            $topItemsNames[] = $data['item']->name;
            $topItemsIssuedQty[] = $data['total_issued'];
            $topItemsRequestCount[] = $data['request_count']; // Collect the request count
        }
        
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $currentQuarter = ceil($currentMonth / 3);

        if ($currentQuarter > 1) {
            $startMonth = ($currentQuarter - 2) * 3 + 1;
            $quarterMonths = [$startMonth, $startMonth + 1, $startMonth + 2];
            $year = $currentYear;
        } else {
            $quarterMonths = [10, 11, 12];
            $year = $currentYear - 1;
        }

        $criticalItemsWithFlag = $items->map(function ($item) use ($quarterMonths, $year) {
            $isCritical = false;
            $totalTransactionSum = 0;
        
            if ($item->inventory) {
                $completedTransacts = $item->transacts->filter(fn($t) => $t->remark === 'Completed');
        
                $transactionDetails = $completedTransacts->flatMap(fn($t) =>
                    $t->transactionDetail ? collect([$t->transactionDetail]) : collect()
                );
        
                // Calculate total transaction quantity (all time, not just this quarter)
                $totalTransactionSum = $transactionDetails->sum('request_quantity');
        
                // Filter by this quarter
                $filteredDetails = $transactionDetails->filter(function ($detail) use ($quarterMonths, $year) {
                    return in_array((int)$detail->request_month, $quarterMonths) && $detail->request_year == $year;
                });
        
                $monthlyTotals = $filteredDetails->groupBy('request_month')->map(fn($g) => $g->sum('request_quantity'));
        
                $monthlyQuantities = collect([
                    $quarterMonths[0] => $monthlyTotals->get($quarterMonths[0], 0),
                    $quarterMonths[1] => $monthlyTotals->get($quarterMonths[1], 0),
                    $quarterMonths[2] => $monthlyTotals->get($quarterMonths[2], 0),
                ]);
        
                $average = round($monthlyQuantities->sum() / 3);
        
                $item->inventory->min_quantity = $average > 0 ? $average : $item->inventory->min_quantity;
        
                $isCritical = $item->inventory->quantity < $item->inventory->min_quantity;
            }
        
            return [
                'item' => $item,
                'is_critical' => $isCritical,
                'total_transaction_sum' => $totalTransactionSum
            ];
        });
        
        // Get only the critical items
        $criticalItemsRecord = $criticalItemsWithFlag->filter(fn($entry) => $entry['is_critical']);
        $criticalCount = $criticalItemsRecord->count();
        
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
            $query->where('remark', 'Completed');
        })
        ->get();  
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
        return view('admin.index', [
            'countclients' => $countclients,
            'transactions' => $transaction,
            'receives' => $receive,
            'items' => $items,
            'transacts' => $transactions,
            'categories' => $categories,
            'notifications' => $notifications,
            'itemCount' => $itemCount,
            'criticalItemsWithSums' => $criticalItemsRecord ,
            'topItemsNames' => $topItemsNames,
            'topItemsIssuedQty' => $topItemsIssuedQty,
            'top10IssuedItems' => $top10IssuedItems,
            'countCategories' => $countCategories,
            'countUnits' => $countUnits,
            'countReports' => $countReports,
            'countTrails' => $countTrails,
            'roles' => $roles,
            'clients' => $clients,
            'statuses' => $statuses,
            'criticalCount' => $criticalCount,
            'sub_categories' => $sub_categories,
            'units' => $units,
            'admins' => $admins,
            'transactionUsers' => $transactionUsers,
            'suppliers' => $suppliers
        ]);
    }
    public function goToTransactions(){
        return view('admin.transaction');
    }
    public function goToRequest(){
        return view('admin.request');
    }
    public function goToReport(){
        return view('admin.report');
    }
    
    public function goToAudits(){
        return view ('admin.audit');
    }
    public function goToProfile(){
        return view ('admin.profile');
    }
}
