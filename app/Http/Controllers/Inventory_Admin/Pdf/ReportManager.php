<?php

namespace App\Http\Controllers\Inventory_Admin\Pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\InventoryModel;
use App\Models\ItemModel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\ClientModel;
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

                    $itemsPart1 = ItemModel::with([
                        'receives' => function ($query) use ($month) {
                            $query->where('received_month', $month);
                        },
                        'inventory.unit',
                        'category.subCategory'
                    ])
                    ->whereHas('category.subCategory', function ($query) {
                        $query->where('id', 1);  // Filter where sub_category_id is 1
                    })
                    ->get();
                    $itemsPart2 = ItemModel::with([
                        'receives' => function ($query) use ($month) {
                            $query->where('received_month', $month);
                        },
                        'inventory.unit',
                        'category.subCategory'
                    ])
                    ->whereHas('category.subCategory', function ($query) {
                        $query->where('id', 2);  // Filter where sub_category_id is 1
                    })
                    ->get();
                    // $items = $items->sortBy(function ($item) {
                    //     return $item->category->subCategory ? $item->category->subCategory->id : 999;  // Place subcategories that don't exist last
                    // });
                    $selectedYear = $request->input('monthlySelectedYear');
                    $date = Carbon::createFromFormat('Y-F', "$selectedYear-$month");   
                    $subDate = Carbon::createFromFormat('Y-F', "$selectedYear-$month");   
                    $currentDate = $date->endOfMonth();
                    $subMonth = $subDate->subMonths(1);
                    $modifiedDate = $subMonth->endOfMonth(); 
                    $formattedDate = $modifiedDate->format('m/d/Y');
                    $formattedCurrentDate = $currentDate->format('m/d/Y');
                    $formatLegalCurrentDate = $currentDate->format('F d, Y');

                    $inventories = InventoryModel::all();
                    $capsMonth = strtoupper($month);
                    $sessionLogin = session()->get('loggedInInventoryAdmin')['id'];
                    $client = ClientModel::where('id', $sessionLogin)->first();
                    $now = Carbon::now('Asia/Manila')->format('F j, Y h:i A');
                    $data = [
                        'title' => 'INVENTORY REPORT OF SUPPLIES FOR THE MONTH OF ' . $capsMonth,
                        'itemsPart1' => $itemsPart1,
                        'itemsPart2' => $itemsPart2,
                        'inventories' => $inventories,
                        'formattedDate' => $formattedDate,
                        'formattedCurrentDate' => $formattedCurrentDate,
                        'formatLegalCurrentDate' => $formatLegalCurrentDate,
                        'client' => $client,
                        'now' => $now,
                    ];
    
                    $pdf = PDF::loadView('admin.pdf.monthly-report', $data)
                        ->setPaper('legal', 'landscape')
                        ->setOptions([
                            'isHtml5ParserEnabled' => true,
                            'isRemoteEnabled' => true,
                            'defaultFont' => 'sans-serif',
                            'margin-top' => 10,      
                            'margin-right' => 20,  
                            'margin-bottom' => 10,    
                            'margin-left' => 20,  
                            'isPhpEnabled' => true    
                        ]);
                        $canvas = $pdf->getCanvas();
                        $canvas->page_text(270, 770, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 8, array(0,0,0));
                    return $pdf->stream('monthly-report.pdf');
                break;
                case "Quarterly":
                    $selectedQuarter = $request->input('quarterly'); 
                    $explodeQuarters = explode('-', $selectedQuarter);
                    $quarters = [
                        'Jan-Feb-Mar' => ['January', 'February', 'March'],
                        'Apr-May-Jun' => ['April', 'May', 'June'],
                        'Jul-Aug-Sep' => ['July', 'August', 'September'],
                        'Oct-Nov-Dec' => ['October', 'November', 'December'],
                    ];
                    $storeQuarter = '';
                    if (isset($quarters[$selectedQuarter])) {
                       
                        $months = $quarters[$selectedQuarter];
                        $year = Carbon::now('Asia/Manila')->year;  
                        $selectedYear = $request->input('selectedYear');
                        $items = ItemModel::with(['receives' => function($query) use ($months, $selectedYear) {
                            $query->whereIn('received_month', $months)
                            ->where('received_year', $selectedYear); 
                        }])->get();

                        $getMonth = $quarters[$selectedQuarter][0];
                        $subDate = Carbon::createFromFormat('Y-F', "$selectedYear-$getMonth");   
                        $subMonth = $subDate->subMonths(1);
                        $finalSubMonth = $subMonth->endOfMonth(); 
                        $currentDate = Carbon::createFromFormat('Y-F', "$selectedYear-$getMonth");  
                        $addMonth = $currentDate->addMonth(2);
                        $finalMonth =  $addMonth->endOfMonth(); 
                        $formatFinalSubMonth = $finalSubMonth->format('m/d/Y');
                        $formatFinalMonth = $finalMonth->format('m/d/Y');
                    } else {
                        $items = collect();  
                    }
                    $data = [
                        'items' => $items,
                        'explodeQuarters' => $explodeQuarters,
                        'formatFinalSubMonth' => $formatFinalSubMonth,
                        'formatFinalMonth' => $formatFinalMonth
                        
                    ];
                    $pdf = PDF::loadView('admin.pdf.quarterly-report', $data)
                    ->setPaper('legal', 'portrait')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'margin-top' => 10,      
                        'margin-right' => 20,  
                        'margin-bottom' => 10,    
                        'margin-left' => 20,      
                    ]);
                    return $pdf->download(time() . '.pdf');
                break;
            }
        }
    }
}
