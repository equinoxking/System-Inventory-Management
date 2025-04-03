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
use Error;
use Illuminate\Support\Facades\Log;
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
                    $year = $request->get('monthlySelectedYear');
                    $itemsPart1 = ItemModel::with([
                        'receivesUpToMonth' => function ($query) use ($month, $year) {
                            $query->where('received_month', '<', $month)
                                ->where('received_year', '<=', $year);
                        },
                        // Receives exactly in the selected month
                        'receivesInSelectedMonth' => function ($query) use ($month, $year) {
                            $query->where('received_month', '=', $month)
                                ->where('received_year', '=', $year);
                        },
                        'requestedUpToMonth' => function ($query) use ($month, $year) {
                            $query->where('request_month', '=', $month)
                                ->where('request_year', '=', $year);
                        },
                        'transacts.TransactionDetail',
                        'category.subCategory'
                    ])
                    ->whereHas('category.subCategory', function ($query) {
                        $query->where('id', 1);  // Filter where sub_category_id is 1
                    })
                    ->get();
                    $itemsPart2 = ItemModel::with([
                        'receivesUpToMonth' => function ($query) use ($month, $year) {
                            $query->where('received_month', '<', $month)
                                ->where('received_year', '<=', $year);
                        },
                        // Receives exactly in the selected month
                        'receivesInSelectedMonth' => function ($query) use ($month, $year) {
                            $query->where('received_month', '=', $month)
                                ->where('received_year', '=', $year);
                        },
                        'requestedUpToMonth' => function ($query) use ($month, $year) {
                            $query->where('request_month', '=', $month)
                                ->where('request_year', '=', $year);
                        },
                        'transacts.TransactionDetail',
                        'category.subCategory'
                    ])
                    ->whereHas('category.subCategory', function ($query) {
                        $query->where('id', 2);  // Filter where sub_category_id is 1
                    })
                    ->get();
                    $itemsPart1->each(function ($item) use ($month, $year) {
                    $item->total_received_quantity = $item->receivesUpToMonth()
                    ->where(function ($query) use ($month, $year) {
                        $query->where('received_year', '<', $year)
                            ->orWhere(function ($query) use ($month, $year) {
                                $query->where('received_year', '<=', $year)
                                        ->where('received_month', '<', $month); 
                            });
                    })
                    ->sum('received_quantity');
                    $item->total_transactions = $item->transacts->sum(function ($transact) use ($month, $year) {
                        if ($transact->transactionDetail) {
                            $transactionDetail = $transact->transactionDetail;
                            if($transact->status_id == 2 && $transact->remark == 'Completed'){
                                if ($transactionDetail->request_year <= $year && $transactionDetail->request_month < $month) {
                                    return $transactionDetail->request_quantity;
                                }
                            }
                        }
                        return 0; 
                    });
                    $item->remaining_quantity = $item->total_received_quantity - $item->total_transactions;
                    $item->total_received_in_selected_month = $item->receives()
                        ->where('received_year', '=', $year)
                        ->where('received_month', '=', $month)
                        ->sum('received_quantity');
                    $item->total_transactions_in_selected_month = $item->transacts
                    ->where('status_id', 2)
                    ->where('remark', 'Completed')
                    ->sum(function ($transact) use ($year, $month) {
                        if ($transact->transactionDetail) {
                            $transactionDetail = $transact->transactionDetail;
                
                            if ($transactionDetail->request_year == $year && $transactionDetail->request_month == $month) {
                                return $transactionDetail->request_quantity;
                            }
                        }
                        return 0;
                    });
                    });
                    $itemsPart2->each(function ($item) use ($month, $year) {
                        $item->total_received_quantity = $item->receivesUpToMonth()
                        ->where(function ($query) use ($month, $year) {
                            $query->where('received_year', '<', $year)
                                ->orWhere(function ($query) use ($month, $year) {
                                    $query->where('received_year', '<=', $year)
                                            ->where('received_month', '<', $month); 
                                });
                        })
                        ->sum('received_quantity');
                        $item->total_transactions = $item->transacts->sum(function ($transact) use ($month, $year) {
                            if ($transact->transactionDetail) {
                                $transactionDetail = $transact->transactionDetail;
                                
                                if ($transactionDetail->request_year <= $year && $transactionDetail->request_month < $month) {
                                    return $transactionDetail->request_quantity;
                                }
                            }
                            return 0; 
                        });
                        $item->remaining_quantity = $item->total_received_quantity - $item->total_transactions;
                        $item->total_received_in_selected_month = $item->receives()
                            ->where('received_year', '=', $year)
                            ->where('received_month', '=', $month)
                            ->sum('received_quantity');
                        $item->total_transactions_in_selected_month = $item->transacts
                        ->where('status_id', 2)
                        ->where('remark', 'Completed')
                        ->sum(function ($transact) use ($year, $month) {
                            if ($transact->transactionDetail) {
                                $transactionDetail = $transact->transactionDetail;
                    
                                if ($transactionDetail->request_year == $year && $transactionDetail->request_month == $month) {
                                    return $transactionDetail->request_quantity;
                                }
                            }
                            return 0;
                        });
                    });

                    $subDate = new \DateTime("$year-$month-01");  // Using the 1st of the month to start
                    $subDate->modify('last day of previous month');  // Move to the last day of the previous month
                    $formattedSubDate = $subDate->format('m/d/Y');  // Format it as m/d/Y


                    // Get the last day of the current month
                    $currentDate = new \DateTime("$year-$month-01");
                    $currentDate->modify('last day of this month');  // Move to the last day of the current month
                    $formattedCurrentDate = $currentDate->format('m/d/Y');  // Format it as m/d/Y



                    // Get the full legal format for the current month
                    $formatLegalCurrentDate = $currentDate->format('F d, Y');
       

                    // Month to Name Mapping
                    $monthToName = [
                        1 => 'January',
                        2 => 'February',
                        3 => 'March',
                        4 => 'April',
                        5 => 'May',
                        6 => 'June',
                        7 => 'July',
                        8 => 'August',
                        9 => 'September',
                        10 => 'October',
                        11 => 'November',
                        12 => 'December',
                    ];

                    // Ensure the $month is valid and exists in the array
                    $monthName = $monthToName[$month] ?? 'Invalid month';

                    // Format the month name into a string format for the month
                    $formattedDateNow = $monthName;

                    $inventories = InventoryModel::all();
                    $sessionLogin = session()->get('loggedInInventoryAdmin')['id'];
                    $client = ClientModel::where('id', $sessionLogin)->first();
                    $now = Carbon::now('Asia/Manila')->format('F j, Y h:i A');
                    $data = [
                        'title' => 'INVENTORY REPORT OF SUPPLIES FOR THE MONTH OF ' . strtoupper($formattedDateNow) ,
                        'itemsPart1' => $itemsPart1,
                        'itemsPart2' => $itemsPart2,
                        'inventories' => $inventories,
                        'formattedSubDate' => $formattedSubDate,
                        'formattedCurrentDate' => $formattedCurrentDate,
                        'formatLegalCurrentDate' => $formatLegalCurrentDate,
                        'client' => $client,
                        'now' => $now,
                        'formattedDateNow' => $formattedDateNow
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
                    $quarters = [
                        '1-2-3' => ['January', 'February', 'March'],
                        '4-5-6' => ['April', 'May', 'June'],
                        '7-8-9' => ['July', 'August', 'September'],
                        '10-11-12' => ['October', 'November', 'December'],
                    ];
                    if (!array_key_exists($selectedQuarter, $quarters)) {
                        // Handle error: Invalid quarter input
                        return response()->json(['error' => 'Invalid quarter selected'], 400);
                    }
                    $storeQuarter = '';
                    if (isset($quarters[$selectedQuarter])) {
                        $months = $quarters[$selectedQuarter];
                        $year = Carbon::now('Asia/Manila')->year;  
                        $selectedYear = $request->input('selectedYear');
                        $itemsPart1 = ItemModel::with(['receives' => function($query) use ($months, $selectedYear) {
                            $query->whereIn('received_month', $months)
                            ->where('received_year', $selectedYear); 
                        }])
                        ->whereHas('category.subCategory', function ($query) {
                            $query->where('id', 1);  // Filter where sub_category_id is 1
                        })
                        ->get();
                        $itemsPart2 = ItemModel::with(['receives' => function($query) use ($months, $selectedYear) {
                            $query->whereIn('received_month', $months)
                            ->where('received_year', $selectedYear); 
                        }])
                        ->whereHas('category.subCategory', function ($query) {
                            $query->where('id', 2);  // Filter where sub_category_id is 1
                        })
                        ->get();
                        $monthToInt = [
                            'January' => 1,
                            'February' => 2,
                            'March' => 3,
                            'April' => 4,
                            'May' => 5,
                            'June' => 6,
                            'July' => 7,
                            'August' => 8,
                            'September' => 9,
                            'October' => 10,
                            'November' => 11,
                            'December' => 12,
                        ];
                        $getMonth = $quarters[$selectedQuarter][0];
                        $numericMonths = array_map(function ($month) use ($monthToInt) {
                            return $monthToInt[$month];
                        }, $months);
                        $numericMonth = $monthToInt[$getMonth];
                        $firstMonthName = $quarters[$selectedQuarter][0];
                        $firstMonthNumeric = (int) $monthToInt[$firstMonthName];
                        $itemsPart1->each(function ($item) use ($numericMonths, $year) {
                            // Sum the received quantities for the selected quarter and year
                            $item->total_received_quantity = $item->receives()
                                ->where(function ($query) use ($numericMonths, $year) {
                                    $query->where('received_year', '<', $year)  // For years before the selected year, include all months
                                        ->orWhere(function ($query) use ($numericMonths, $year) {
                                            // For the selected year, include all months up to and including the selected month
                                            $query->where('received_year', '=', $year)
                                                ->whereIn('received_month', $numericMonths);  // Use whereIn to filter by multiple months
                                        });
                                })
                            ->sum('received_quantity');
                            });
                        $itemsPart1->each(function ($item) use ($firstMonthNumeric, $year) {
                            $item->total_balances = $item->receives()
                                ->where(function ($query) use ($firstMonthNumeric, $year) {
                                    $query->where('received_year', '<', $year)  // For years before the selected year, include all months
                                        ->orWhere(function ($query) use ($firstMonthNumeric, $year) {
                                            // For the selected year, include all months up to and including the selected month
                                            $query->where('received_year', '=', $year)
                                                    ->where('received_month', '<=', $firstMonthNumeric);
                                        });
                                })
                            ->sum('received_quantity');
                            });
                        $itemsPart2->each(function ($item) use ($numericMonths, $year) {
                            // Sum the received quantities for the selected quarter and year
                            $item->total_received_quantity = $item->receives()
                                ->where(function ($query) use ($numericMonths, $year) {
                                    $query->where('received_year', '<', $year)  // For years before the selected year, include all months
                                        ->orWhere(function ($query) use ($numericMonths, $year) {
                                            // For the selected year, include all months up to and including the selected month
                                            $query->where('received_year', '=', $year)
                                                ->whereIn('received_month', $numericMonths);  // Use whereIn to filter by multiple months
                                        });
                                })
                            ->sum('received_quantity');
                            });
                        $itemsPart2->each(function ($item) use ($firstMonthNumeric, $year) {
                            $item->total_balances = $item->receives()
                                ->where(function ($query) use ($firstMonthNumeric, $year) {
                                    $query->where('received_year', '<', $year)  // For years before the selected year, include all months
                                        ->orWhere(function ($query) use ($firstMonthNumeric, $year) {
                                            // For the selected year, include all months up to and including the selected month
                                            $query->where('received_year', '=', $year)
                                                    ->where('received_month', '<=', $firstMonthNumeric);
                                        });
                                })
                            ->sum('received_quantity');
                            });
                        $monthAbbreviations = [
                            'January' => 'Jan',
                            'February' => 'Feb',
                            'March' => 'Mar',
                            'April' => 'Apr',
                            'May' => 'May',
                            'June' => 'Jun',
                            'July' => 'Jul',
                            'August' => 'Aug',
                            'September' => 'Sep',
                            'October' => 'Oct',
                            'November' => 'Nov',
                            'December' => 'Dec',
                        ];
                        $explodeQuarters = [$selectedQuarter];
                        $subDate = Carbon::createFromFormat('Y-m', "$selectedYear-$numericMonth");   
                        $subMonth = $subDate->subMonths(1);
                        $finalSubMonth = $subMonth->endOfMonth(); 
                        $currentDate = Carbon::createFromFormat('Y-m', "$selectedYear-$numericMonth");  
                        $addMonth = $currentDate->addMonth(2);
                        $finalMonth =  $addMonth->endOfMonth(); 
                        $formatFinalSubMonth = $finalSubMonth->format('m/d/Y');
                        $formatFinalMonth = $finalMonth->format('m/d/Y');
                        $getMonth = $quarters[$selectedQuarter][2];
                        $numericMonthNow = $monthToInt[$getMonth];
                        $endOfMonth = Carbon::createFromFormat('Y-m', "$selectedYear-$numericMonthNow")->endOfMonth();
                        $endOfMonthFormatted = $endOfMonth->format('F d, Y');
                    } else {
                        $items = collect();  
                    }
                    $data = [
                        'title' => "PROVINCIAL HUMAN RESOURCE MANAGEMENT OFFICE",
                        'sub_title' => "SUPPLIES UTILIZATION REPORT",
                        'itemsPart1' => $itemsPart1,
                        'itemsPart2' => $itemsPart2,
                        'explodeQuarters' => $explodeQuarters,
                        'formatFinalSubMonth' => $formatFinalSubMonth,
                        'formatFinalMonth' => $formatFinalMonth,
                        'quarters' => $quarters,
                        'monthAbbreviations' => $monthAbbreviations,
                        'endOfMonthFormatted' => $endOfMonthFormatted
                        
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
// $itemsPart1->each(function ($item) use ($month, $year) {
//     $item->total_received_quantity = $item->transactions()
//         ->where(function ($query) use ($month, $year) {
//             // Filter transactions where the released year and month are before the user input year and month
//             $query->where(function ($query) use ($month, $year) {
//                 // Include transactions with released_year less than the input year (all months before the input year)
//                 $query->where('released_year', '<', $year)
//                       ->orWhere(function ($query) use ($month, $year) {
//                           // Include transactions for the selected year, but only up to the input month
//                           $query->where('released_year', '=', $year)
//                                 ->where('released_month', '<', $month);  // Exclude the input month
//                       });
//             });
//         })
//         ->sum('received_quantity');
// });