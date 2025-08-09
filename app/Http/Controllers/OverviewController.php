<?php
namespace App\Http\Controllers;

use App\Models\CyberbullyingAssessment;
use Illuminate\Http\Request;

class OverviewController extends Controller
{

    public function showForm()
    {
        return view('assessment.cyberbullying.overview.form.form');
    }


    public function submitForm(Request $request)
    {
        $validationRules = [];
        for ($i = 1; $i <= 18; $i++) {
            $validationRules['question' . $i] = 'required|integer|min:0|max:4';
        }
        
        $request->validate($validationRules);

        $personActionQuestionsArray = [];
        $victimQuestionsArray = [];

        for ($i = 1; $i <= 9; $i++) {
            $personActionQuestionsArray[] = (int)$request->{'question' . $i};
        }

        for ($i = 10; $i <= 18; $i++) {
            $victimQuestionsArray[] = (int)$request->{'question' . $i};
        }

        $personActionTotalScore = array_sum($personActionQuestionsArray);
        $victimTotalScore = array_sum($victimQuestionsArray);
        
        $hasPersonActionExperience = $personActionTotalScore > 0;
        $hasVictimExperience = $victimTotalScore > 0;

        $assessmentData = [
            [$personActionQuestionsArray, [$hasPersonActionExperience]], 
            [$victimQuestionsArray, [$hasVictimExperience]]        
        ];

        $assessment = CyberbullyingAssessment::create([
            'assessment_data' => $assessmentData
        ]);

        $maxPossibleScore = 9 * 4; 
        
        $personActionPercentage = ($personActionTotalScore / $maxPossibleScore) * 100;
        $victimPercentage = ($victimTotalScore / $maxPossibleScore) * 100;

        session([
            'personActionScore' => $personActionTotalScore,
            'personActionPercentage' => $personActionPercentage,
            'victimScore' => $victimTotalScore,
            'victimPercentage' => $victimPercentage,
            'hasPersonActionExperience' => $hasPersonActionExperience,
            'hasVictimExperience' => $hasVictimExperience,
            'assessment_id' => $assessment->id
        ]);

        return redirect()->route('overview/result');
    }

    public function showResults()
    {
        $personActionScore = session('personActionScore', 0);
        $personActionPercentage = session('personActionPercentage', 0);
        $victimScore = session('victimScore', 0);
        $victimPercentage = session('victimPercentage', 0);
        $hasPersonActionExperience = session('hasPersonActionExperience', false);
        $hasVictimExperience = session('hasVictimExperience', false);
        
        return view('assessment.cyberbullying.overview.form.result', compact(
            'personActionScore',
            'personActionPercentage',
            'victimScore',
            'victimPercentage',
            'hasPersonActionExperience',
            'hasVictimExperience'
        ));
    }
}