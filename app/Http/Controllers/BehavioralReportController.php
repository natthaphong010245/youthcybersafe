<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReportConsultation\BehavioralReportReportConsultation;

class BehavioralReportController extends Controller
{
    public function index()
    {
        return view('report&consultation.behavioral_report.behavioral_report');
    }
    
    public function store(Request $request)
    {
        // ทำให้เรียบง่ายก่อน เพื่อทดสอบ
        try {
            $report = BehavioralReportReportConsultation::create([
                'who' => $request->report_to ?? 'researcher',
                'school' => $request->school,
                'message' => $request->message ?? 'Test message',
                'status' => false,
            ]);

            return redirect()->route('behavioral_report')
                ->with('success', 'รายงานถูกส่งเรียบร้อยแล้ว');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()])
                ->withInput();
        }
    }
}