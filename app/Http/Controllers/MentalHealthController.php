<?php
namespace App\Http\Controllers;

use App\Models\MentalHealthAssessment;
use Illuminate\Http\Request;

class MentalHealthController extends Controller
{
    public function showForm()
    {
        return view('assessment.mental_health.form.form');
    }

    public function submitForm(Request $request)
    {
        $validationRules = [];
        for ($i = 1; $i <= 21; $i++) {
            $validationRules['question' . $i] = 'required|integer|min:0|max:3';
        }
        
        $request->validate($validationRules);

        $stressQuestions = [];
        $anxietyQuestions = [];
        $depressionQuestions = [];

        for ($i = 1; $i <= 7; $i++) {
            $stressQuestions[] = (int)$request->{'question' . $i};
        }

        for ($i = 8; $i <= 14; $i++) {
            $anxietyQuestions[] = (int)$request->{'question' . $i};
        }

        for ($i = 15; $i <= 21; $i++) {
            $depressionQuestions[] = (int)$request->{'question' . $i};
        }

        $stressScore = array_sum($stressQuestions);
        $anxietyScore = array_sum($anxietyQuestions);
        $depressionScore = array_sum($depressionQuestions);

        $stressLevel = $this->calculateStressLevel($stressScore);
        $anxietyLevel = $this->calculateAnxietyLevel($anxietyScore);
        $depressionLevel = $this->calculateDepressionLevel($depressionScore);

        $assessment = MentalHealthAssessment::create([
            'stress' => [$stressQuestions, $stressLevel],
            'anxiety' => [$anxietyQuestions, $anxietyLevel],
            'depression' => [$depressionQuestions, $depressionLevel]
        ]);

        session([
            'stress_score' => $stressScore,
            'anxiety_score' => $anxietyScore,
            'depression_score' => $depressionScore,
            'stress_level' => $stressLevel,
            'anxiety_level' => $anxietyLevel,
            'depression_level' => $depressionLevel
        ]);

        return redirect()->route('mental_health/result');
    }

    public function showResults()
    {
        $stressScore = session('stress_score', 0);
        $anxietyScore = session('anxiety_score', 0);
        $depressionScore = session('depression_score', 0);
        
        return view('assessment.mental_health.form.result', compact(
            'stressScore',
            'anxietyScore',
            'depressionScore'
        ));
    }

    private function calculateStressLevel($score)
    {
        if ($score <= 7) {
            return 'normal';
        } elseif ($score <= 9) {
            return 'mild';
        } elseif ($score <= 12) {
            return 'moderate';
        } elseif ($score <= 16) {
            return 'severe';
        } else {
            return 'verysevere';
        }
    }

    private function calculateAnxietyLevel($score)
    {
        if ($score <= 3) {
            return 'normal';
        } elseif ($score <= 5) {
            return 'mild';
        } elseif ($score <= 7) {
            return 'moderate';
        } elseif ($score <= 9) {
            return 'severe';
        } else {
            return 'verysevere';
        }
    }

    private function calculateDepressionLevel($score)
    {
        if ($score <= 4) {
            return 'normal';
        } elseif ($score <= 6) {
            return 'mild';
        } elseif ($score <= 10) {
            return 'moderate';
        } elseif ($score <= 13) {
            return 'severe';
        } else {
            return 'verysevere';
        }
    }
}