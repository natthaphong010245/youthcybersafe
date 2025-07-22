<?php

namespace App\Http\Controllers;

use App\Models\CyberbullyingAssessment;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    /**
     * Show the form for the combined assessment.
     */
    public function showForm()
    {
        return view('assessment.cyberbullying.overview.form.form');
    }

    /**
     * Process the form submission and store the assessments.
     */
    public function submitForm(Request $request)
    {
        // Validate all questions
        $validationRules = [];
        for ($i = 1; $i <= 18; $i++) {
            $validationRules['question' . $i] = 'required|integer|min:0|max:4';
        }
        
        $request->validate($validationRules);

        // Separate questions for person_action (1-9) and victim (10-18)
        $personActionQuestionsArray = [];
        $victimQuestionsArray = [];

        // Process person action questions (1-9)
        for ($i = 1; $i <= 9; $i++) {
            $personActionQuestionsArray[] = (int)$request->{'question' . $i};
        }

        // Process victim questions (10-18)
        for ($i = 10; $i <= 18; $i++) {
            $victimQuestionsArray[] = (int)$request->{'question' . $i};
        }

        // ✅ Create assessment data structure: [person_action, victim]
        $assessmentData = [
            $personActionQuestionsArray, // index 0: person_action
            $victimQuestionsArray        // index 1: victim
        ];

        // Create new assessment record in single table
        $assessment = CyberbullyingAssessment::create([
            'assessment_data' => $assessmentData
        ]);

        // Calculate scores for both assessments
        $personActionTotalScore = array_sum($personActionQuestionsArray);
        $victimTotalScore = array_sum($victimQuestionsArray);
        
        $maxPossibleScore = 9 * 4; // 9 questions with max value of 4 each
        
        $personActionPercentage = ($personActionTotalScore / $maxPossibleScore) * 100;
        $victimPercentage = ($victimTotalScore / $maxPossibleScore) * 100;

        // Store the scores in the session for the results page
        session([
            'person_action_score' => $personActionTotalScore,
            'person_action_percentage' => $personActionPercentage,
            'victim_score' => $victimTotalScore,
            'victim_percentage' => $victimPercentage,
            'assessment_id' => $assessment->id
        ]);

        // Redirect to the results page
        return redirect()->route('overview/result');
    }

    /**
     * Show the results page.
     */
    public function showResults()
    {
        // Get scores from session
        $personActionScore = session('person_action_score', 0);
        $personActionPercentage = session('person_action_percentage', 0);
        $victimScore = session('victim_score', 0);
        $victimPercentage = session('victim_percentage', 0);
        
        return view('assessment.cyberbullying.overview.form.result', compact(
            'personActionScore',
            'personActionPercentage',
            'victimScore',
            'victimPercentage'
        ));
    }
}