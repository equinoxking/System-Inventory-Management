<?php

namespace App\Http\Controllers\User\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Inventory_Admin\Trail\TrailManager;
use App\Models\ClientModel;
use App\Models\TransactionModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Intervention\Image\Facades\Image;

class UserReportManager extends Controller
{
    public function generateUserReportPdf(Request $request){
        $client_id = session()->get('loginCheckUser')['id'];
        $transactions = TransactionModel::where('user_id', $client_id)->get();
        $client = ClientModel::where('id', $client_id)->first();
        $clientName = $client->full_name;
        $now = Carbon::now('Asia/Manila')->format('F j, Y h:i A');
        $logoPh = $this->getCompressedBase64Image('assets/images/LOGO-PH.png', 'png');
        $logoWebp = $this->getCompressedBase64Image('assets/images/LOGO.webp', 'webp');
        $generatedBy = ClientModel::where('id', $client_id)->first();

        $data = [
                'transactions' => $transactions,
                'logo' => $logoWebp,
                'logoPh' => $logoPh,
                'generatedBy' => $generatedBy,
                'clientName' => $clientName,
                'client' => $client
            ];

        $admin_id = null;
        $user_id = session()->get('loginCheckUser')['id'];
        $activity = "Generated transaction PDF - All Records.";
        (new TrailManager)->createUserTrail($user_id, $admin_id, $activity);

        $pdf = PDF::loadView('user.report.all-transactions', $data, compact('now'))
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
}
