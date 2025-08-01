<?php

namespace App\Http\Controllers;

use App\Models\CyberbullyingAssessment;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{

    public function showVictimForm()
    {
        return view('assessment.cyberbullying.victim.form.form');
    }

    public function showPersonActionForm()
    {
        return view('assessment.cyberbullying.person_action.form.form');
    }

    public function showOverviewForm()
    {
        return view('assessment.cyberbullying.overview.form.form');
    }


    public function submitVictimForm(Request $request)
    {
        $validationRules = [];
        for ($i = 1; $i <= 9; $i++) {
            $validationRules["question{$i}"] = 'required|integer|min:0|max:4';
        }
        $request->validate($validationRules);

        $scores = [];
        for ($i = 1; $i <= 9; $i++) {
            $scores[] = (int)$request->{"question{$i}"};
        }

        $totalScore = array_sum($scores);
        $hasVictimExperience = $totalScore > 0;

        $assessmentData = [
            [], 
            [$scores, [$hasVictimExperience]] 
        ];

        $assessment = CyberbullyingAssessment::create([
            'assessment_data' => $assessmentData
        ]);

        $maxPossibleScore = 9 * 4;
        $percentage = ($totalScore / $maxPossibleScore) * 100;

        session([
            'score' => $totalScore,
            'percentage' => $percentage,
            'hasVictimExperience' => $hasVictimExperience,
            'assessment_id' => $assessment->id
        ]);

        return redirect()->route('victim/result');
    }

    public function submitPersonActionForm(Request $request)
    {
        $validationRules = [];
        for ($i = 1; $i <= 9; $i++) {
            $validationRules["question{$i}"] = 'required|integer|min:0|max:4';
        }
        $request->validate($validationRules);

        $scores = [];
        for ($i = 1; $i <= 9; $i++) {
            $scores[] = (int)$request->{"question{$i}"};
        }

        $totalScore = array_sum($scores);
        $hasPersonActionExperience = $totalScore > 0;

        $assessmentData = [
            [$scores, [$hasPersonActionExperience]], 
            []
        ];

        $assessment = CyberbullyingAssessment::create([
            'assessment_data' => $assessmentData
        ]);

        $maxPossibleScore = 9 * 4;
        $percentage = ($totalScore / $maxPossibleScore) * 100;

        session([
            'score' => $totalScore,
            'percentage' => $percentage,
            'hasPersonActionExperience' => $hasPersonActionExperience,
            'assessment_id' => $assessment->id
        ]);

        return redirect()->route('person_action/result');
    }

    public function submitOverviewForm(Request $request)
    {
        $validationRules = [];
        for ($i = 1; $i <= 18; $i++) {
            $validationRules["question{$i}"] = 'required|integer|min:0|max:4';
        }
        $request->validate($validationRules);

        $personActionScores = [];
        for ($i = 1; $i <= 9; $i++) {
            $personActionScores[] = (int)$request->{"question{$i}"};
        }

        $victimScores = [];
        for ($i = 10; $i <= 18; $i++) {
            $victimScores[] = (int)$request->{"question{$i}"};
        }

        $personActionScore = array_sum($personActionScores);
        $victimScore = array_sum($victimScores);
        $hasPersonActionExperience = $personActionScore > 0;
        $hasVictimExperience = $victimScore > 0;

        $assessmentData = [
            [$personActionScores, [$hasPersonActionExperience]], 
            [$victimScores, [$hasVictimExperience]]  
        ];

        $assessment = CyberbullyingAssessment::create([
            'assessment_data' => $assessmentData
        ]);

        $maxPossibleScore = 9 * 4;
        $personActionPercentage = ($personActionScore / $maxPossibleScore) * 100;
        $victimPercentage = ($victimScore / $maxPossibleScore) * 100;
        $totalScore = $personActionScore + $victimScore;
        $totalPercentage = ($totalScore / (18 * 4)) * 100;

        session([
            'personActionScore' => $personActionScore,
            'victimScore' => $victimScore,
            'personActionPercentage' => $personActionPercentage,
            'victimPercentage' => $victimPercentage,
            'totalScore' => $totalScore,
            'totalPercentage' => $totalPercentage,
            'hasPersonActionExperience' => $hasPersonActionExperience,
            'hasVictimExperience' => $hasVictimExperience,
            'assessment_id' => $assessment->id
        ]);

        return redirect()->route('cyberbullying/overview/result');
    }


    public function showVictimResults()
    {
        $score = session('score', 0);
        $percentage = session('percentage', 0);
        $hasVictimExperience = session('hasVictimExperience', false);

        return view('assessment.cyberbullying.victim.form.result', compact(
            'score', 
            'percentage', 
            'hasVictimExperience'
        ));
    }

    public function showPersonActionResults()
    {
        $score = session('score', 0);
        $percentage = session('percentage', 0);
        $hasPersonActionExperience = session('hasPersonActionExperience', false);

        return view('assessment.cyberbullying.person_action.form.result', compact(
            'score', 
            'percentage', 
            'hasPersonActionExperience'
        ));
    }

    public function showOverviewResults()
    {
        $data = [
            'personActionScore' => session('personActionScore', 0),
            'victimScore' => session('victimScore', 0),
            'personActionPercentage' => session('personActionPercentage', 0),
            'victimPercentage' => session('victimPercentage', 0),
            'totalScore' => session('totalScore', 0),
            'totalPercentage' => session('totalPercentage', 0),
            'hasPersonActionExperience' => session('hasPersonActionExperience', false),
            'hasVictimExperience' => session('hasVictimExperience', false)
        ];

        return view('assessment.cyberbullying.overview.form.result', $data);
    }
}