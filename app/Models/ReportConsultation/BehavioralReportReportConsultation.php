<?php

namespace App\Models\ReportConsultation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GoogleDriveService;

class BehavioralReportReportConsultation extends Model
{
    use HasFactory;
    
    protected $table = 'behavioral_report';
    
    protected $fillable = [
        'who', 'school', 'message', 'voice', 'image', 
        'latitude', 'longitude', 'status',
    ];
    
    protected $casts = [
        'image' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ลดฟังก์ชันให้เหลือแค่พื้นฐานก่อน เพื่อป้องกัน error
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopePending($query)
    {
        return $query->where('status', false);
    }
}