<?php
namespace App\Models\ReportConsultation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\LocalFileService;
use Illuminate\Support\Facades\Log;

class BehavioralReportReportConsultation extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     */
    protected $table = 'behavioral_report';
    
    /**
     * The attributes that are mass assignable.
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
     * Bootstrap the model and its traits.
     */
    protected static function boot()
    {
        parent::boot();
        
        // ตั้งค่าเริ่มต้นให้ status = false
        static::creating(function ($model) {
            if (is_null($model->status)) {
                $model->status = false;
            }
        });
    }

    /**
     * บันทึกไฟล์เสียงและอัปเดตค่าในฐานข้อมูล
     */
    public function saveVoiceRecording($audioData): array
    {
        try {
            $fileService = new LocalFileService();
            $result = $fileService->uploadVoice($audioData, $this->id);
            
            Log::info('Voice upload result:', $result);
            
            if ($result['success']) {
                $this->voice = $result['secure_url']; 
                $this->save();
                
                return [
                    'success' => true,
                    'filename' => $result['filename'],
                    'url' => $result['secure_url']
                ];
            }
            
            return [
                'success' => false,
                'error' => $result['error'] ?? 'Failed to upload voice recording'
            ];
        } catch (\Exception $e) {
            Log::error('Voice recording save failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Failed to save voice recording: ' . $e->getMessage()
            ];
        }
    }

    /**
     * บันทึกรูปภาพและอัปเดตค่าในฐานข้อมูล
     */
    public function saveImages($photos): array
    {
        try {
            $fileService = new LocalFileService();
            $result = $fileService->uploadMultipleImages($photos, $this->id);
            
            Log::info('Images upload result:', $result);
            
            if ($result['success_count'] > 0) {
                $this->image = json_encode($result['urls'], JSON_UNESCAPED_SLASHES);
                $this->save();
                
                return [
                    'success' => true,
                    'uploaded_count' => $result['success_count'],
                    'total_count' => $result['total_count'],
                    'urls' => $result['urls'],
                    'filenames' => $result['filenames']
                ];
            }
            
            return [
                'success' => false,
                'error' => 'Failed to upload images',
                'results' => $result['results']
            ];
        } catch (\Exception $e) {
            Log::error('Images save failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Failed to save images: ' . $e->getMessage()
            ];
        }
    }

    /**
     * รับ URL ของรูปภาพ
     */
    public function getImageUrls(): array
    {
        if (empty($this->image)) {
            return [];
        }
        
        $images = is_string($this->image) ? json_decode($this->image, true) : $this->image;
        return $images ?? [];
    }

    /**
     * รับ URL ของไฟล์เสียง
     */
    public function getVoiceUrl(): ?string
    {
        if (empty($this->voice)) {
            return null;
        }
        
        if (filter_var($this->voice, FILTER_VALIDATE_URL)) {
            return $this->voice;
        }
        
        return asset("uploads/behavioral_report/voice/{$this->id}/{$this->voice}");
    }

    /**
     * ตรวจสอบว่า report นี้ได้รับการอนุมัติแล้วหรือไม่
     */
    public function isApproved(): bool
    {
        return $this->status === true;
    }

    /**
     * อนุมัติ report
     */
    public function approve(): bool
    {
        $this->status = true;
        return $this->save();
    }

    /**
     * ยกเลิกการอนุมัติ report
     */
    public function disapprove(): bool
    {
        $this->status = false;
        return $this->save();
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', true);
    }

    public function scopePending($query)
    {
        return $query->where('status', false);
    }

    public function scopeForTeacher($query)
    {
        return $query->where('who', 'teacher');
    }

    public function scopeForResearcher($query)
    {
        return $query->where('who', 'researcher');
    }

    // Accessors
    public function getStatusTextAttribute(): string
    {
        return $this->status ? 'อนุมัติแล้ว' : 'รออนุมัติ';
    }

    public function getWhoTextAttribute(): string
    {
        return $this->who === 'teacher' ? 'ครู' : 'นักวิจัย';
    }
}