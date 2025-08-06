<?php
// app/Http/Controllers/BehavioralReportController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ReportConsultation\BehavioralReportReportConsultation;
use App\Services\LocalFileService; // เปลี่ยนจาก CloudinaryService

class BehavioralReportController extends Controller
{
    protected $fileService;

    public function __construct(LocalFileService $fileService) // เปลี่ยนเป็น LocalFileService
    {
        $this->fileService = $fileService;
    
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
                'status' => false, // ค่าเริ่มต้น
            ]);
            
            $uploadResults = [];
            
            // Handle voice recording upload
            if ($request->filled('audio_recording')) {
                Log::info('Processing voice recording for report ID: ' . $report->id);
                
                $voiceResult = $report->saveVoiceRecording($request->audio_recording);
                
                if ($voiceResult['success']) {
                    Log::info('Voice recording uploaded successfully', [
                        'report_id' => $report->id,
                        'filename' => $voiceResult['filename']
                    ]);
                    $uploadResults['voice'] = $voiceResult;
                } else {
                    Log::error('Failed to upload voice recording', [
                        'report_id' => $report->id,
                        'error' => $voiceResult['error']
                    ]);
                    $uploadResults['voice'] = $voiceResult;
                }
            }
            
            // Handle image uploads
            if ($request->hasFile('photos')) {
                Log::info('Processing images for report ID: ' . $report->id, [
                    'image_count' => count($request->file('photos'))
                ]);
                
                $imageResult = $report->saveImages($request->file('photos'));
                
                if ($imageResult['success']) {
                    Log::info('Images uploaded successfully', [
                        'report_id' => $report->id,
                        'uploaded_count' => $imageResult['uploaded_count'],
                        'total_count' => $imageResult['total_count']
                    ]);
                    $uploadResults['images'] = $imageResult;
                } else {
                    Log::error('Failed to upload images', [
                        'report_id' => $report->id,
                        'error' => $imageResult['error']
                    ]);
                    $uploadResults['images'] = $imageResult;
                }
            }
            
            DB::commit();
            
            // Log successful report creation
            Log::info('Behavioral report created successfully', [
                'report_id' => $report->id,
                'who' => $report->who,
                'school' => $report->school,
                'has_voice' => !empty($report->voice),
                'has_images' => !empty($report->image),
                'upload_results' => $uploadResults
            ]);
            
            // สร้างข้อความ success
            $successMessage = 'รายงานพฤติกรรมของคุณถูกส่งเรียบร้อยแล้ว';
            
            if (isset($uploadResults['voice']) && !$uploadResults['voice']['success']) {
                $successMessage .= ' (หมายเหตุ: ไม่สามารถอัปโหลดไฟล์เสียงได้)';
            }
            
            if (isset($uploadResults['images']) && !$uploadResults['images']['success']) {
                $successMessage .= ' (หมายเหตุ: ไม่สามารถอัปโหลดรูปภาพบางรูปได้)';
            }
            
            return redirect()->route('behavioral_report')->with('success', $successMessage);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Failed to create behavioral report', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['photos', 'audio_recording'])
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