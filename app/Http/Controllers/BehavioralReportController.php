<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\GoogleDriveService;
use App\Models\ReportConsultation\BehavioralReportReportConsultation;

class BehavioralReportController extends Controller
{
    protected $googleDriveService;

    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    /**
     * Display the behavioral report form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('report&consultation.behavioral_report.behavioral_report');
    }
    
    /**
     * Store a newly created behavioral report in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'report_to' => 'required|in:teacher,researcher',
            'school' => 'nullable|required_if:report_to,teacher',
            'message' => 'required|min:10|max:2000',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'audio_recording' => 'nullable|string',
        ], [
            'report_to.required' => 'กรุณาเลือกผู้รับรายงาน',
            'report_to.in' => 'ผู้รับรายงานไม่ถูกต้อง',
            'school.required_if' => 'กรุณาเลือกโรงเรียนเมื่อรายงานให้ครู',
            'message.required' => 'กรุณาใส่ข้อความ',
            'message.min' => 'ข้อความต้องมีอย่างน้อย 10 ตัวอักษร',
            'message.max' => 'ข้อความต้องไม่เกิน 2000 ตัวอักษร',
            'photos.*.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพเท่านั้น',
            'photos.*.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg, gif',
            'photos.*.max' => 'ขนาดรูปภาพต้องไม่เกิน 10MB',
            'latitude.between' => 'ค่า latitude ไม่ถูกต้อง',
            'longitude.between' => 'ค่า longitude ไม่ถูกต้อง',
        ]);
        
        try {
            // สร้าง behavioral report record ใหม่
            $report = BehavioralReportReportConsultation::create([
                'who' => $request->report_to,
                'school' => $request->report_to === 'researcher' ? null : $request->school,
                'message' => $request->message,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => false, // ค่าเริ่มต้น
            ]);

            \Log::info("Created new behavioral report with ID: {$report->id}");

            // สร้างชื่อไฟล์พื้นฐานจาก timestamp
            $baseFilename = $this->googleDriveService->generateFileName('');
            $baseFilename = rtrim($baseFilename, '.'); // ตัด extension ออก

            $uploadErrors = [];

            // Handle voice recording
            if ($request->filled('audio_recording')) {
                try {
                    $voiceFilename = $baseFilename . '.mp3';
                    $this->googleDriveService->uploadVoiceFile(
                        $request->audio_recording, 
                        $voiceFilename
                    );
                    
                    // อัปเดตชื่อไฟล์เสียงในฐานข้อมูล
                    $report->voice = $voiceFilename;
                    $report->save();
                    
                    \Log::info("Voice file uploaded successfully for report ID: {$report->id}, filename: {$voiceFilename}");
                    
                } catch (\Exception $e) {
                    $uploadErrors[] = 'การอัปโหลดไฟล์เสียงล้มเหลว: ' . $e->getMessage();
                    \Log::error("Voice upload failed for report ID {$report->id}: " . $e->getMessage());
                }
            }
            
            // Handle image uploads
            if ($request->hasFile('photos')) {
                try {
                    $uploadedImages = $this->googleDriveService->uploadImageFiles(
                        $request->file('photos'), 
                        $baseFilename
                    );
                    
                    // อัปเดตชื่อไฟล์รูปภาพในฐานข้อมูล
                    $report->image = $uploadedImages;
                    $report->save();
                    
                    \Log::info("Images uploaded successfully for report ID: {$report->id}, files: " . implode(', ', $uploadedImages));
                    
                } catch (\Exception $e) {
                    $uploadErrors[] = 'การอัปโหลดรูปภาพล้มเหลว: ' . $e->getMessage();
                    \Log::error("Image upload failed for report ID {$report->id}: " . $e->getMessage());
                }
            }
            
            // ตรวจสอบว่ามี error ในการอัปโหลดหรือไม่
            if (!empty($uploadErrors)) {
                // ถ้ามี error แต่รายงานถูกสร้างแล้ว ให้แจ้งเตือนแต่ไม่ redirect กลับ
                $message = 'รายงานถูกส่งเรียบร้อยแล้ว แต่พบปัญหาบางประการ: ' . implode(', ', $uploadErrors);
                return redirect()->route('behavioral_report')->with('warning', $message);
            }
            
            // ส่ง session success เพื่อแสดงป๊อปอัพและ redirect ไปยังหน้า behavioral_report
            return redirect()->route('behavioral_report')->with('success', 'รายงานพฤติกรรมของคุณถูกส่งเรียบร้อยแล้ว');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors
            return redirect()->back()
                ->withErrors($e->validator->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            \Log::error("Behavioral report creation failed: " . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'เกิดข้อผิดพลาดในการส่งรายงาน กรุณาลองใหม่อีกครั้ง'])
                ->withInput();
        }
    }

    /**
     * Display the specified behavioral report.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $report = BehavioralReportReportConsultation::findOrFail($id);
            
            return view('report&consultation.behavioral_report.show', compact('report'));
            
        } catch (\Exception $e) {
            return redirect()->route('behavioral_report')
                ->withErrors(['error' => 'ไม่พบรายงานที่ต้องการ']);
        }
    }

    /**
     * แสดงไฟล์เสียงจาก Google Drive
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getVoiceFile($id)
    {
        try {
            $report = BehavioralReportReportConsultation::findOrFail($id);
            
            if (!$report->voice) {
                return response()->json(['error' => 'Voice file not found'], 404);
            }

            $fileContent = $report->getVoiceFileContent();
            
            if (!$fileContent) {
                return response()->json(['error' => 'Voice file not accessible'], 404);
            }

            return response($fileContent, 200, [
                'Content-Type' => 'audio/mpeg',
                'Content-Disposition' => 'inline; filename="' . $report->voice . '"',
                'Cache-Control' => 'public, max-age=3600',
                'Accept-Ranges' => 'bytes'
            ]);

        } catch (\Exception $e) {
            \Log::error("Failed to get voice file for report ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'File not accessible'], 500);
        }
    }

    /**
     * แสดงไฟล์รูปภาพจาก Google Drive
     *
     * @param int $id
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function getImageFile($id, $filename)
    {
        try {
            $report = BehavioralReportReportConsultation::findOrFail($id);
            
            if (!$report->image || !in_array($filename, $report->image)) {
                return response()->json(['error' => 'Image file not found'], 404);
            }

            $fileContent = $report->getImageFileContent($filename);
            
            if (!$fileContent) {
                return response()->json(['error' => 'Image file not accessible'], 404);
            }

            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $mimeType = $this->getMimeTypeFromExtension($extension);

            return response($fileContent, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Cache-Control' => 'public, max-age=3600'
            ]);

        } catch (\Exception $e) {
            \Log::error("Failed to get image file {$filename} for report ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'File not accessible'], 500);
        }
    }

    /**
     * อัปเดตสถานะรายงาน
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        try {
            $report = BehavioralReportReportConsultation::findOrFail($id);
            $report->updateStatus($request->status);

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตสถานะเรียบร้อยแล้ว',
                'status' => $report->status
            ]);

        } catch (\Exception $e) {
            \Log::error("Failed to update status for report ID {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการอัปเดตสถานะ'
            ], 500);
        }
    }

    /**
     * ลบรายงาน
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $report = BehavioralReportReportConsultation::findOrFail($id);
            
            // ลบไฟล์จาก Google Drive ก่อน (จะทำใน Model boot method)
            $report->delete();

            return response()->json([
                'success' => true,
                'message' => 'ลบรายงานเรียบร้อยแล้ว'
            ]);

        } catch (\Exception $e) {
            \Log::error("Failed to delete report ID {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการลบรายงาน'
            ], 500);
        }
    }

    /**
     * ดึงรายงานทั้งหมด (สำหรับ Admin/Dashboard)
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getAllReports(Request $request)
    {
        try {
            $query = BehavioralReportReportConsultation::query();

            // Filter by status
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            // Filter by who
            if ($request->has('who') && $request->who !== '') {
                $query->where('who', $request->who);
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Search in message
            if ($request->has('search') && $request->search) {
                $query->where('message', 'like', '%' . $request->search . '%');
            }

            $reports = $query->orderBy('created_at', 'desc')
                           ->paginate($request->get('per_page', 15));

            return response()->json($reports);

        } catch (\Exception $e) {
            \Log::error("Failed to get reports: " . $e->getMessage());
            return response()->json([
                'error' => 'เกิดข้อผิดพลาดในการดึงข้อมูล'
            ], 500);
        }
    }

    /**
     * ดึงสถิติรายงาน
     *
     * @return \Illuminate\Http\Response
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total_reports' => BehavioralReportReportConsultation::count(),
                'pending_reports' => BehavioralReportReportConsultation::pending()->count(),
                'active_reports' => BehavioralReportReportConsultation::active()->count(),
                'teacher_reports' => BehavioralReportReportConsultation::forTeachers()->count(),
                'researcher_reports' => BehavioralReportReportConsultation::forResearchers()->count(),
                'reports_with_voice' => BehavioralReportReportConsultation::whereNotNull('voice')->count(),
                'reports_with_images' => BehavioralReportReportConsultation::whereNotNull('image')->count(),
                'today_reports' => BehavioralReportReportConsultation::whereDate('created_at', today())->count(),
                'this_week_reports' => BehavioralReportReportConsultation::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                'this_month_reports' => BehavioralReportReportConsultation::whereMonth('created_at', now()->month)
                                                                      ->whereYear('created_at', now()->year)
                                                                      ->count(),
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            \Log::error("Failed to get statistics: " . $e->getMessage());
            return response()->json([
                'error' => 'เกิดข้อผิดพลาดในการดึงสถิติ'
            ], 500);
        }
    }

    /**
     * กำหนด MIME type จาก file extension
     *
     * @param string $extension
     * @return string
     */
    private function getMimeTypeFromExtension($extension)
    {
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
        ];

        return $mimeTypes[strtolower($extension)] ?? 'image/jpeg';
    }
}