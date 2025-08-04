<?php
// app/Models/ReportConsultation/BehavioralReportReportConsultation.php
namespace App\Models\ReportConsultation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Log;

class BehavioralReportReportConsultation extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'behavioral_report';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'who',
        'school',
        'message',
        'voice',
        'image',
        'latitude',
        'longitude',
        'status',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'image' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'status' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * บันทึกไฟล์เสียงไปยัง Google Drive และอัปเดตค่าในฐานข้อมูล
     *
     * @param string $audioData
     * @param GoogleDriveService $googleDriveService
     * @return bool
     */
    public function saveVoiceRecordingToGoogleDrive($audioData, GoogleDriveService $googleDriveService)
    {
        try {
            // Check if it's a base64 data URL
            if (strpos($audioData, 'data:audio') === 0) {
                // Extract base64 data
                $audioData = substr($audioData, strpos($audioData, ',') + 1);
                $audioData = base64_decode($audioData);
                
                // Upload to Google Drive
                $result = $googleDriveService->uploadVoice($audioData, $this->id);
                
                if ($result && $result['file_name']) {
                    $this->voice = $result['file_name'];
                    $this->save();
                    return true;
                }
            }
            
            return false;
        } catch (\Exception $e) {
            Log::error('Error saving voice recording to Google Drive: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * บันทึกรูปภาพไปยัง Google Drive และอัปเดตค่าในฐานข้อมูล
     *
     * @param array $photos
     * @param GoogleDriveService $googleDriveService
     * @return bool
     */
    public function saveImagesToGoogleDrive($photos, GoogleDriveService $googleDriveService)
    {
        try {
            $imageFileNames = [];
            
            foreach ($photos as $index => $photo) {
                $imageData = file_get_contents($photo->getPathname());
                $originalName = $photo->getClientOriginalName();
                
                $result = $googleDriveService->uploadImage($imageData, $originalName, $this->id . '_' . ($index + 1));
                
                if ($result && $result['file_name']) {
                    $imageFileNames[] = $result['file_name'];
                }
            }
            
            if (!empty($imageFileNames)) {
                $this->image = $imageFileNames; // จะถูก cast เป็น JSON โดยอัตโนมัติ
                $this->save();
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            Log::error('Error saving images to Google Drive: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * รช ข้อมูล Google Drive ของไฟล์เสียง
     *
     * @return array|null
     */
    public function getVoiceFileInfo()
    {
        if (!$this->voice) {
            return null;
        }
        
        return [
            'filename' => $this->voice,
            'type' => 'voice',
            'folder' => 'behavioral_report/voices'
        ];
    }
    
    /**
     * ได ข้อมูล Google Drive ของไฟล์รูปภาพ
     *
     * @return array
     */
    public function getImageFilesInfo()
    {
        if (!$this->image || !is_array($this->image)) {
            return [];
        }
        
        $imageFiles = [];
        foreach ($this->image as $filename) {
            $imageFiles[] = [
                'filename' => $filename,
                'type' => 'image',
                'folder' => 'behavioral_report/images'
            ];
        }
        
        return $imageFiles;
    }
    
    /**
     * รับรายการไฟล์ทั้งหมดที่เกี่ยวข้องกับรายงานนี้
     *
     * @return array
     */
    public function getAllFilesInfo()
    {
        $files = [];
        
        // เพิ่มไฟล์เสียง
        $voiceInfo = $this->getVoiceFileInfo();
        if ($voiceInfo) {
            $files[] = $voiceInfo;
        }
        
        // เพิ่มไฟล์รูปภาพ
        $imageFiles = $this->getImageFilesInfo();
        $files = array_merge($files, $imageFiles);
        
        return $files;
    }
    
    /**
     * Scope สำหรับกรองตาม status
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    
    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }
    
    /**
     * อัปเดตสถานะของรายงาน
     *
     * @param bool $status
     * @return bool
     */
    public function updateStatus($status)
    {
        try {
            $this->status = $status;
            return $this->save();
        } catch (\Exception $e) {
            Log::error('Error updating behavioral report status: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * ลช ไฟล์ทั้งหมดที่เกี่ยวข้องจาก Google Drive
     *
     * @param GoogleDriveService $googleDriveService
     * @return bool
     */
    public function deleteAllFilesFromGoogleDrive(GoogleDriveService $googleDriveService)
    {
        try {
            $success = true;
            
            // ลบไฟล์เสียง
            if ($this->voice) {
                // Note: You would need to implement a way to get the Google Drive file ID from the filename
                // This is a simplified example
                $success = $success && $this->deleteFileFromGoogleDrive($this->voice, $googleDriveService);
            }
            
            // ลบไฟล์รูปภาพ
            if ($this->image && is_array($this->image)) {
                foreach ($this->image as $filename) {
                    $success = $success && $this->deleteFileFromGoogleDrive($filename, $googleDriveService);
                }
            }
            
            return $success;
        } catch (\Exception $e) {
            Log::error('Error deleting files from Google Drive: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * ลบไฟล์เดี่ยวจาก Google Drive
     *
     * @param string $filename
     * @param GoogleDriveService $googleDriveService
     * @return bool
     */
    private function deleteFileFromGoogleDrive($filename, GoogleDriveService $googleDriveService)
    {
        // Note: This is a simplified implementation
        // You would need to implement a way to map filenames to Google Drive file IDs
        // This could be done by storing the file ID in a separate table or using Google Drive API to search by filename
        
        try {
            // Example implementation - you would need to modify this based on your requirements
            // $fileId = $this->getGoogleDriveFileId($filename);
            // return $googleDriveService->deleteFile($fileId);
            
            return true; // Placeholder
        } catch (\Exception $e) {
            Log::error('Error deleting individual file from Google Drive: ' . $e->getMessage());
            return false;
        }
    }
}