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
}