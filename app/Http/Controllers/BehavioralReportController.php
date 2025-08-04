<?php
// app/Http/Controllers/BehavioralReportController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Log;

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
            'message' => 'required',
            'photos.*' => 'nullable|image|max:10240', // 10MB max
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        
        try {
            // Create and save the report to get the ID using DB directly
            $reportId = DB::table('behavioral_report')->insertGetId([
                'who' => $request->report_to,
                'school' => $request->report_to === 'researcher' ? null : $request->school,
                'message' => $request->message,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => false, // ค่าเริ่มต้นเป็น false
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $voiceFileName = null;
            $imageFileNames = [];
            
            // ตรวจสอบว่า Google Drive Service พร้อมใช้งานหรือไม่
            $useGoogleDrive = $this->googleDriveService->isAvailable();
            
            if ($useGoogleDrive) {
                Log::info('Using Google Drive for file storage');
                
                // Handle voice recording upload to Google Drive
                if ($request->filled('audio_recording')) {
                    $voiceFileName = $this->handleVoiceUploadGoogleDrive($request->audio_recording, $reportId);
                }
                
                // Handle image uploads to Google Drive
                if ($request->hasFile('photos')) {
                    $imageFileNames = $this->handleImageUploadsGoogleDrive($request->file('photos'), $reportId);
                }
            } else {
                Log::warning('Google Drive not available, using local storage');
                
                // Fallback to local storage
                if ($request->filled('audio_recording')) {
                    $voiceFileName = $this->handleVoiceUploadLocal($request->audio_recording, $reportId);
                }
                
                // Handle image uploads to local storage
                if ($request->hasFile('photos')) {
                    $imageFileNames = $this->handleImageUploadsLocal($request->file('photos'), $reportId);
                }
            }
            
            // Update the report with file information
            if ($voiceFileName) {
                DB::table('behavioral_report')
                    ->where('id', $reportId)
                    ->update(['voice' => $voiceFileName]);
            }
            
            if (!empty($imageFileNames)) {
                DB::table('behavioral_report')
                    ->where('id', $reportId)
                    ->update(['image' => json_encode($imageFileNames, JSON_UNESCAPED_SLASHES)]);
            }
            
            Log::info('Behavioral report created successfully', [
                'report_id' => $reportId,
                'storage_type' => $useGoogleDrive ? 'google_drive' : 'local',
                'voice_file' => $voiceFileName,
                'image_files' => $imageFileNames
            ]);
            
            // ส่ง session success เพื่อแสดงป๊อปอัพและ redirect ไปยังหน้า behavioral_report
            return redirect()->route('behavioral_report')->with('success', 'รายงานพฤติกรรมของคุณถูกส่งเรียบร้อยแล้ว');
            
        } catch (\Exception $e) {
            Log::error('Error creating behavioral report: ' . $e->getMessage(), [
                'request_data' => $request->except(['photos', 'audio_recording']),
                'error' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'เกิดข้อผิดพลาดในการส่งรายงาน กรุณาลองใหม่อีกครั้ง']);
        }
    }
    
    /**
     * Handle voice recording upload to Google Drive
     */
    private function handleVoiceUploadGoogleDrive($audioData, $reportId)
    {
        try {
            if (strpos($audioData, 'data:audio') === 0) {
                $audioData = substr($audioData, strpos($audioData, ',') + 1);
                $audioData = base64_decode($audioData);
                
                $result = $this->googleDriveService->uploadVoice($audioData, $reportId);
                
                if ($result && $result['file_name']) {
                    return $result['file_name'];
                }
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Error uploading voice to Google Drive: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Handle image uploads to Google Drive
     */
    private function handleImageUploadsGoogleDrive($photos, $reportId)
    {
        $imageFileNames = [];
        
        try {
            foreach ($photos as $index => $photo) {
                $imageData = file_get_contents($photo->getPathname());
                $originalName = $photo->getClientOriginalName();
                
                $result = $this->googleDriveService->uploadImage($imageData, $originalName, $reportId . '_' . ($index + 1));
                
                if ($result && $result['file_name']) {
                    $imageFileNames[] = $result['file_name'];
                }
            }
            
            return $imageFileNames;
        } catch (\Exception $e) {
            Log::error('Error uploading images to Google Drive: ' . $e->getMessage());
            return $imageFileNames;
        }
    }
    
    /**
     * Handle voice recording upload to local storage (fallback)
     */
    private function handleVoiceUploadLocal($audioData, $reportId)
    {
        try {
            if (strpos($audioData, 'data:audio') === 0) {
                $audioData = substr($audioData, strpos($audioData, ',') + 1);
                $audioData = base64_decode($audioData);
                
                // Create directory if it doesn't exist
                $directory = public_path('voice/behavioral_report');
                if (!File::isDirectory($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                
                // สร้างชื่อไฟล์แบบ timestamp
                $timestamp = now()->format('Y_m_d_H_i_s');
                $audioFilename = $timestamp . '_' . $reportId . '.mp3';
                
                // Save the file
                File::put($directory . '/' . $audioFilename, $audioData);
                
                return $audioFilename;
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Error uploading voice to local storage: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Handle image uploads to local storage (fallback)
     */
    private function handleImageUploadsLocal($photos, $reportId)
    {
        $imageFileNames = [];
        
        try {
            // สร้างชื่อไฟล์แบบ timestamp
            $timestamp = now()->format('Y_m_d_H_i_s');
            
            // Create directory if it doesn't exist
            $directory = public_path("images/behavioral_report/{$timestamp}_{$reportId}");
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            
            // Process each image
            foreach ($photos as $index => $photo) {
                $index++; // Start from 1
                $extension = $photo->getClientOriginalExtension();
                $filename = "{$timestamp}_{$reportId}_{$index}.{$extension}";
                
                // Move the file
                $photo->move($directory, $filename);
                
                // Add to images array
                $imageFileNames[] = $filename;
            }
            
            return $imageFileNames;
        } catch (\Exception $e) {
            Log::error('Error uploading images to local storage: ' . $e->getMessage());
            return $imageFileNames;
        }
    }
}