<?php
// app/Http/Controllers/BehavioralReportController.php - Fallback Version

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
     * Store behavioral report with fallback to local storage
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

            Log::info("=== Processing Behavioral Report (Fallback Mode) ===", [
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

            // ตรวจสอบว่ามี Google Drive Service หรือไม่
            $useGoogleDrive = $this->checkGoogleDriveAvailability();
            
            if ($useGoogleDrive) {
                // ใช้ Google Drive
                $uploadResults = $this->processWithGoogleDrive($request, $report);
            } else {
                // ใช้ Local Storage แทน
                $uploadResults = $this->processWithLocalStorage($request, $report);
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
                'storage_method' => $useGoogleDrive ? 'Google Drive' : 'Local Storage',
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
     * ตรวจสอบว่าสามารถใช้ Google Drive ได้หรือไม่
     */
    private function checkGoogleDriveAvailability()
    {
        try {
            // ตรวจสอบว่ามี Google Client class หรือไม่
            if (!class_exists('Google_Client')) {
                Log::warning('Google_Client class not found, falling back to local storage');
                return false;
            }

            // ตรวจสอบว่ามีไฟล์ service account key หรือไม่
            $possiblePaths = [
                storage_path('app/google/service-account-key.json'),
                storage_path('app/google-credentials.json'),
                base_path('google-credentials.json'),
            ];
            
            $keyExists = false;
            foreach ($possiblePaths as $path) {
                if (file_exists($path) && is_readable($path)) {
                    $keyExists = true;
                    break;
                }
            }
            
            if (!$keyExists) {
                Log::warning('Google service account key file not found, falling back to local storage');
                return false;
            }

            Log::info('Google Drive is available, using Google Drive storage');
            return true;

        } catch (Exception $e) {
            Log::warning('Google Drive availability check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * ประมวลผลไฟล์ด้วย Google Drive
     */
    private function processWithGoogleDrive($request, $report)
    {
        try {
            $googleDriveService = new \App\Services\GoogleDriveService();
            $uploadResults = [
                'voice' => null,
                'images' => [],
                'errors' => []
            ];

            // ประมวลผลไฟล์เสียง
            if ($request->filled('audio_recording')) {
                try {
                    $voiceFileName = 'voice_' . $report->id . '_' . now()->format('Y_m_d_H_i_s') . '.mp3';
                    $voiceResult = $googleDriveService->uploadVoiceFile($request->audio_recording, $voiceFileName);
                    
                    $report->voice = $voiceFileName;
                    $report->save();
                    
                    $uploadResults['voice'] = $voiceResult;
                    Log::info("✅ Voice file uploaded to Google Drive", ['filename' => $voiceFileName]);
                    
                } catch (Exception $e) {
                    $uploadResults['errors'][] = 'การอัปโหลดไฟล์เสียงไปยัง Google Drive ล้มเหลว: ' . $e->getMessage();
                    Log::error("❌ Google Drive voice upload failed: " . $e->getMessage());
                }
            }

            // ประมวลผลไฟล์รูปภาพ
            if ($request->hasFile('photos')) {
                try {
                    $uploadedImages = [];
                    
                    foreach ($request->file('photos') as $index => $photo) {
                        try {
                            $extension = $photo->getClientOriginalExtension();
                            $imageFileName = 'image_' . $report->id . '_' . ($index + 1) . '_' . now()->format('Y_m_d_H_i_s') . '.' . $extension;
                            
                            $imageResult = $googleDriveService->uploadImageFile($photo, $imageFileName);
                            $uploadedImages[] = $imageFileName;
                            
                            Log::info("✅ Image file uploaded to Google Drive", ['filename' => $imageFileName]);
                            
                        } catch (Exception $e) {
                            $uploadResults['errors'][] = "การอัปโหลดรูปภาพ #{$index} ไปยัง Google Drive ล้มเหลว: " . $e->getMessage();
                            Log::error("❌ Google Drive image upload failed: " . $e->getMessage());
                        }
                    }
                    
                    if (!empty($uploadedImages)) {
                        $report->image = $uploadedImages;
                        $report->save();
                        $uploadResults['images'] = $uploadedImages;
                    }
                    
                } catch (Exception $e) {
                    $uploadResults['errors'][] = 'การประมวลผลรูปภาพผ่าน Google Drive ล้มเหลว: ' . $e->getMessage();
                    Log::error("❌ Google Drive image processing failed: " . $e->getMessage());
                }
            }

            return $uploadResults;
            
        } catch (Exception $e) {
            Log::error("Google Drive processing completely failed: " . $e->getMessage());
            // หาก Google Drive ล้มเหลวทั้งหมด ให้ใช้ Local Storage แทน
            return $this->processWithLocalStorage($request, $report);
        }
    }

    /**
     * ประมวลผลไฟล์ด้วย Local Storage
     */
    private function processWithLocalStorage($request, $report)
    {
        $uploadResults = [
            'voice' => null,
            'images' => [],
            'errors' => []
        ];

        try {
            // สร้างโฟลเดอร์สำหรับเก็บไฟล์
            Storage::makeDirectory('behavioral_reports/voices');
            Storage::makeDirectory('behavioral_reports/images');

            // ประมวลผลไฟล์เสียง
            if ($request->filled('audio_recording')) {
                try {
                    $voiceFileName = 'voice_' . $report->id . '_' . now()->format('Y_m_d_H_i_s') . '.mp3';
                    
                    // แปลง base64 เป็นไฟล์
                    $audioData = $request->audio_recording;
                    if (strpos($audioData, 'data:audio') === 0) {
                        $audioData = substr($audioData, strpos($audioData, ',') + 1);
                        $audioData = base64_decode($audioData);
                    }
                    
                    // บันทึกไฟล์
                    Storage::put('behavioral_reports/voices/' . $voiceFileName, $audioData);
                    
                    $report->voice = $voiceFileName;
                    $report->save();
                    
                    $uploadResults['voice'] = ['filename' => $voiceFileName, 'storage' => 'local'];
                    Log::info("✅ Voice file saved to local storage", ['filename' => $voiceFileName]);
                    
                } catch (Exception $e) {
                    $uploadResults['errors'][] = 'การบันทึกไฟล์เสียงล้มเหลว: ' . $e->getMessage();
                    Log::error("❌ Local voice save failed: " . $e->getMessage());
                }
            }

            // ประมวลผลไฟล์รูปภาพ
            if ($request->hasFile('photos')) {
                try {
                    $uploadedImages = [];
                    
                    foreach ($request->file('photos') as $index => $photo) {
                        try {
                            $extension = $photo->getClientOriginalExtension();
                            $imageFileName = 'image_' . $report->id . '_' . ($index + 1) . '_' . now()->format('Y_m_d_H_i_s') . '.' . $extension;
                            
                            // บันทึกไฟล์รูปภาพ
                            $photo->storeAs('behavioral_reports/images', $imageFileName);
                            $uploadedImages[] = $imageFileName;
                            
                            Log::info("✅ Image file saved to local storage", ['filename' => $imageFileName]);
                            
                        } catch (Exception $e) {
                            $uploadResults['errors'][] = "การบันทึกรูปภาพ #{$index} ล้มเหลว: " . $e->getMessage();
                            Log::error("❌ Local image save failed: " . $e->getMessage());
                        }
                    }
                    
                    if (!empty($uploadedImages)) {
                        $report->image = $uploadedImages;
                        $report->save();
                        $uploadResults['images'] = $uploadedImages;
                    }
                    
                } catch (Exception $e) {
                    $uploadResults['errors'][] = 'การประมวลผลรูปภาพล้มเหลว: ' . $e->getMessage();
                    Log::error("❌ Local image processing failed: " . $e->getMessage());
                }
            }

            Log::info("📁 Files processed with local storage", [
                'voice_count' => $uploadResults['voice'] ? 1 : 0,
                'image_count' => count($uploadResults['images']),
                'error_count' => count($uploadResults['errors'])
            ]);

            return $uploadResults;

        } catch (Exception $e) {
            Log::error("Local storage processing failed: " . $e->getMessage());
            $uploadResults['errors'][] = 'การประมวลผลไฟล์ล้มเหลว: ' . $e->getMessage();
            return $uploadResults;
        }
    }

    /**
     * Test Google Drive connection
     */
    public function testGoogleDrive()
    {
        try {
            // ตรวจสอบ Google Client
            if (!class_exists('Google_Client')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Google API Client library not installed',
                    'recommendation' => 'Run: composer require google/apiclient',
                    'google_client_available' => false,
                    'service_initialized' => false
                ], 500, [], JSON_PRETTY_PRINT);
            }

            // ตรวจสอบ Service Account Key
            $testResult = \App\Services\GoogleDriveService::testBasicConnection();
            
            if ($testResult['key_file_exists'] && $testResult['json_valid']) {
                $service = new \App\Services\GoogleDriveService();
                $testResult['service_initialized'] = true;
                $testResult['message'] = 'Google Drive service is working properly';
                $testResult['google_client_available'] = true;
            } else {
                $testResult['service_initialized'] = false;
                $testResult['message'] = 'Google Drive service configuration issues found';
                $testResult['google_client_available'] = true;
            }
            
            return response()->json($testResult, 200, [], JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'google_client_available' => class_exists('Google_Client'),
                'service_initialized' => false,
                'error' => $e->getMessage(),
                'message' => 'Google Drive service initialization failed',
                'recommendation' => class_exists('Google_Client') ? 
                    'Check service account key file and permissions' : 
                    'Install Google API Client: composer require google/apiclient'
            ], 500, [], JSON_PRETTY_PRINT);
        }
    }
}