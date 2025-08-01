<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentalHealthAssessment extends Model
{
    use HasFactory;
    
    protected $table = 'mental_health_assessment';
    
    protected $fillable = ['stress', 'anxiety', 'depression'];
    
    protected $casts = [
        'stress' => 'array',
        'anxiety' => 'array',
        'depression' => 'array',
    ];

    public function getStressScoresAttribute()
    {
        return $this->stress[0] ?? [];
    }

    public function getAnxietyScoresAttribute()
    {
        return $this->anxiety[0] ?? [];
    }

    public function getDepressionScoresAttribute()
    {
        return $this->depression[0] ?? [];
    }


    public function getStressLevelAttribute()
    {
        return $this->stress[1] ?? 'normal';
    }

    public function getAnxietyLevelAttribute()
    {
        return $this->anxiety[1] ?? 'normal';
    }

    public function getDepressionLevelAttribute()
    {
        return $this->depression[1] ?? 'normal';
    }

    public function getStressTotalScoreAttribute()
    {
        return array_sum($this->stress_scores);
    }

    public function getAnxietyTotalScoreAttribute()
    {
        return array_sum($this->anxiety_scores);
    }

    public function getDepressionTotalScoreAttribute()
    {
        return array_sum($this->depression_scores);
    }

    public function getStressLevelThaiAttribute()
    {
        return $this->translateLevelToThai($this->stress_level);
    }

    public function getAnxietyLevelThaiAttribute()
    {
        return $this->translateLevelToThai($this->anxiety_level);
    }

    public function getDepressionLevelThaiAttribute()
    {
        return $this->translateLevelToThai($this->depression_level);
    }

    private function translateLevelToThai($level)
    {
        $translations = [
            'normal' => 'ระดับปกติ',
            'mild' => 'ระดับเล็กน้อย',
            'moderate' => 'ระดับปานกลาง',
            'severe' => 'ระดับรุนแรง',
            'verysevere' => 'ระดับรุนแรงมาก'
        ];

        return $translations[$level] ?? 'ไม่ทราบระดับ';
    }

    public function scopeByStressLevel($query, $level)
    {
        return $query->whereJsonContains('stress->1', $level);
    }

    public function scopeByAnxietyLevel($query, $level)
    {
        return $query->whereJsonContains('anxiety->1', $level);
    }

    public function scopeByDepressionLevel($query, $level)
    {
        return $query->whereJsonContains('depression->1', $level);
    }

    public function scopeHighRisk($query)
    {
        return $query->where(function($q) {
            $q->whereJsonContains('stress->1', 'severe')
              ->orWhereJsonContains('stress->1', 'verysevere')
              ->orWhereJsonContains('anxiety->1', 'severe')
              ->orWhereJsonContains('anxiety->1', 'verysevere')
              ->orWhereJsonContains('depression->1', 'severe')
              ->orWhereJsonContains('depression->1', 'verysevere');
        });
    }
}