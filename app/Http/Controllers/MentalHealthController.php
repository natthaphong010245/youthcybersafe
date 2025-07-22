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
        // สร้าง validation rules
        $validationRules = [];
        for ($i = 1; $i <= 21; $i++) {
            $validationRules['question' . $i] = 'required|integer|min:0|max:3';
        }
        
        $request->validate($validationRules);

        // แยกคำถามตามหมวดหมู่
        $stressQuestions = [];
        $anxietyQuestions = [];
        $depressionQuestions = [];

        // ด้านความเครียด (ข้อ 1-7)
        for ($i = 1; $i <= 7; $i++) {
            $stressQuestions[] = (int)$request->{'question' . $i};
        }

        // ด้านภาวะวิตกกังวล (ข้อ 8-14)
        for ($i = 8; $i <= 14; $i++) {
            $anxietyQuestions[] = (int)$request->{'question' . $i};
        }

        // ด้านภาวะซึมเศร้า (ข้อ 15-21)
        for ($i = 15; $i <= 21; $i++) {
            $depressionQuestions[] = (int)$request->{'question' . $i};
        }

        // บันทึกข้อมูล
        $assessment = MentalHealthAssessment::create([
            'stress' => $stressQuestions,
            'anxiety' => $anxietyQuestions,
            'depression' => $depressionQuestions
        ]);

        // คำนวณคะแนน
        $stressScore = array_sum($stressQuestions);
        $anxietyScore = array_sum($anxietyQuestions);
        $depressionScore = array_sum($depressionQuestions);

        // เก็บคะแนนใน session
        session([
            'stress_score' => $stressScore,
            'anxiety_score' => $anxietyScore,
            'depression_score' => $depressionScore
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
}