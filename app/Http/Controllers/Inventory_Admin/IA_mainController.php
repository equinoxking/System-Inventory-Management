<?php

namespace App\Http\Controllers\Inventory_Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryModel;
use App\Models\UnitModel;
use App\Models\ClientModel;
use App\Models\ReceiveModel;
use App\Models\InventoryModel;
use App\Models\TransactionModel;
use App\Models\ItemModel;
use App\Models\NotificationModel;
use App\Models\TransactionDetailModel;

class IA_mainController extends Controller
{
    public function goToDashboard(){
        $clients = ClientModel::count();
        $transaction = TransactionModel::count();
        $receive = ReceiveModel::count();
        $items = ItemModel::all();
        $itemCount = ItemModel::count();
        $counts = TransactionModel::selectRaw('remark, COUNT(*) as count')
            ->groupBy('remark')
            ->whereIn('remark', ['For Review', 'For Release', 'Completed', 'Rejected', 'Canceled'])
            ->get();

        // Prepare the data for the chart
        $labels = ['For Review',  'For Release', 'Completed', 'Rejected', 'Canceled'];
        $data = [];

        foreach ($labels as $label) {
            // Get the count for each category
            $count = $counts->firstWhere('remark', $label);
            $data[] = $count ? $count->count : 0;
        }
        $categories = CategoryModel::all();
        $transactions = TransactionModel::all();
        $notifications = NotificationModel::all();
        return view('admin.index', [
            'clients' => $clients,
            'transactions' => $transaction,
            'receives' => $receive,
            'items' => $items,
            'itemCount' => $itemCount,
            'transacts' => $transactions,
            'categories' => $categories,
            'notifications' => $notifications
        ], compact('data', 'labels'));
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
