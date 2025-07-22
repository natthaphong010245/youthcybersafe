<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CyberbullyingAssessment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cyberbullying_assessment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['assessment_data'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'assessment_data' => 'array',
    ];

    /**
     * Get person action assessment data (first array - index 0)
     */
    public function getPersonActionAssessmentAttribute()
    {
        return $this->assessment_data[0] ?? [];
    }

    /**
     * Get victim assessment data (second array - index 1)
     */
    public function getVictimAssessmentAttribute()
    {
        return $this->assessment_data[1] ?? [];
    }

    /**
     * Check if person action assessment is completed
     */
    public function hasPersonActionAssessment()
    {
        $personAction = $this->assessment_data[0] ?? [];
        return !empty($personAction);
    }

    /**
     * Check if victim assessment is completed
     */
    public function hasVictimAssessment()
    {
        $victim = $this->assessment_data[1] ?? [];
        return !empty($victim);
    }

    /**
     * Check if both assessments are completed
     */
    public function isBothAssessmentsCompleted()
    {
        return $this->hasPersonActionAssessment() && $this->hasVictimAssessment();
    }

    /**
     * Get person action score
     */
    public function getPersonActionScore()
    {
        $personAction = $this->getPersonActionAssessmentAttribute();
        return array_sum($personAction);
    }

    /**
     * Get victim score
     */
    public function getVictimScore()
    {
        $victim = $this->getVictimAssessmentAttribute();
        return array_sum($victim);
    }

    /**
     * Get total score from both assessments
     */
    public function getTotalScore()
    {
        return $this->getPersonActionScore() + $this->getVictimScore();
    }
}