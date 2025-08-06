<?php
// app/Http/Controllers/BehavioralReportController.php - Emergency Fix
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ReportConsultation\BehavioralReportReportConsultation;

class BehavioralReportController extends Controller
{
    // ลบ dependency injection ชั่วคราว เพื่อหยุด error
    public function __construct()
    {
        // Comment out dependency injection temporarily
        // $this->fileService = $fileService;
    }

    /**
     * Display the behavioral report form.
     */
    public function index()
    {
        return view('report&consultation.behavioral_report.behavioral_report');
    }
    
    /**
     * Store a newly created behavioral report in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'report_to' => 'required|in:teacher,researcher',
            'school' => 'nullable|required_if:report_to,teacher',
            'message' => 'required',
            'photos.*' => 'nullable|image|max:10240', // 10MB max
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create and save the report to get the ID
            $report = BehavioralReportReportConsultation::create([
                'who' => $request->report_to,
                'school' => $request->report_to === 'researcher' ? null : $request->school,
                'message' => $request->message,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => false,
            ]);
            
            $uploadResults = [];
            
            // Handle voice recording upload - ใช้ model method แทน service
            if ($request->filled('audio_recording')) {
                Log::info('Processing voice recording for report ID: ' . $report->id);
                
                try {
                    $voiceResult = $report->saveVoiceRecording($request->audio_recording);
                    $uploadResults['voice'] = $voiceResult;
                } catch (\Exception $e) {
                    Log::error('Voice upload failed:', ['error' => $e->getMessage()]);
                    $uploadResults['voice'] = ['success' => false, 'error' => $e->getMessage()];
                }
            }
            
            // Handle image uploads - ใช้ model method แทน service
            if ($request->hasFile('photos')) {
                Log::info('Processing images for report ID: ' . $report->id, [
                    'image_count' => count($request->file('photos'))
                ]);
                
                try {
                    $imageResult = $report->saveImages($request->file('photos'));
                    $uploadResults['images'] = $imageResult;
                } catch (\Exception $e) {
                    Log::error('Image upload failed:', ['error' => $e->getMessage()]);
                    $uploadResults['images'] = ['success' => false, 'error' => $e->getMessage()];
                }
            }
            
            DB::commit();
            
            // Log successful report creation
            Log::info('Behavioral report created successfully', [
                'report_id' => $report->id,
                'who' => $report->who,
                'school' => $report->school,
                'upload_results' => $uploadResults
            ]);
            
            // สร้างข้อความ success
            $successMessage = 'รายงานพฤติกรรมของคุณถูกส่งเรียบร้อยแล้ว';
            
            return redirect()->route('behavioral_report')->with('success', $successMessage);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Failed to create behavioral report', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'เกิดข้อผิดพลาดในการส่งรายงาน กรุณาลองใหม่อีกครั้ง']);
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        return [
            'total' => BehavioralReportReportConsultation::count(),
            'approved' => BehavioralReportReportConsultation::approved()->count(),
            'pending' => BehavioralReportReportConsultation::pending()->count(),
            'teacher_reports' => BehavioralReportReportConsultation::forTeacher()->count(),
            'researcher_reports' => BehavioralReportReportConsultation::forResearcher()->count(),
            'recent_reports' => BehavioralReportReportConsultation::latest()->take(5)->get()
        ];
    }
}