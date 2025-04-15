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
                    $validatorMonthly = Validator::make($request->all(), [
                        'month' => 'required',
                        'conducted' => 'required',
                        'prepared' => 'required',
                        'monthlySelectedYear' => 'required'
                    ]);
                    if ($validatorMonthly->fails()) {
                        return response()->json([
                            'status' => 400,
                            'message' => $validatorMonthly->errors()
                        ]);
                    } else {
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
                        ->where('delivery_type', 'Receipt for Stock')
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
                    
                            if ($transact->status_id == 2 && $transact->remark === 'Completed') {
                                if ($transactionDetail->request_year <= $year && $transactionDetail->request_month < $month) {
                                    return $transactionDetail->request_quantity;
                                }
                            }
                        }
                        return 0;
                    });                 
                    $item->remaining_quantity = $item->total_received_quantity - $item->total_transactions;
                    $item->total_received_in_selected_month = $item->receives()
                        ->where('delivery_type', 'Receipt for Stock')
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
                    $preparedBy = ClientModel::where('id', $request->get('prepared'))->first();
                    $conductedBy = ClientModel::where('id', $request->get('conducted'))->first();

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
                        'formattedDateNow' => $formattedDateNow,
                        'conductedBy' => $conductedBy,
                        'preparedBy' => $preparedBy
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
                        $filename = 'monthly-report-' . date('F-Y') . '.pdf';    
                    return $pdf->stream($filename);
                    }
                break;
                case "Quarterly":
                    $validatorQuarterly = Validator::make($request->all(), [
                        'quarterly' => 'required',
                        'conducted' => 'required',
                        'prepared' => 'required',
                        'selectedYear' => 'required'
                    ]);
                    if ($validatorQuarterly->fails()) {
                        return response()->json([
                            'status' => 400,
                            'message' => $validatorQuarterly->errors()
                        ]);
                    } else {
                        $selectedQuarter = $request->input('quarterly'); 
                    $quarters = [
                        '1-2-3' => ['January', 'February', 'March'],
                        '4-5-6' => ['April', 'May', 'June'],
                        '7-8-9' => ['July', 'August', 'September'],
                        '10-11-12' => ['October', 'November', 'December'],
                    ];

                    if (!array_key_exists($selectedQuarter, $quarters)) {
                        return response()->json(['error' => 'Invalid quarter selected'], 400);
                    }

                    $storeQuarter = '';

                    if (isset($quarters[$selectedQuarter])) {
                        $months = $quarters[$selectedQuarter];
                        $year = Carbon::now('Asia/Manila')->year;  
                        $selectedYear = $request->input('selectedYear');

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

                        $getFirstMonth = $quarters[$selectedQuarter][0];
                        $getSecondMonth = $quarters[$selectedQuarter][1];
                        $getThirdMonth = $quarters[$selectedQuarter][2];

                        $numericMonths = array_map(function ($month) use ($monthToInt) {
                            return $monthToInt[$month];
                        }, $months);

                        $numericMonth = $monthToInt[$getFirstMonth];
                        $numericSecondMonth = $monthToInt[$getSecondMonth];
                        $numericThirdMonth = $monthToInt[$getThirdMonth];

                        $firstMonthName = $quarters[$selectedQuarter][0];
                        $firstMonthNumeric = (int) $monthToInt[$firstMonthName];

                        $itemsPart1 = ItemModel::with([
                            'receivesUpToMonth' => function ($query) use ($months, $year) {
                                $query->where('received_month', '<', $months)
                                    ->where('received_year', '<=', $year);
                            },
                            'receivesInSelectedMonth' => function ($query) use ($months, $year) {
                                $query->where('received_month', '=', $months)
                                    ->where('received_year', '=', $year);
                            },
                            'requestedUpToMonth' => function ($query) use ($months, $year) {
                                $query->where('request_month', '=', $months)
                                    ->where('request_year', '=', $year);
                            },
                            'transacts.TransactionDetail',
                            'category.subCategory'
                        ])
                        ->whereHas('category.subCategory', function ($query) {
                            $query->where('id', 1);
                        })
                        ->get();

                        $itemsPart2 = ItemModel::with([
                            'receivesUpToMonth' => function ($query) use ($months, $year) {
                                $query->where('received_month', '<', $months)
                                    ->where('received_year', '<=', $year);
                            },
                            'receivesInSelectedMonth' => function ($query) use ($months, $year) {
                                $query->where('received_month', '=', $months)
                                    ->where('received_year', '=', $year);
                            },
                            'requestedUpToMonth' => function ($query) use ($months, $year) {
                                $query->where('request_month', '=', $months)
                                    ->where('request_year', '=', $year);
                            },
                            'transacts.TransactionDetail',
                            'category.subCategory'
                        ])
                        ->whereHas('category.subCategory', function ($query) {
                            $query->where('id', 2);
                        })
                        ->get();

                        $itemsPart1->each(function ($item) use ($firstMonthNumeric, $year) {
                            $item->total_received_quantity = $item->receivesUpToMonth()
                                ->where('delivery_type', 'Receipt for Stock')
                                ->where(function ($query) use ($firstMonthNumeric, $year) {
                                    $query->where('received_year', '<', $year)
                                        ->orWhere(function ($query) use ($firstMonthNumeric, $year) {
                                            $query->where('received_year', $year)
                                                ->where('received_month', '<', $firstMonthNumeric);
                                        });
                                })
                                ->sum('received_quantity');

                            $item->total_transactions = $item->transacts
                                ->filter(function ($transact) {
                                    return $transact->remark === 'Completed';
                                })
                                ->sum(function ($transact) use ($firstMonthNumeric, $year) {
                                    if ($transact->transactionDetail) {
                                        $transactionDetail = $transact->transactionDetail;
                                        if ($transactionDetail->request_year <= $year && $transactionDetail->request_month < $firstMonthNumeric) {
                                            return $transactionDetail->request_quantity;
                                        }
                                    }
                                    return 0;
                                });

                            $item->remaining_quantity = $item->total_received_quantity - $item->total_transactions;

                            $item->total_transactions_first_month = $item->transactsSelectedQuartersFirstMonth()
                                ->where('request_year', '<=', $year)
                                ->where('request_month', $firstMonthNumeric)
                                ->whereHas('transacts', function ($query) {
                                    $query->where('remark', 'Completed'); 
                                })
                                ->sum('request_quantity');
                        });

                        $itemsPart1->each(function ($item) use ($numericMonths, $year) {
                            $item->total_received_in_selected_quarter = $item->receives()
                                ->where('delivery_type', 'Receipt for Stock')
                                ->where('received_year', '<=', $year)
                                ->whereIn('received_month', $numericMonths)
                                ->sum('received_quantity');
                        });

                        $itemsPart1->each(function ($item) use ($numericSecondMonth, $year) {
                            $item->total_transactions_second_month = $item->transactsSelectedQuartersSecondMonth()
                                ->where('request_year', '<=', $year)
                                ->where('request_month', $numericSecondMonth)
                                ->whereHas('transacts', function ($query) {
                                    $query->where('remark', 'Completed'); 
                                })
                                ->sum('request_quantity');
                        });

                        $itemsPart1->each(function ($item) use ($numericThirdMonth, $year) {
                            $item->total_transactions_third_month = $item->transactsSelectedQuartersThirdMonth()
                                ->where('request_year', '<=', $year)
                                ->where('request_month', $numericThirdMonth)
                                ->whereHas('transacts', function ($query) {
                                    $query->where('remark', 'Completed'); 
                                })
                                ->sum('request_quantity');
                        });
                        //Part 2
                        $itemsPart2->each(function ($item) use ($firstMonthNumeric, $year) {
                            $item->total_received_quantity = $item->receivesUpToMonth()
                                ->where('delivery_type', 'Receipt for Stock')
                                ->where(function ($query) use ($firstMonthNumeric, $year) {
                                    $query->where('received_year', '<', $year)
                                        ->orWhere(function ($query) use ($firstMonthNumeric, $year) {
                                            $query->where('received_year', $year)
                                                ->where('received_month', '<', $firstMonthNumeric);
                                        });
                                })
                                ->sum('received_quantity');

                            $item->total_transactions = $item->transacts
                                ->filter(function ($transact) {
                                    return $transact->remark === 'Completed';
                                })
                                ->sum(function ($transact) use ($firstMonthNumeric, $year) {
                                    if ($transact->transactionDetail) {
                                        $transactionDetail = $transact->transactionDetail;
                                        if ($transactionDetail->request_year <= $year && $transactionDetail->request_month < $firstMonthNumeric) {
                                            return $transactionDetail->request_quantity;
                                        }
                                    }
                                    return 0;
                                });

                            $item->remaining_quantity = $item->total_received_quantity - $item->total_transactions;

                            $item->total_transactions_first_month = $item->transactsSelectedQuartersFirstMonth()
                                ->where('request_year', '<=', $year)
                                ->where('request_month', $firstMonthNumeric)
                                ->whereHas('transacts', function ($query) {
                                    $query->where('remark', 'Completed'); 
                                })
                                ->sum('request_quantity');
                        });

                        $itemsPart2->each(function ($item) use ($numericMonths, $year) {
                            $item->total_received_in_selected_quarter = $item->receives()
                                ->where('delivery_type', 'Receipt for Stock')
                                ->where('received_year', '<=', $year)
                                ->whereIn('received_month', $numericMonths)
                                ->sum('received_quantity');
                        });

                        $itemsPart2->each(function ($item) use ($numericSecondMonth, $year) {
                            $item->total_transactions_second_month = $item->transactsSelectedQuartersSecondMonth()
                                ->where('request_year', '<=', $year)
                                ->where('request_month', $numericSecondMonth)
                                ->whereHas('transacts', function ($query) {
                                    $query->where('remark', 'Completed'); 
                                })
                                ->sum('request_quantity');
                        });

                        $itemsPart2->each(function ($item) use ($numericThirdMonth, $year) {
                            $item->total_transactions_third_month = $item->transactsSelectedQuartersThirdMonth()
                                ->where('request_year', '<=', $year)
                                ->where('request_month', $numericThirdMonth)
                                ->whereHas('transacts', function ($query) {
                                    $query->where('remark', 'Completed'); 
                                })
                                ->sum('request_quantity');
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
                        $now = Carbon::now('Asia/Manila')->format('F j, Y h:i A');
                        $preparedBy = ClientModel::where('id', $request->get('prepared'))->first();
                        $conductedBy = ClientModel::where('id', $request->get('conducted'))->first();
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
                        'now' => $now,
                        'monthAbbreviations' => $monthAbbreviations,
                        'endOfMonthFormatted' => $endOfMonthFormatted,
                        'conductedBy' => $conductedBy,
                        'preparedBy' => $preparedBy,
                        'getMonth' => $getFirstMonth
                        
                    ];
                    $pdf = PDF::loadView('admin.pdf.quarterly-report', $data)
                    ->setPaper('legal', 'landscape')
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
                    }
                break;
            }
        }
    }
}