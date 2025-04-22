<?php

namespace App\Http\Controllers\Inventory_Admin\Charts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionModel;
use App\Models\ReceiveModel;
use App\Models\ItemModel;
use Illuminate\Support\Carbon;

class ChartManager extends Controller
{
    public function goToCharts()
{
    $month = now()->format('F Y');
    $startOfMonth = now()->startOfMonth();
    $endOfMonth = now()->endOfMonth();

    // 1. Count transactions grouped by remark
    $counts = TransactionModel::selectRaw('remark, COUNT(*) as total')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->whereIn('remark', ['For Review', 'Ready for Release', 'Released', 'Completed', 'Denied', 'Canceled'])
        ->groupBy('remark')
        ->get();

    $labels1 = ['For Review', 'Ready for Release', 'Released', 'Completed', 'Denied', 'Canceled'];
    $data1 = [];

    foreach ($labels1 as $label) {
        $count = $counts->firstWhere('remark', $label);
        $data1[] = $count ? $count->total : 0;
    }

    // 2. Count total deliveries this month
    $totalDelivered = ReceiveModel::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

    // 3. Count completed transactions based on status_id
   // 1. Get all completed transactions (status_id = 2) within the given month range
    $completedTransactionsThisMonth = TransactionModel::with('transactionDetail')
    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])  // Filter by date range
    ->where('status_id', 2)
    ->count();

    // 2. Get all items with inventory and transactions
    $allItems1 = ItemModel::with(['inventory', 'transacts.transactionDetail'])->get();
  
    // 3. Calculate transaction sums per item (with monthly filter)
    $itemsTransactionSumsMonthly = $allItems1->map(function ($item) use ($startOfMonth, $endOfMonth) {
        $itemsTransactionSumsMonthly = $item->transacts
            ->where('status_id', 2)  // Filter by completed transactions
            ->filter(function ($transaction) use ($startOfMonth, $endOfMonth) {
                // Filter transactions based on the date range (monthly filter)
                return Carbon::parse($transaction->created_at)->between($startOfMonth, $endOfMonth);
            })
            ->sum(function ($transact) {
                return $transact->transactionDetail
                    ? $transact->transactionDetail->request_quantity
                    : 0;
            });

        return [
            'item' => $item,
            'item_transaction_sum' => $itemsTransactionSumsMonthly,
        ];
    });

    // 4. Compute total current stock (from inventory table)
    $totalInventoryQuantity1 = $allItems1->sum(function ($item) {
        return $item->inventory->quantity ?? 0;
    });

    // 5. Compute total issued quantity within the monthly filter
    $totalIssuedQuantity1 = $itemsTransactionSumsMonthly->sum(function ($itemWithSum) {
        return $itemWithSum['item_transaction_sum'];
    });

    // 6. Compute remaining inventory
    $remainingInventoryQuantity1 = $totalInventoryQuantity1 - $totalIssuedQuantity1;

    // 7. Prepare chart data
    
    $chartDataMonthly = [
        'labelItems' => ['Issued Quantity', 'Remaining Inventory'],
        'currentStock' => $remainingInventoryQuantity1,
        'issuedData' => $totalIssuedQuantity1,
    ];
    // 8. Prepare monthly top issued items
    $itemsForMonth = ItemModel::with(['transacts.transactionDetail' => function ($query) use ($startOfMonth, $endOfMonth) {
        $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
    }])->get();
    
    $itemSummaries = $itemsForMonth->map(function ($item) {
        $totalIssuedThisMonth = $item->transacts
            ->where('status_id', 2) // Apply the status filter here
            ->sum(function ($transaction) {
                return $transaction->transactionDetail->sum('request_quantity');
            });
    
        return [
            'item' => $item,
            'total_issued' => $totalIssuedThisMonth
        ];
    });
    
    $topIssuedItems = $itemSummaries->filter(function ($summary) {
        return $summary['total_issued'] > 0;
    })->sortByDesc('total_issued')->take(10);
    
    $itemNames = [];
    $itemIssued = [];
    
    foreach ($topIssuedItems as $summary) {
        $itemNames[] = $summary['item']->name;
        $itemIssued[] = $summary['total_issued'];
    }
    

    // 9. Prepare all-time top issued items
    $allItems = ItemModel::with(['transacts.transactionDetail'])->get();

    $top10IssuedItems = $allItems->map(function ($item) {
        // Only sum transactions where status_id == 2
        $totalIssuedQty = $item->transacts
            ->where('status_id', 2)
            ->sum(function ($transaction) {
                return $transaction->transactionDetail->sum('request_quantity');
            });
    
        return [
            'item' => $item,
            'total_issued_qty' => $totalIssuedQty
        ];
    })->filter(function ($data) {
        return $data['total_issued_qty'] > 0;
    })->sortByDesc('total_issued_qty')->take(10);
    
    $topItemsNames = [];
    $topItemsIssuedQty = [];
    
    foreach ($top10IssuedItems as $data) {
        $topItemsNames[] = $data['item']->name;
        $topItemsIssuedQty[] = $data['total_issued_qty'];
    }
    $items = ItemModel::with(['inventory', 'transacts.transactionDetail'])->get();
    $itemsWithTransactionSums = $items->map(function ($item) {
        $totalTransactionSum = $item->transacts
            ->where('status_id', 2)
            ->sum(function ($transact) {
                return $transact->transactionDetail ? $transact->transactionDetail->request_quantity : 0;
            });

        return [
            'item' => $item,
            'total_transaction_sum' => $totalTransactionSum
        ];
    });

    // 2. Compute total current stock from inventory
    $totalInventoryStock = $items->sum(function ($item) {
        return $item->inventory->quantity ?? 0;
    });

    // 3. Compute total issued quantity from transactions with status_id == 2
    $totalIssuedQuantity = $itemsWithTransactionSums->sum(function ($itemWithSum) {
        return $itemWithSum['total_transaction_sum'];
    });

    // 4. Compute total remaining stock (current stock - issued data)
    $totalRemainingInventory = $totalInventoryStock - $totalIssuedQuantity;

    // 5. Prepare data for the chart (passing only relevant data)
    $chartData = [
        'labels' => ['Issued Quantity', 'Remaining Inventory'],
        'issuedQuantity' => $totalIssuedQuantity,
        'remainingInventory' => $totalRemainingInventory,
    ];
    // 10. Return all data to the chart view
    return view('admin.charts.chart', compact(
        'labels1',
        'data1',
        'month',
        'totalDelivered',
        'completedTransactionsThisMonth',
        'chartData',
        'chartDataMonthly'
    ), [
        'itemNames' => $itemNames,
        'itemIssued' => $itemIssued,
        'topItemsNames' => $topItemsNames,
        'topItemsIssuedQty' => $topItemsIssuedQty
    ]);
}

}
