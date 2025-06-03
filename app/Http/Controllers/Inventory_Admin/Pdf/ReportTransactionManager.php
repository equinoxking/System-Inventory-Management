<?php

namespace App\Http\Controllers\Inventory_Admin\Pdf;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\InventoryModel;
use App\Models\TransactionDetailModel;
use App\Models\ItemModel;
use App\Models\TransactionModel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;
use App\Models\ClientModel;
use Intervention\Image\Facades\Image;
use DateTime;
class ReportTransactionManager extends Controller
{
    // public function generateTransactionReport(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'selection' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 400,
    //             'message' => $validator->errors()
    //         ]);
    //     } else {
    //         $selectedOption = $request->get('selection');
    //         switch($selectedOption){
    //             case "All" :
    //                 $transactions = TransactionModel::with(['client', 'item', 'transactionDetail', 'status', 'item.inventory', 'admin', 'adminBy'])
    //                 ->where('remark', 'Completed')
    //                 ->orWhere('remark', 'Denied')
    //                 ->orWhere('remark', 'Canceled')
    //                 ->get();
    //                 $now = Carbon::now('Asia/Manila')->format('F j, Y h:i A');
    //                 $preparedBy = AdminModel::where('id', $request->get('admin'))->first();
    //                 $logoPh = $this->getCompressedBase64Image('assets/images/LOGO-PH.png', 'png');
    //                 $logoWebp = $this->getCompressedBase64Image('assets/images/LOGO.webp', 'webp');
    //                 $generatedBy = AdminModel::where('id', session()->get('loggedInInventoryAdmin')['id'])->first();
    //                 $data = [
    //                     'transactions' => $transactions,
    //                     'preparedBy' => $preparedBy,
    //                     'logo' => $logoWebp,
    //                     'logoPh' => $logoPh,
    //                     'generatedBy' => $generatedBy,
    //                     'selection' => $selectedOption
    //                 ];

    //                 $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
    //                 $user_id = null;
    //                 $activity = "Generated transaction PDF - All Records.";
    //                 (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

    //                 $pdf = PDF::loadView('admin.pdf.all-transactions', $data, compact('now'))
    //                     ->setPaper('legal', 'landscape')
    //                     ->setOptions([
    //                         'isHtml5ParserEnabled' => true,
    //                         'isRemoteEnabled' => true,
    //                         'defaultFont' => 'sans-serif',
    //                         'margin-top' => 10,      
    //                         'margin-right' => 20,  
    //                         'margin-bottom' => 10,    
    //                         'margin-left' => 20,  
    //                         'isPhpEnabled' => true    
    //                     ]);
    //                     $canvas = $pdf->getCanvas();
    //                     $canvas->page_text(270, 770, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 8, array(0,0,0));
    //                     $filename = 'transaction-report-' . date('F-Y') . '.pdf';    
    //                 return $pdf->stream($filename);
    //             break;
    //             case "User" :
    //                 $now = Carbon::now('Asia/Manila')->format('F j, Y h:i A');
    //                 $rawSelection = $request->input('user_selection');
    //                 $selection = trim($rawSelection); // Remove whitespace just in case
    //                 [$type, $id] = explode('-', $selection);
    //                 if ($type === 'user') {
    //                     $transactions = TransactionModel::with(['client', 'item', 'transactionDetail', 'status', 'item.inventory', 'admin', 'adminBy'])
    //                         ->where('remark', 'Completed')
    //                         ->where('user_id', $id)
    //                         ->get();
    //                 } elseif ($type === 'admin') {
    //                     $transactions = TransactionModel::with(['client', 'item', 'transactionDetail', 'status', 'item.inventory', 'admin', 'adminBy'])
    //                         ->where('remark', 'Completed')
    //                         ->where('admin_id', $id)
    //                         ->get();
    //                 } else {
    //                     return back()->with('error', 'Unknown selection type.');
    //                 }
    //                 $transactionOfUser = TransactionModel::with(['client', 'item', 'transactionDetail', 'status', 'item.inventory', 'admin', 'adminBy'])
    //                 ->where('remark', 'Completed')
    //                 ->where('user_id', $id)
    //                 ->first();
    //                 $transactionOfAdmin = TransactionModel::with(['client', 'item', 'transactionDetail', 'status', 'item.inventory', 'admin', 'adminBy'])
    //                 ->where('remark', 'Completed')
    //                 ->where('admin_id', $id)
    //                 ->first();
    //                 $preparedBy = AdminModel::where('id', $request->get('admin'))->first();
                   

    //                // Retrieve the first transaction for the given user_id
    //                 $transaction = TransactionModel::with(['client', 'admin'])->where('user_id', $request->get('user'))->first();

    //                 // Check if a transaction was found
    //                 if ($transaction) {
    //                     // Now you can safely check the properties of the $transaction
    //                     if ($transaction->user_id && $transaction->client) {
    //                         // It's a user/client
    //                         $type = 'User';
    //                         $owner_id = $transaction->client->id;
    //                         $owner_name = $transaction->client->full_name;
    //                     } elseif ($transaction->admin) {
    //                         // It's an admin
    //                         $type = 'Admin';
    //                         $owner_id = $transaction->admin->id;
    //                         $owner_name = $transaction->admin->full_name;
    //                     } else {
    //                         // Handle if neither client nor admin is found
    //                         $type = 'Unknown';
    //                         $owner_id = null;
    //                         $owner_name = 'Unknown';
    //                     }
    //                 } else {
    //                     // Handle the case where no transaction is found
    //                     $type = 'Not Found';
    //                     $owner_id = null;
    //                     $owner_name = 'N/A';
    //                 }

    //                 $logoPh = $this->getCompressedBase64Image('assets/images/LOGO-PH.png', 'png');
    //                 $logoWebp = $this->getCompressedBase64Image('assets/images/LOGO.webp', 'webp');
    //                 $generatedBy = AdminModel::where('id', session()->get('loggedInInventoryAdmin')['id'])->first();

    //                 $data = [
    //                     'transactions' => $transactions,
    //                     'preparedBy' => $preparedBy,
    //                     'logo' => $logoWebp,
    //                     'logoPh' => $logoPh,
    //                     'generatedBy' => $generatedBy,
    //                     'selection' => $selectedOption,
    //                     'transactionOfUser' => $transactionOfUser,
    //                     'transactionOfAdmin' => $transactionOfAdmin
    //                 ];
                    
    //                 $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
    //                 $user_id = null;
    //                 if ($transactionOfUser && $transactionOfUser->client) {
    //                     $owner_name = $transactionOfUser->client->full_name;
    //                     $activity = "Generated transaction PDF of " . $owner_name . ".";
    //                 } elseif ($transactionOfAdmin && $transactionOfAdmin->admin) {
    //                     $owner_name = $transactionOfAdmin->admin->full_name;
    //                     $activity = "Generated transaction PDF of " . $owner_name . ".";
    //                 } else {
    //                     $activity = "Generated transaction PDF."; // fallback in case neither match
    //                 }

    //                 (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

    //                 $pdf = PDF::loadView('admin.pdf.all-transactions', $data, compact('now'))
    //                     ->setPaper('legal', 'landscape')
    //                     ->setOptions([
    //                         'isHtml5ParserEnabled' => true,
    //                         'isRemoteEnabled' => true,
    //                         'defaultFont' => 'sans-serif',
    //                         'margin-top' => 10,      
    //                         'margin-right' => 20,  
    //                         'margin-bottom' => 10,    
    //                         'margin-left' => 20,  
    //                         'isPhpEnabled' => true    
    //                     ]);
    //                     $canvas = $pdf->getCanvas();
    //                     $canvas->page_text(270, 770, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 8, array(0,0,0));
    //                     $owner_name_clean = str_replace(['/', '\\'], '-', $owner_name);

    //                     $filename = 'transaction-report of ' . $owner_name_clean . date('F-Y') . '.pdf';    
    //                 return $pdf->stream($filename);
    //             break;
    //             case "Monthly" :
    //                 $selection = $request->get('selection');
    //                 $year = $request->get('year');
    //                 $month = $request->get('month');
    //                 $dateObj = DateTime::createFromFormat('!m', $month);
    //                 $monthName = $dateObj->format('F');
    //                 $transactions = TransactionModel::with(['client', 'item', 'transactionDetail', 'status', 'item.inventory', 'admin', 'adminBy'])
    //                 ->where('remark', 'Completed')
    //                 ->whereHas('transactionDetail', function($query) use ($year, $month) {
    //                     $query->where('request_year', $year)
    //                             ->where('request_month', $month);
    //                 })
    //                 ->get();
    //                 $now = Carbon::now('Asia/Manila')->format('F j, Y h:i A');
    //                 $preparedBy = AdminModel::where('id', $request->get('admin'))->first();

    //                 $logoPh = $this->getCompressedBase64Image('assets/images/LOGO-PH.png', 'png');
    //                 $logoWebp = $this->getCompressedBase64Image('assets/images/LOGO.webp', 'webp');
    //                 $generatedBy = AdminModel::where('id', session()->get('loggedInInventoryAdmin')['id'])->first();

    //                 $data = [
    //                     'transactions' => $transactions,
    //                     'preparedBy' => $preparedBy,
    //                     'logo' => $logoWebp,
    //                     'logoPh' => $logoPh,
    //                     'generatedBy' => $generatedBy,
    //                     'selection' => $selection
    //                 ];
    //                 $user = TransactionModel::where('user_id', $request->get('user'))->first();

    //                 $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
    //                 $user_id = null;
    //                 $activity = "Generated transaction for " . $monthName . " " . $year . ".";
    //                 (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

    //                 $pdf = PDF::loadView('admin.pdf.all-transactions', $data, compact('now'))
    //                     ->setPaper('legal', 'landscape')
    //                     ->setOptions([
    //                         'isHtml5ParserEnabled' => true,
    //                         'isRemoteEnabled' => true,
    //                         'defaultFont' => 'sans-serif',
    //                         'margin-top' => 10,      
    //                         'margin-right' => 20,  
    //                         'margin-bottom' => 10,    
    //                         'margin-left' => 20,  
    //                         'isPhpEnabled' => true    
    //                     ]);
    //                     $canvas = $pdf->getCanvas();
    //                     $canvas->page_text(270, 770, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 8, array(0,0,0));
    //                     $filename = 'transaction-monthly-report of ' . $month . date('F-Y') . '.pdf';    
    //                 return $pdf->stream($filename);              
    //             break;
    //             default :

    //         }
    //     }
    // }
    public function generateTransactionReport(Request $request){
        $validator = Validator::make($request->all(), [
            'selectOption' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            $selectedOption = $request->get('selectOption');
            switch($selectedOption){
                case 'User' :
                $selectionOption = $request->get('selection');
                if($selectionOption == "All"){
                    $division = null; 
                    $selectionOption = $request->get('selection');
                    $transactions = TransactionModel::with(['client', 'item', 'transactionDetail', 'status', 'item.inventory', 'admin', 'adminBy'])
                        ->where('remark', 'Completed')
                        ->orWhere('remark', 'Denied')
                        ->orWhere('remark', 'Canceled')
                        ->get();
                        $now = Carbon::now('Asia/Manila')->format('F j, Y h:i A');
                        $preparedBy = AdminModel::where('id', $request->get('admin'))->first();
                        $logoPh = $this->getCompressedBase64Image('assets/images/LOGO-PH.png', 'png');
                        $logoWebp = $this->getCompressedBase64Image('assets/images/LOGO.webp', 'webp');
                        $generatedBy = AdminModel::where('client_id', session()->get('loggedInInventoryAdmin')['id'])->first();
                        $data = [
                            'transactions' => $transactions,
                            'preparedBy' => $preparedBy,
                            'logo' => $logoWebp,
                            'logoPh' => $logoPh,
                            'generatedBy' => $generatedBy,
                            'selection' => $selectionOption,
                            'division' => $division
                        ];

                        $admin_id = session()->get('loggedInInventoryAdmin')['admin_id'];
                        $user_id = null;
                        $activity = "Generated transaction PDF - All Records.";
                        (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

                        $pdf = PDF::loadView('admin.pdf.all-transactions', $data, compact('now'))
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
                            $filename = 'transaction-report-' . date('F-Y') . '.pdf';    
                        return $pdf->stream($filename);
                }else{
                $transactions = TransactionModel::with(['client', 'item', 'transactionDetail', 'status', 'item.inventory', 'admin', 'adminBy'])
                    ->where('remark', 'Completed')
                    ->where('user_id', $selectionOption)
                    ->get();
                      $division = null; 
                    $now = Carbon::now('Asia/Manila')->format('F j, Y h:i A');
                    $logoPh = $this->getCompressedBase64Image('assets/images/LOGO-PH.png', 'png');
                    $logoWebp = $this->getCompressedBase64Image('assets/images/LOGO.webp', 'webp');
                    $generatedBy = AdminModel::where('client_id', session()->get('loggedInInventoryAdmin')['id'])->first();
                    $preparedBy = AdminModel::where('id', $request->get('admin'))->first();
                    $client = ClientModel::where('id', $selectionOption)->first();
                    $data = [
                        'transactions' => $transactions,
                        'preparedBy' => $preparedBy,
                        'logo' => $logoWebp,
                        'logoPh' => $logoPh,
                        'generatedBy' => $generatedBy,
                        'selection' => $selectionOption,
                        'client' => $client,
                        'division' => $division
                    ];
                    $pdf = PDF::loadView('admin.pdf.all-transactions', $data, compact('now'))
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
                        $filename = 'transaction-report-' . date('F-Y') . '.pdf';    
                    return $pdf->stream($filename);
                }
               case "Division":
                    $division = $request->get('division');  // Get selected division from request
                    $selectionOption = null;
                    // Eager load relationships, filter by remark 'Completed' AND client's division
                    $transactions = TransactionModel::with(['client', 'item', 'transactionDetail', 'status', 'item.inventory', 'admin', 'adminBy'])
                        ->where('remark', 'Completed')
                        ->whereHas('client', function ($query) use ($division) {
                            $query->where('division', $division);
                        })
                        ->get();
                    $now = Carbon::now('Asia/Manila')->format('F j, Y h:i A');
                    $logoPh = $this->getCompressedBase64Image('assets/images/LOGO-PH.png', 'png');
                    $logoWebp = $this->getCompressedBase64Image('assets/images/LOGO.webp', 'webp');
                    $generatedBy = AdminModel::where('client_id', session()->get('loggedInInventoryAdmin')['id'])->first();
                    $preparedBy = AdminModel::where('id', $request->get('admin'))->first();

                    // You might not have a single client here, but if you want you can pass the division name or leave null
                    $client = ClientModel::where('id', $selectionOption)->first();

                    $data = [
                        'transactions' => $transactions,
                        'preparedBy' => $preparedBy,
                        'logo' => $logoWebp,
                        'logoPh' => $logoPh,
                        'generatedBy' => $generatedBy,
                        'selection' => $selectionOption,
                        'division' => $division,
                        'client' => $client,
                    ];

                    $pdf = PDF::loadView('admin.pdf.all-transactions', $data, compact('now'))
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
                    $filename = 'transaction-report-' . date('F-Y') . '.pdf';    
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
    public function goToFastMoving(){
        $fastMovingItems = TransactionDetailModel::select('item_id')
            ->selectRaw('COUNT(*) as request_count')
            ->selectRaw('SUM(request_quantity) as total_requested')
            ->with(['item', 'transacts' => function($query) {
                // Filter by 'remark' within the 'transaction' relationship
                $query->where('remark', 'Completed');
            }])
            ->where('created_at', '>=', Carbon::now()->subDays(30)) // Filter by the last 30 days
            ->groupBy('item_id')
            ->orderByDesc('total_requested')
            ->get();
        $filteredItems = $fastMovingItems->filter(function($detail) {
            // Only keep transaction details where the transaction is completed
            return $detail->transaction; // Transaction will be null if it doesn't match 'remark'
        });
        $transactionUsers = TransactionModel::with([
            'transactionDetail',
            'client',
            'item',
            'item.inventory.unit',
            'status',
            'adminBy',
            'admin'
        ])
        ->where(function ($query) {
            $query->where('remark', 'Completed');
        })
        ->get();  
        $admins = AdminModel::all();
        return view('admin.reports.fast-moving', compact('fastMovingItems', 'admins', 'transactionUsers'));
    }
}
