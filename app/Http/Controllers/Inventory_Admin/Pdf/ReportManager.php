<?php

namespace App\Http\Controllers\Inventory_Admin\Pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\InventoryModel;
use App\Models\ItemModel;
use App\Models\ReceiveModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReportManager extends Controller
{
    public function generateReport(Request $request){
        $validator = Validator::make($request->all(), [
            'period' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $response = $request->get('period');
            switch ($response) {
                case 'Monthly':
                    $month = $request->get('month');
                    $items = ItemModel::with([
                        'receives' => function ($query) use ($month) {
                            $query->where('received_month', $month);
                        },
                        'inventory.unit'
                    ])->get();

                    
                    $inventories = InventoryModel::all();
                    $data = [
                        'title' => 'Monthly Inventory Report',
                        'items' => $items,
                        'inventories' => $inventories
                    ];
    
                    $pdf = PDF::loadView('admin.pdf.monthly-report', $data)
                        ->setPaper('legal', 'landscape')
                        ->setOptions([
                            'isHtml5ParserEnabled' => true,
                            'isRemoteEnabled' => true,
                            'defaultFont' => 'sans-serif',
                        ]);
                    return $pdf->download(time() . '.pdf');
            }
        }
    }
}
