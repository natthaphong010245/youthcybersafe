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


    public function getPersonActionScoresAttribute()
    {
        $personActionData = $this->assessment_data[0] ?? [];
        
        if (empty($personActionData)) {
            return [];
        }
        
        if (is_array($personActionData) && isset($personActionData[0]) && is_array($personActionData[0])) {
            return $personActionData[0]; 
        }
        
        if (is_array($personActionData) && !empty($personActionData)) {
            $lastElement = end($personActionData);
            if (is_bool($lastElement)) {
                return array_slice($personActionData, 0, -1);
            }
            return $personActionData;
        }
        
        return [];
    }


    public function getVictimScoresAttribute()
    {
        $victimData = $this->assessment_data[1] ?? [];
        
        if (empty($victimData)) {
            return [];
        }
        
        if (is_array($victimData) && isset($victimData[0]) && is_array($victimData[0])) {
            return $victimData[0]; 
        }
        
        if (is_array($victimData) && !empty($victimData)) {
            $lastElement = end($victimData);
            if (is_bool($lastElement)) {
                return array_slice($victimData, 0, -1);
            }
            return $victimData;
        }
        
        return [];
    }


    public function getPersonActionResultAttribute()
    {
        $personActionData = $this->assessment_data[0] ?? [];
        
        if (empty($personActionData)) {
            return false;
        }
        
        if (is_array($personActionData) && isset($personActionData[1]) && is_array($personActionData[1])) {
            return $personActionData[1][0] ?? false;
        }
        
        if (is_array($personActionData) && !empty($personActionData)) {
            $lastElement = end($personActionData);
            if (is_bool($lastElement)) {
                return $lastElement;
            }
            $scores = $this->getPersonActionScoresAttribute();
            return array_sum($scores) > 0;
        }
        
        return false;
    }


    public function getVictimResultAttribute()
    {
        $victimData = $this->assessment_data[1] ?? [];
        
        if (empty($victimData)) {
            return false;
        }
        
        if (is_array($victimData) && isset($victimData[1]) && is_array($victimData[1])) {
            return $victimData[1][0] ?? false;
        }
        
        if (is_array($victimData) && !empty($victimData)) {
            $lastElement = end($victimData);
            if (is_bool($lastElement)) {
                return $lastElement;
            }
            $scores = $this->getVictimScoresAttribute();
            return array_sum($scores) > 0;
        }
        
        return false;
    }


    public function getPersonActionAssessmentAttribute()
    {
        return $this->assessment_data[0] ?? [];
    }


    public function getVictimAssessmentAttribute()
    {
        return $this->assessment_data[1] ?? [];
    }


    public function hasPersonActionAssessment()
    {
        $personAction = $this->assessment_data[0] ?? [];
        return !empty($personAction);
    }


    public function hasVictimAssessment()
    {
        $victim = $this->assessment_data[1] ?? [];
        return !empty($victim);
    }


    public function isBothAssessmentsCompleted()
    {
        return $this->hasPersonActionAssessment() && $this->hasVictimAssessment();
    }


    public function getPersonActionScore()
    {
        $scores = $this->getPersonActionScoresAttribute();
        return array_sum($scores);
    }


    public function getVictimScore()
    {
        $scores = $this->getVictimScoresAttribute();
        return array_sum($scores);
    }


    public function getTotalScore()
    {
        return $this->getPersonActionScore() + $this->getVictimScore();
    }


    public function getPersonActionPercentage()
    {
        $score = $this->getPersonActionScore();
        $maxScore = 9 * 4; 
        return $maxScore > 0 ? ($score / $maxScore) * 100 : 0;
    }

    public function getVictimPercentage()
    {
        $score = $this->getVictimScore();
        $maxScore = 9 * 4;
        return $maxScore > 0 ? ($score / $maxScore) * 100 : 0;
    }

    public function getTotalPercentage()
    {
        $totalScore = $this->getTotalScore();
        $maxTotalScore = 18 * 4; 
        return $maxTotalScore > 0 ? ($totalScore / $maxTotalScore) * 100 : 0;
    }


    public function hasBullyingExperience()
    {
        return $this->getPersonActionResultAttribute();
    }


    public function hasVictimExperience()
    {
        return $this->getVictimResultAttribute();
    }


    public function getAssessmentSummary()
    {
        return [
            'person_action' => [
                'scores' => $this->getPersonActionScoresAttribute(),
                'total_score' => $this->getPersonActionScore(),
                'percentage' => $this->getPersonActionPercentage(),
                'has_experience' => $this->hasBullyingExperience(),
                'result_text' => $this->hasBullyingExperience() ? 'มีพฤติกรรมการกลั่นแกล้ง' : 'ไม่มีพฤติกรรมการกลั่นแกล้ง'
            ],
            'victim' => [
                'scores' => $this->getVictimScoresAttribute(),
                'total_score' => $this->getVictimScore(),
                'percentage' => $this->getVictimPercentage(),
                'has_experience' => $this->hasVictimExperience(),
                'result_text' => $this->hasVictimExperience() ? 'เคยถูกกลั่นแกล้ง' : 'ไม่เคยถูกกลั่นแกล้ง'
            ],
            'total' => [
                'total_score' => $this->getTotalScore(),
                'percentage' => $this->getTotalPercentage(),
                'is_completed' => $this->isBothAssessmentsCompleted()
            ]
        ];
    }

    public function convertToNewFormat()
    {
        $data = $this->assessment_data;
        $needsUpdate = false;
        
        if (!empty($data[0]) && !is_array($data[0][0] ?? null)) {
            $scores = [];
            $boolean = false;
            
            foreach ($data[0] as $item) {
                if (is_bool($item)) {
                    $boolean = $item;
                } elseif (is_numeric($item)) {
                    $scores[] = (int)$item;
                }
            }
            
            if (empty($scores)) {
                $boolean = array_sum($scores) > 0;
            }
            
            $data[0] = [$scores, [$boolean]];
            $needsUpdate = true;
        }
        
        if (!empty($data[1]) && !is_array($data[1][0] ?? null)) {
            $scores = [];
            $boolean = false;
            
            foreach ($data[1] as $item) {
                if (is_bool($item)) {
                    $boolean = $item;
                } elseif (is_numeric($item)) {
                    $scores[] = (int)$item;
                }
            }
            
            if (empty($scores)) {
                $boolean = array_sum($scores) > 0;
            }
            
            $data[1] = [$scores, [$boolean]];
            $needsUpdate = true;
        }
        
        if ($needsUpdate) {
            $this->update(['assessment_data' => $data]);
            return true;
        }
        
        return false;
    }
}