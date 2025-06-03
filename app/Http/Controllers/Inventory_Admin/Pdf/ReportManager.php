<?php

namespace App\Http\Controllers\Inventory_Admin\Pdf;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\InventoryModel;
use App\Models\ItemModel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\ClientModel;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\ReportModel;
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
                        Log::info('Total received in selected month: ' . $item->total_received_in_selected_month);

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
                    $preparedBy = AdminModel::where('id', $request->get('prepared'))->first();
                    $logoPh = $this->getCompressedBase64Image('assets/images/LOGO-PH.png', 'png');
                    $logoWebp = $this->getCompressedBase64Image('assets/images/LOGO.webp', 'webp');
                    $generatedBy = AdminModel::where('client_id', session()->get('loggedInInventoryAdmin')['id'])->first();
                    $data = [
                        'title' => "MONTHLY INVENTORY REPORT",
                        'itemsPart1' => $itemsPart1,
                        'itemsPart2' => $itemsPart2,
                        'inventories' => $inventories,
                        'formattedSubDate' => $formattedSubDate,
                        'formattedCurrentDate' => $formattedCurrentDate,
                        'formatLegalCurrentDate' => $formatLegalCurrentDate,
                        'client' => $client,
                        'now' => $now,
                        'formattedDateNow' => $formattedDateNow,
                        'preparedBy' => $preparedBy,
                        'logo' => $logoWebp,
                        'logoPh' => $logoPh,
                        'generatedBy' => $generatedBy
                    ];
                    $now = now()->setTimezone('Asia/Manila')->format('F d, Y h:i A');

                    // Generate a unique timestamp (this will give a number like 1745391496 based on current time)
                    $uniqueId = time();  // Unix timestamp, e.g., 1745391496

                    // Create the filename with the unique ID, report name, and month/year
                    $filename = $uniqueId . '_inventory-report-' . date('F-Y') . '.pdf';
                    $filePath = public_path('pdf-reports/' . $filename);

                    // Generate PDF
                    $pdf = PDF::loadView('admin.pdf.monthly-report', $data, compact('now'))
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
                    $canvas->page_text(270, 770, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 8, [0, 0, 0]);

                    // Ensure directory exists
                    if (!file_exists(public_path('reports'))) {
                        mkdir(public_path('reports'), 0755, true);
                    }

                    // Save to public/reports/
                    file_put_contents($filePath, $pdf->output());

                    // Save to database
                    $report = new ReportModel();
                    $report->admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                    $report->report_type = 'Monthly';
                    $report->control_number = $this->generateControlNumber();
                    $report->report_file = $filename;
                    $report->save();

                    // Return the PDF stream
                    return $pdf->stream($filename);
                    }
                break;
                case "Quarterly":
                $validated = Validator::make($request->all(), [
                    'quarterly' => 'required',
                    'prepared' => 'required',
                    'selectedYear' => 'required'
                ]);

                if ($validated->fails()) {
                    return response()->json(['status' => 400, 'message' => $validated->errors()]);
                }

                $selectedQuarter = $request->input('quarterly');
                $selectedYear = $request->input('selectedYear');
                $year = (int)$selectedYear;

                $quarters = $this->getQuarterMapping();

                if (!array_key_exists($selectedQuarter, $quarters)) {
                    return response()->json(['error' => 'Invalid quarter selected'], 400);
                }

                $months = $quarters[$selectedQuarter];
                $monthToInt = $this->getMonthToInt();
                $monthInts = array_map(fn($month) => $monthToInt[$month], $months);

                // Load items based on category
                $itemsPart1 = $this->loadItemsBySubCategory(1, $monthInts[0], $year);
                $itemsPart2 = $this->loadItemsBySubCategory(2, $monthInts[0], $year);

                // Calculate metrics
                $this->calculateItemMetrics($itemsPart1, $monthInts, $year);
                $this->calculateItemMetrics($itemsPart2, $monthInts, $year);

                // Date Ranges
                $firstMonth = $monthInts[0];
                $lastMonth = $monthInts[2];

                $finalSubMonth = Carbon::create($year, $firstMonth)->subMonth()->endOfMonth()->format('m/d/Y');
                $finalMonth = Carbon::create($year, $lastMonth)->endOfMonth()->format('m/d/Y');
                $endOfMonthFormatted = Carbon::create($year, $lastMonth)->endOfMonth()->format('F d, Y');
                $now = now('Asia/Manila')->format('F j, Y h:i A');

                $preparedBy = AdminModel::find($request->prepared);
                $generatedBy = AdminModel::where('client_id', session()->get('loggedInInventoryAdmin')['id'])->first();

                $data = [
                    'title' => "PROVINCIAL HUMAN RESOURCE MANAGEMENT OFFICE",
                    'sub_title' => "INVENTORY QUARTERLY REPORT",
                    'itemsPart1' => $itemsPart1,
                    'itemsPart2' => $itemsPart2,
                    'explodeQuarters' => [$selectedQuarter],
                    'formatFinalSubMonth' => $finalSubMonth,
                    'formatFinalMonth' => $finalMonth,
                    'quarters' => $quarters,
                    'now' => $now,
                    'monthAbbreviations' => $this->getMonthAbbreviations(),
                    'endOfMonthFormatted' => $endOfMonthFormatted,
                    'preparedBy' => $preparedBy,
                    'getMonth' => $months[0],
                    'logo' => $this->getCompressedBase64Image('assets/images/LOGO.webp', 'webp'),
                    'logoPh' => $this->getCompressedBase64Image('assets/images/LOGO-PH.png', 'png'),
                    'generatedBy' => $generatedBy
                ];

                // Generate PDF
                $filename = $this->generatePdfFileName();
                $filePath = public_path('pdf-reports/' . $filename);

                $pdf = PDF::loadView('admin.pdf.quarterly-report', $data)
                    ->setPaper('legal', 'landscape')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                        'defaultFont' => 'sans-serif',
                        'isPhpEnabled' => true
                    ]);

                $pdf->getCanvas()->page_text(270, 770, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 8, [0, 0, 0]);

                if (!file_exists(public_path('pdf-reports'))) {
                    mkdir(public_path('pdf-reports'), 0755, true);
                }

                file_put_contents($filePath, $pdf->output());

                ReportModel::create([
                    'admin_id' => session()->get('loggedInInventoryAdmin')['admin_id'],
                    'report_type' => 'Quarterly',
                    'control_number' => $this->generateControlNumber(),
                    'report_file' => $filename,
                ]);

                return $pdf->stream($filename);
                break;
            }
        }
    }
    private function getCompressedBase64Image($relativePath, $mimeType = 'png', $width = 300){
    $fullPath = public_path($relativePath);

    if (!file_exists($fullPath)) {
        return '';
    }

    $image = Image::make($fullPath)
        ->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })
        ->encode($mimeType, 75); // 75% quality

    return 'data:image/' . $mimeType . ';base64,' . base64_encode($image);
    }
    private function generateControlNumber() {
        $currentYearAndMonth = Carbon::now()->format('Y-m');
        $controlNumber = ReportModel::whereYear('created_at', Carbon::now()->year)
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->orderBy('control_number', 'desc')
                                ->pluck('control_number')
                                ->first();
    
        if (!$controlNumber) {
            return $currentYearAndMonth . '-00001';
        }
    
        $numberPart = intval(substr($controlNumber, -5)) + 1; 
        $paddedNumber = str_pad($numberPart, 5, '0', STR_PAD_LEFT);
    
        return $currentYearAndMonth . '-' . $paddedNumber;
    }  
    private function getQuarterMapping() {
        return [
            '1-2-3' => ['January', 'February', 'March'],
            '4-5-6' => ['April', 'May', 'June'],
            '7-8-9' => ['July', 'August', 'September'],
            '10-11-12' => ['October', 'November', 'December'],
        ];
    }

    private function getMonthToInt() {
        return [
            'January' => 1, 'February' => 2, 'March' => 3,
            'April' => 4, 'May' => 5, 'June' => 6,
            'July' => 7, 'August' => 8, 'September' => 9,
            'October' => 10, 'November' => 11, 'December' => 12,
        ];
    }

    private function getMonthAbbreviations() {
        return [
            'January' => 'Jan', 'February' => 'Feb', 'March' => 'Mar',
            'April' => 'Apr', 'May' => 'May', 'June' => 'Jun',
            'July' => 'Jul', 'August' => 'Aug', 'September' => 'Sep',
            'October' => 'Oct', 'November' => 'Nov', 'December' => 'Dec',
        ];
    }

    private function loadItemsBySubCategory($subCategoryId, $firstMonthNumeric, $year) {
        return ItemModel::with([
            'receivesUpToMonth' => fn($q) => $q->where('received_month', '<', $firstMonthNumeric)->where('received_year', '<=', $year),
            'receivesInSelectedMonth' => fn($q) => $q->where('received_month', '=', $firstMonthNumeric)->where('received_year', '=', $year),
            'requestedUpToMonth' => fn($q) => $q->where('request_month', '=', $firstMonthNumeric)->where('request_year', '=', $year),
            'transacts.TransactionDetail',
            'category.subCategory'
        ])->whereHas('category.subCategory', fn($q) => $q->where('id', $subCategoryId))->get();
    }

    private function calculateItemMetrics($items, $monthInts, $year) {
        foreach ($items as $item) {
            $firstMonth = $monthInts[0];
            $secondMonth = $monthInts[1];
            $thirdMonth = $monthInts[2];

            $item->total_received_quantity = $item->receivesUpToMonth()
                ->where('delivery_type', 'Receipt for Stock')
                ->where(fn($q) => $q->where('received_year', '<', $year)
                    ->orWhere(fn($q) => $q->where('received_year', $year)->where('received_month', '<', $firstMonth)))
                ->sum('received_quantity');

            $item->total_transactions = $item->transacts
                ->filter(fn($t) => $t->remark === 'Completed')
                ->sum(function ($t) use ($firstMonth, $year) {
                    $d = $t->transactionDetail;
                    return $d && $d->request_year <= $year && $d->request_month < $firstMonth ? $d->request_quantity : 0;
                });

            $item->remaining_quantity = $item->total_received_quantity - $item->total_transactions;

            $item->total_transactions_first_month = $item->transactsSelectedQuartersFirstMonth()
                ->where('request_year', '<=', $year)
                ->where('request_month', $firstMonth)
                ->whereHas('transacts', fn($q) => $q->where('remark', 'Completed'))
                ->sum('request_quantity');

            $item->total_received_in_selected_quarter = $item->receives()
                ->where('delivery_type', 'Receipt for Stock')
                ->where('received_year', '<=', $year)
                ->whereIn('received_month', $monthInts)
                ->sum('received_quantity');

            $item->total_transactions_second_month = $item->transactsSelectedQuartersSecondMonth()
                ->where('request_year', '<=', $year)
                ->where('request_month', $secondMonth)
                ->whereHas('transacts', fn($q) => $q->where('remark', 'Completed'))
                ->sum('request_quantity');

            $item->total_transactions_third_month = $item->transactsSelectedQuartersThirdMonth()
                ->where('request_year', '<=', $year)
                ->where('request_month', $thirdMonth)
                ->whereHas('transacts', fn($q) => $q->where('remark', 'Completed'))
                ->sum('request_quantity');
        }
    }
    private function generatePdfFileName() {
        return time() . '_inventory-report-' . now()->format('F-Y') . '.pdf';
    }
}