<?php

namespace App\Models\ReportConsultation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GoogleDriveService;

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
        'updated_at' => 'datetime',
    ];

    /**
     * บันทึกไฟล์เสียงไปยัง Google Drive และอัปเดตค่าในฐานข้อมูล
     *
     * @param string $audioData
     * @return void
     */
    public function saveVoiceRecording($audioData)
    {
        try {
            $googleDriveService = new GoogleDriveService();
            $filename = $googleDriveService->uploadVoiceFile($audioData);
            
            // Update the model
            $this->voice = $filename;
            $this->save();

            \Log::info("Voice recording saved successfully for report ID: {$this->id}, filename: {$filename}");

        } catch (\Exception $e) {
            \Log::error("Failed to save voice recording for report ID {$this->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * บันทึกรูปภาพไปยัง Google Drive และอัปเดตค่าในฐานข้อมูล
     *
     * @param array $photos
     * @return void
     */
    public function saveImages($photos)
    {
        try {
            $googleDriveService = new GoogleDriveService();
            $uploadedFiles = $googleDriveService->uploadImageFiles($photos);
            
            // Update the model - เก็บเป็น JSON Array
            $this->image = $uploadedFiles;
            $this->save();

            \Log::info("Images saved successfully for report ID: {$this->id}, files: " . implode(', ', $uploadedFiles));

        } catch (\Exception $e) {
            \Log::error("Failed to save images for report ID {$this->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * ดาวน์โหลดไฟล์เสียงจาก Google Drive
     *
     * @return string|null
     */
    public function getVoiceFileContent()
    {
        if (!$this->voice) {
            return null;
        }

        try {
            $googleDriveService = new GoogleDriveService();
            return $googleDriveService->downloadFile($this->voice, 'voice');
        } catch (\Exception $e) {
            \Log::error("Failed to get voice file content for report ID {$this->id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * ดาวน์โหลดไฟล์รูปภาพจาก Google Drive
     *
     * @param string $filename
     * @return string|null
     */
    public function getImageFileContent($filename)
    {
        try {
            $googleDriveService = new GoogleDriveService();
            return $googleDriveService->downloadFile($filename, 'image');
        } catch (\Exception $e) {
            \Log::error("Failed to get image file content {$filename} for report ID {$this->id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * ลบไฟล์ทั้งหมดจาก Google Drive
     *
     * @return void
     */
    public function deleteFiles()
    {
        try {
            $googleDriveService = new GoogleDriveService();
            
            // ลบไฟล์เสียง
            if ($this->voice) {
                $googleDriveService->deleteFile($this->voice, 'voice');
                \Log::info("Deleted voice file: {$this->voice} for report ID: {$this->id}");
            }
            
            // ลบไฟล์รูปภาพ
            if ($this->image && is_array($this->image)) {
                foreach ($this->image as $imageFile) {
                    $googleDriveService->deleteFile($imageFile, 'image');
                    \Log::info("Deleted image file: {$imageFile} for report ID: {$this->id}");
                }
            }

        } catch (\Exception $e) {
            \Log::error("Failed to delete files for report ID {$this->id}: " . $e->getMessage());
        }
    }

    /**
     * ตรวจสอบว่ารายงานนี้มีไฟล์แนบหรือไม่
     *
     * @return bool
     */
    public function hasAttachments()
    {
        return !empty($this->voice) || !empty($this->image);
    }

    /**
     * ดึงข้อมูลสถิติไฟล์แนบ
     *
     * @return array
     */
    public function getAttachmentStats()
    {
        return [
            'has_voice' => !empty($this->voice),
            'has_images' => !empty($this->image),
            'image_count' => is_array($this->image) ? count($this->image) : 0,
            'voice_filename' => $this->voice,
            'image_filenames' => $this->image
        ];
    }

    /**
     * สร้าง URL สำหรับเข้าถึงไฟล์เสียง
     *
     * @return string|null
     */
    public function getVoiceUrl()
    {
        if (!$this->voice) {
            return null;
        }

        return route('behavioral-report.voice', ['id' => $this->id]);
    }

    /**
     * สร้าง URL สำหรับเข้าถึงไฟล์รูปภาพ
     *
     * @param string $filename
     * @return string|null
     */
    public function getImageUrl($filename)
    {
        if (!$this->image || !in_array($filename, $this->image)) {
            return null;
        }

        return route('behavioral-report.image', ['id' => $this->id, 'filename' => $filename]);
    }

    /**
     * ดึง URLs ของรูปภาพทั้งหมด
     *
     * @return array
     */
    public function getImageUrls()
    {
        if (!$this->image || !is_array($this->image)) {
            return [];
        }

        $urls = [];
        foreach ($this->image as $filename) {
            $urls[] = $this->getImageUrl($filename);
        }

        return $urls;
    }

    /**
     * อัปเดตสถานะรายงาน
     *
     * @param bool $status
     * @return bool
     */
    public function updateStatus($status)
    {
        try {
            $this->status = $status;
            $this->save();

            \Log::info("Updated status to " . ($status ? 'true' : 'false') . " for report ID: {$this->id}");
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to update status for report ID {$this->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Scope สำหรับรายงานที่มีสถานะ active
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope สำหรับรายงานที่มีสถานะ pending
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', false);
    }

    /**
     * Scope สำหรับรายงานที่ส่งให้ครู
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForTeachers($query)
    {
        return $query->where('who', 'teacher');
    }

    /**
     * Scope สำหรับรายงานที่ส่งให้นักวิจัย
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForResearchers($query)
    {
        return $query->where('who', 'researcher');
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // ลบไฟล์เมื่อลบรายงาน
        static::deleting(function ($report) {
            \Log::info("Deleting report ID: {$report->id} and associated files");
            $report->deleteFiles();
        });

        // Log เมื่อสร้างรายงานใหม่
        static::created(function ($report) {
            \Log::info("New behavioral report created with ID: {$report->id}");
        });

        // Log เมื่ออัปเดตรายงาน
        static::updated(function ($report) {
            \Log::info("Behavioral report updated, ID: {$report->id}");
        });
    }
}