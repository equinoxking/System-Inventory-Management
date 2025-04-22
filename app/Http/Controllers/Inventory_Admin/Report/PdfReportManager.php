<?php

namespace App\Http\Controllers\Inventory_Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminModel;
use App\Models\ReportModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class PdfReportManager extends Controller
{
    public function addReport(Request $request){
        $validator = Validator::make($request->all(), [
            'report_type' => 'required', 
            'submitted' => 'required',
            'pdf' => 'required|mimes:pdf|max:10240'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        } else {
            if ($request->hasFile('pdf')) {
                $file = $request->file('pdf');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('pdf-reports'), $filename);
        
                $admin = AdminModel::findOrFail($request->get('submitted'));
                $report = new ReportModel();
                $report->control_number = $this->generateControlNumber();
                $report->report_type = $request->get('report_type');
                $report->admin_id = $admin->id;
                $report->report_file = $filename;
                $report->save();
                
                return response()->json([
                    'message' => 'Report successfully added!',
                    'status' => 200
                ]);
            }
        }
        return response()->json([
            'message' => 'Check your internet connection!',
            'status' => 500
        ]);
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
}
