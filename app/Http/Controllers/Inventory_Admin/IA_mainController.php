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
        // $items = ItemModel::all();
        $items = ItemModel::with('transacts.TransactionDetail')->get();

        $itemsWithTransactionSums = $items->map(function ($item) {
            // Sum all request_quantity values in TransactionDetail for each transaction related to this item
            $totalTransactionSum = $item->transacts->sum(function ($transact) {
                // Check if transactionDetail exists and sum the 'request_quantity' for all related transaction details
                return $transact->transactionDetail ? $transact->transactionDetail->request_quantity : 0;
            });
            // Return the item with its total transaction sum
            return [
                'item' => $item,
                'total_transaction_sum' => $totalTransactionSum
            ];
        });
        $itemCount = ItemModel::count();
        $categories = CategoryModel::all();
        $transactions = TransactionModel::all();
        $notifications = NotificationModel::all();
        return view('admin.index', [
            'clients' => $clients,
            'transactions' => $transaction,
            'receives' => $receive,
            'items' => $items,
            'transacts' => $transactions,
            'categories' => $categories,
            'notifications' => $notifications,
            'itemCount' => $itemCount,
            'itemsWithTransactionSums' => $itemsWithTransactionSums
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
