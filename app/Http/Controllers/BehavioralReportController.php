<?php
// app/Http/Controllers/BehavioralReportController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            
            // Handle voice recording upload to Google Drive
            if ($request->filled('audio_recording')) {
                $voiceFileName = $this->handleVoiceUpload($request->audio_recording, $reportId);
                
                if ($voiceFileName) {
                    DB::table('behavioral_report')
                        ->where('id', $reportId)
                        ->update(['voice' => $voiceFileName]);
                }
            }
            
            // Handle image uploads to Google Drive
            if ($request->hasFile('photos')) {
                $imageFileNames = $this->handleImageUploads($request->file('photos'), $reportId);
                
                if (!empty($imageFileNames)) {
                    DB::table('behavioral_report')
                        ->where('id', $reportId)
                        ->update(['image' => json_encode($imageFileNames, JSON_UNESCAPED_SLASHES)]);
                }
            }
            
            Log::info('Behavioral report created successfully', [
                'report_id' => $reportId,
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
     *
     * @param string $audioData
     * @param int $reportId
     * @return string|null
     */
    private function handleVoiceUpload($audioData, $reportId)
    {
        try {
            // Check if it's a base64 data URL
            if (strpos($audioData, 'data:audio') === 0) {
                // Extract base64 data
                $audioData = substr($audioData, strpos($audioData, ',') + 1);
                $audioData = base64_decode($audioData);
                
                // Upload to Google Drive
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
     *
     * @param array $photos
     * @param int $reportId
     * @return array
     */
    private function handleImageUploads($photos, $reportId)
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
            return $imageFileNames; // Return whatever we managed to upload
        }
    }
    
    /**
     * Get Google Drive download URL for a file
     *
     * @param string $fileName
     * @return string|null
     */
    public function getFileDownloadUrl($fileName)
    {
        try {
            // You would need to implement a way to map file names to Google Drive file IDs
            // This could be done by storing the file ID in the database or using a naming convention
            
            // For now, return null - you'll need to implement this based on your requirements
            return null;
        } catch (\Exception $e) {
            Log::error('Error getting file download URL: ' . $e->getMessage());
            return null;
        }
    }
}