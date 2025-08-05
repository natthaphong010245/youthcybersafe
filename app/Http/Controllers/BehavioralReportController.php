<?php
// app/Http/Controllers/BehavioralReportController.php - Updated with Google Drive

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\GoogleDriveService;
use App\Models\ReportConsultation\BehavioralReportReportConsultation;
use Exception;

class BehavioralReportController extends Controller
{
    /**
     * Display the behavioral report form.
     */
    public function index()
    {
        try {
            return view('report&consultation.behavioral_report.behavioral_report');
        } catch (Exception $e) {
            Log::error('Error in BehavioralReportController@index: ' . $e->getMessage());
            return response()->json([
                'error' => 'View not found: report&consultation.behavioral_report.behavioral_report',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Store behavioral report with Google Drive integration
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validatedData = $request->validate([
                'report_to' => 'required|in:teacher,researcher',
                'school' => 'nullable|required_if:report_to,teacher',
                'message' => 'required|min:10|max:2000',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'audio_recording' => 'nullable|string',
            ]);

            $reportId = 'RPT_' . now()->format('YmdHis') . '_' . rand(1000, 9999);

            Log::info("=== Processing Behavioral Report with Google Drive ===", [
                'report_id' => $reportId,
                'timestamp' => now()->toDateTimeString(),
                'report_to' => $request->report_to,
                'has_audio' => $request->filled('audio_recording'),
                'has_photos' => $request->hasFile('photos'),
                'photos_count' => $request->hasFile('photos') ? count($request->file('photos')) : 0
            ]);

            // สร้างรายงานในฐานข้อมูลก่อน
            $report = BehavioralReportReportConsultation::create([
                'who' => $request->report_to,
                'school' => $request->school,
                'message' => $request->message,
                'voice' => null, // จะอัปเดตหลังอัปโหลด
                'image' => null, // จะอัปเดตหลังอัปโหลด
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => false // เริ่มต้นเป็น pending
            ]);
            
            Log::info("💾 Report created in database", ['report_id' => $report->id]);

            // ตัวแปรสำหรับเก็บผลลัพธ์การอัปโหลด
            $uploadResults = [
                'voice' => null,
                'images' => [],
                'errors' => []
            ];

            // ตรวจสอบและอัปโหลดไฟล์เสียง
            if ($request->filled('audio_recording')) {
                try {
                    Log::info("🎵 Processing voice recording...");
                    
                    // ตรวจสอบ Google Drive Service
                    $googleDriveService = new GoogleDriveService();
                    
                    // สร้างชื่อไฟล์เสียง
                    $voiceFileName = 'voice_' . $report->id . '_' . now()->format('Y_m_d_H_i_s') . '.mp3';
                    
                    // อัปโหลดไฟล์เสียงไปยัง Google Drive
                    $voiceResult = $googleDriveService->uploadVoiceFile(
                        $request->audio_recording,
                        $voiceFileName
                    );
                    
                    // อัปเดตชื่อไฟล์ในฐานข้อมูล
                    $report->voice = $voiceFileName;
                    $report->save();
                    
                    $uploadResults['voice'] = $voiceResult;
                    
                    Log::info("✅ Voice file uploaded successfully", [
                        'filename' => $voiceFileName,
                        'google_drive_id' => $voiceResult['id'] ?? 'unknown'
                    ]);
                    
                } catch (Exception $e) {
                    $uploadResults['errors'][] = 'การอัปโหลดไฟล์เสียงล้มเหลว: ' . $e->getMessage();
                    Log::error("❌ Voice upload failed", [
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);
                }
            }

            // ตรวจสอบและอัปโหลดไฟล์รูปภาพ
            if ($request->hasFile('photos')) {
                try {
                    Log::info("🖼️ Processing image files...", [
                        'count' => count($request->file('photos'))
                    ]);
                    
                    $googleDriveService = $googleDriveService ?? new GoogleDriveService();
                    $uploadedImages = [];
                    
                    foreach ($request->file('photos') as $index => $photo) {
                        try {
                            // สร้างชื่อไฟล์รูปภาพ
                            $extension = $photo->getClientOriginalExtension();
                            $imageFileName = 'image_' . $report->id . '_' . ($index + 1) . '_' . now()->format('Y_m_d_H_i_s') . '.' . $extension;
                            
                            // อัปโหลดรูปภาพไปยัง Google Drive
                            $imageResult = $googleDriveService->uploadImageFile($photo, $imageFileName);
                            
                            $uploadedImages[] = $imageFileName;
                            
                            Log::info("✅ Image file uploaded successfully", [
                                'filename' => $imageFileName,
                                'google_drive_id' => $imageResult['id'] ?? 'unknown'
                            ]);
                            
                        } catch (Exception $e) {
                            $uploadResults['errors'][] = "การอัปโหลดรูปภาพ #{$index} ล้มเหลว: " . $e->getMessage();
                            Log::error("❌ Image upload failed", [
                                'index' => $index,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }
                    
                    // อัปเดตชื่อไฟล์รูปภาพในฐานข้อมูล
                    if (!empty($uploadedImages)) {
                        $report->image = $uploadedImages; // Model จะแปลงเป็น JSON อัตโนมัติ  
                        $report->save();
                        
                        $uploadResults['images'] = $uploadedImages;
                        
                        Log::info("💾 Image filenames saved to database", [
                            'count' => count($uploadedImages),
                            'filenames' => $uploadedImages
                        ]);
                    }
                    
                } catch (Exception $e) {
                    $uploadResults['errors'][] = 'การประมวลผลรูปภาพล้มเหลว: ' . $e->getMessage();
                    Log::error("❌ Image processing failed", [
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);
                }
            }

            // อัปเดตสถานะรายงานตามผลการอัปโหลด
            $hasSuccessfulUploads = !empty($uploadResults['voice']) || !empty($uploadResults['images']);
            $hasErrors = !empty($uploadResults['errors']);
            
            if ($hasSuccessfulUploads && !$hasErrors) {
                $report->status = true; // อัปโหลดสำเร็จทั้งหมด
                $message = 'รายงานพฤติกรรมของคุณถูกส่งเรียบร้อยแล้ว รวมถึงไฟล์แนบทั้งหมด';
            } elseif ($hasSuccessfulUploads && $hasErrors) {
                $report->status = true; // บางไฟล์สำเร็จ
                $message = 'รายงานถูกส่งเรียบร้อยแล้ว แต่พบปัญหาบางประการ: ' . implode(', ', $uploadResults['errors']);
            } else {
                $report->status = false; // ไม่มีไฟล์หรือไฟล์ทั้งหมดล้มเหลว
                $message = 'รายงานถูกส่งแล้ว' . ($hasErrors ? ' แต่พบปัญหา: ' . implode(', ', $uploadResults['errors']) : '');
            }
            
            $report->save();

            // Log สรุปผลลัพธ์
            Log::info("📊 Behavioral Report Processing Complete", [
                'report_id' => $report->id,
                'database_id' => $report->id,
                'status' => $report->status ? 'success' : 'partial',
                'voice_uploaded' => !empty($uploadResults['voice']),
                'images_uploaded' => count($uploadResults['images']),
                'errors_count' => count($uploadResults['errors']),
                'final_message' => $message
            ]);

            return redirect()->route('behavioral_report')
                ->with($hasErrors ? 'warning' : 'success', $message);

        } catch (Exception $e) {
            Log::error("💥 Critical error in behavioral report processing", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->except(['audio_recording', 'photos'])
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'เกิดข้อผิดพลาดในการส่งรายงาน กรุณาลองใหม่อีกครั้ง: ' . $e->getMessage()]);
        }
    }

    /**
     * Test Google Drive connection
     */
    public function testGoogleDrive()
    {
        try {
            $testResult = GoogleDriveService::testBasicConnection();
            
            if ($testResult['key_file_exists'] && $testResult['json_valid']) {
                $service = new GoogleDriveService();
                $testResult['service_initialized'] = true;
                $testResult['message'] = 'Google Drive service is working properly';
            } else {
                $testResult['service_initialized'] = false;
                $testResult['message'] = 'Google Drive service configuration issues found';
            }
            
            return response()->json($testResult, 200, [], JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            return response()->json([
                'service_initialized' => false,
                'error' => $e->getMessage(),
                'message' => 'Google Drive service initialization failed'
            ], 500, [], JSON_PRETTY_PRINT);
        }
    }
}