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
use Carbon\Carbon;
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
                    
                    $month = $request->get('month');
                    $year = Carbon::now('Asia/Manila')->year;  
                    $date = Carbon::createFromFormat('Y-F', "$year-$month");   
                    $subDate = Carbon::createFromFormat('Y-F', "$year-$month");   
                    $currentDate = $date->endOfMonth();
                    $subMonth = $subDate->subMonths(1);
                    $modifiedDate = $subMonth->endOfMonth(); 
                    $formattedDate = $modifiedDate->format('m/d/Y');
                    $formattedCurrentDate =$currentDate->format('m/d/Y');

                    $inventories = InventoryModel::all();
                    $capsMonth = strtoupper($month);
                    $data = [
                        'title' => 'INVENTORY REPORT OF SUPPLIES FOR THE MONTH OF ' . $capsMonth,
                        'items' => $items,
                        'inventories' => $inventories,
                        'formattedDate' => $formattedDate,
                        'formattedCurrentDate' => $formattedCurrentDate
                    ];
    
                    $pdf = PDF::loadView('admin.pdf.monthly-report', $data)
                        ->setPaper('legal', 'landscape')
                        ->setOptions([
                            'isHtml5ParserEnabled' => true,
                            'isRemoteEnabled' => true,
                            'defaultFont' => 'sans-serif',
                            'margin-top' => 10,       // Top margin (in points)
                            'margin-right' => 20,     // Right margin (in points)
                            'margin-bottom' => 10,    // Bottom margin (in points)
                            'margin-left' => 20,      // Left margin (in points)
                        ]);
                    return $pdf->download(time() . '.pdf');
            }
        }
    }
}
