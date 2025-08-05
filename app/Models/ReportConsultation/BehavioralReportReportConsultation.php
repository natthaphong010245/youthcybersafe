<?php
// app/Models/ReportConsultation/BehavioralReportReportConsultation.php - Simple version

namespace App\Models\ReportConsultation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get image attribute as array
     */
    public function getImageAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }
        
        // Try to decode JSON
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        
        // If not JSON, return as single string in array
        return [$value];
    }

    /**
     * Set image attribute as JSON
     */
    public function setImageAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['image'] = null;
            return;
        }
        
        if (is_array($value)) {
            $this->attributes['image'] = json_encode($value);
            return;
        }
        
        // Single string value
        $this->attributes['image'] = json_encode([$value]);
    }

    /**
     * Scope for active reports
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for pending reports
     */
    public function scopePending($query)
    {
        return $query->where('status', false);
    }

    /**
     * Get human readable status
     */
    public function getStatusTextAttribute()
    {
        return $this->status ? 'ประมวลผลแล้ว' : 'รอการประมวลผล';
    }

    /**
     * Get human readable who
     */
    public function getWhoTextAttribute()
    {
        return $this->who === 'teacher' ? 'ครู' : 'นักวิจัย';
    }
}