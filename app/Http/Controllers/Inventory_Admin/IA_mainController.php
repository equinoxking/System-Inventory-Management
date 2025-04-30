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
        $itemsWithTransactionSums = $items->map(function ($item) {
            $totalTransactionSum = $item->transacts->sum(function ($transact) {
                return $transact->transactionDetail ? $transact->transactionDetail->request_quantity : 0;
            });
            return [
                'item' => $item,
                'total_transaction_sum' => $totalTransactionSum
            ];
        });
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
        $items = ItemModel::with(['inventory', 'transacts.transactionDetail'])->get();
        $statuses = TransactionStatusModel::all();
        $units = UnitModel::all();
        $admins = AdminModel::all();
        $itemsForMonth = ItemModel::with(['transacts.transactionDetail' => function ($query) use ($currentMonth) {
            $query->where('request_month', $currentMonth);
        }])->get();
        
        $top10IssuedItems = $items->map(function ($item) {
            $totalIssuedQty = 0;
        
            $item->transacts->each(function ($transaction) use (&$totalIssuedQty, $item) {
                if ($transaction->status_id == 3) return;
        
                if ($transaction->status_id == 2 && $transaction->remark === 'Completed') {
                    // Only get the first transactionDetail that belongs to this item
                    $detail = $transaction->transactionDetail
                        ->firstWhere('item_id', $item->id);
        
                    if ($detail) {
                        $totalIssuedQty += $detail->request_quantity ?? 0;
                    }
                }
            });
        
            return [
                'item' => $item,
                'total_issued' => $totalIssuedQty
            ];
        })
        ->filter(fn($data) => $data['total_issued'] > 0)
        ->sortByDesc('total_issued')
        ->take(10)
        ->values(); // <--- THIS resets the keys to 0,1,2,...
        

        $topItemsNames = [];
        $topItemsIssuedQty = [];

        foreach ($top10IssuedItems as $data) {
            $topItemsNames[] = $data['item']->name;
            $topItemsIssuedQty[] = $data['total_issued']; 
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

        $items = ItemModel::with('transacts.transactionDetail')->get();

        foreach ($items as $item) {
            $transactionDetails = $item->transacts
                ->filter(function ($transact) {
                    return $transact->remark == 'Completed';
                })
                ->flatMap(function ($transact) {
                    return $transact->transactionDetail ? collect([$transact->transactionDetail]) : collect();
                });

            $filteredDetails = $transactionDetails->filter(function ($detail) use ($quarterMonths, $year) {
                return in_array((int) $detail->request_month, $quarterMonths)
                    && $detail->request_year == $year;
            });

            $monthlyTotals = $filteredDetails->groupBy('request_month')->map(function ($group) {
                return $group->sum('request_quantity');
            });

            $monthlyQuantities = collect([
                $quarterMonths[0] => $monthlyTotals->get($quarterMonths[0], 0),
                $quarterMonths[1] => $monthlyTotals->get($quarterMonths[1], 0),
                $quarterMonths[2] => $monthlyTotals->get($quarterMonths[2], 0),
            ]);

            $average = round($monthlyQuantities->sum() / 3);

            if ($item->inventory) {
                $item->inventory->min_quantity = $average > 0 
                    ? $average 
                    : $item->inventory->min_quantity;
            }
        }

        // Count items with quantity < buffer (critical items)
        $criticalCount = $items->filter(function ($item) {
            return $item->inventory 
                && $item->inventory->quantity < $item->inventory->min_quantity;
        })->count(); 
        return view('admin.index', [
            'countclients' => $countclients,
            'transactions' => $transaction,
            'receives' => $receive,
            'items' => $items,
            'transacts' => $transactions,
            'categories' => $categories,
            'notifications' => $notifications,
            'itemCount' => $itemCount,
            'itemsWithTransactionSums' => $itemsWithTransactionSums,
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
            'admins' => $admins
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
