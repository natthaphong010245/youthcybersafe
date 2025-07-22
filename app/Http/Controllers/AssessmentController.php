<?php

namespace App\Http\Controllers;

use App\Models\CyberbullyingAssessment;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Show the form for victim assessment.
     */
    public function showVictimForm()
    {
        return view('assessment.cyberbullying.victim.form.form');
    }

    /**
     * Show the form for person action assessment.
     */
    public function showPersonActionForm()
    {
        return view('assessment.cyberbullying.person_action.form.form');
    }

    /**
     * Process the victim assessment form submission.
     */
    public function submitVictimForm(Request $request)
    {
        // Validate the request
        $request->validate([
            'question1' => 'required|integer|min:0|max:4',
            'question2' => 'required|integer|min:0|max:4',
            'question3' => 'required|integer|min:0|max:4',
            'question4' => 'required|integer|min:0|max:4',
            'question5' => 'required|integer|min:0|max:4',
            'question6' => 'required|integer|min:0|max:4',
            'question7' => 'required|integer|min:0|max:4',
            'question8' => 'required|integer|min:0|max:4',
            'question9' => 'required|integer|min:0|max:4',
        ]);

        // Collect all question answers into an array
        $questionsArray = [
            (int)$request->question1,
            (int)$request->question2,
            (int)$request->question3,
            (int)$request->question4,
            (int)$request->question5,
            (int)$request->question6,
            (int)$request->question7,
            (int)$request->question8,
            (int)$request->question9,
        ];

        // ✅ Create assessment data structure: [person_action, victim]
        $assessmentData = [
            [], // person_action array (empty)
            $questionsArray // victim array
        ];

        // Create new assessment record
        $assessment = CyberbullyingAssessment::create([
            'assessment_data' => $assessmentData
        ]);

        // Calculate total score
        $totalScore = array_sum($questionsArray);
        $maxPossibleScore = 9 * 4; // 9 questions with max value of 4 each
        $percentageScore = ($totalScore / $maxPossibleScore) * 100;

        // Store the score in the session for the results page
        session([
            'score' => $totalScore, 
            'percentage' => $percentageScore,
            'assessment_id' => $assessment->id
        ]);

        // Redirect to the results page
        return redirect()->route('victim/result');
    }

    /**
     * Process the person action assessment form submission.
     */
    public function submitPersonActionForm(Request $request)
    {
        // Validate the request
        $request->validate([
            'question1' => 'required|integer|min:0|max:4',
            'question2' => 'required|integer|min:0|max:4',
            'question3' => 'required|integer|min:0|max:4',
            'question4' => 'required|integer|min:0|max:4',
            'question5' => 'required|integer|min:0|max:4',
            'question6' => 'required|integer|min:0|max:4',
            'question7' => 'required|integer|min:0|max:4',
            'question8' => 'required|integer|min:0|max:4',
            'question9' => 'required|integer|min:0|max:4',
        ]);

        // Collect all question answers into an array
        $questionsArray = [
            (int)$request->question1,
            (int)$request->question2,
            (int)$request->question3,
            (int)$request->question4,
            (int)$request->question5,
            (int)$request->question6,
            (int)$request->question7,
            (int)$request->question8,
            (int)$request->question9,
        ];

        // ✅ Create assessment data structure: [person_action, victim]
        $assessmentData = [
            $questionsArray, // person_action array
            [] // victim array (empty)
        ];

        // Create new assessment record
        $assessment = CyberbullyingAssessment::create([
            'assessment_data' => $assessmentData
        ]);

        // Calculate total score
        $totalScore = array_sum($questionsArray);
        $maxPossibleScore = 9 * 4; // 9 questions with max value of 4 each
        $percentageScore = ($totalScore / $maxPossibleScore) * 100;

        // Store the score in the session for the results page
        session([
            'score' => $totalScore, 
            'percentage' => $percentageScore,
            'assessment_id' => $assessment->id
        ]);

        // Redirect to the results page
        return redirect()->route('person_action/result');
    }

    /**
     * Submit both assessments together
     */
    public function submitBothAssessments(Request $request)
    {
        // Validate both victim and person action questions
        $request->validate([
            'victim_question1' => 'required|integer|min:0|max:4',
            'victim_question2' => 'required|integer|min:0|max:4',
            'victim_question3' => 'required|integer|min:0|max:4',
            'victim_question4' => 'required|integer|min:0|max:4',
            'victim_question5' => 'required|integer|min:0|max:4',
            'victim_question6' => 'required|integer|min:0|max:4',
            'victim_question7' => 'required|integer|min:0|max:4',
            'victim_question8' => 'required|integer|min:0|max:4',
            'victim_question9' => 'required|integer|min:0|max:4',
            'person_action_question1' => 'required|integer|min:0|max:4',
            'person_action_question2' => 'required|integer|min:0|max:4',
            'person_action_question3' => 'required|integer|min:0|max:4',
            'person_action_question4' => 'required|integer|min:0|max:4',
            'person_action_question5' => 'required|integer|min:0|max:4',
            'person_action_question6' => 'required|integer|min:0|max:4',
            'person_action_question7' => 'required|integer|min:0|max:4',
            'person_action_question8' => 'required|integer|min:0|max:4',
            'person_action_question9' => 'required|integer|min:0|max:4',
        ]);

        // Collect person action assessment answers
        $personActionQuestions = [
            (int)$request->person_action_question1,
            (int)$request->person_action_question2,
            (int)$request->person_action_question3,
            (int)$request->person_action_question4,
            (int)$request->person_action_question5,
            (int)$request->person_action_question6,
            (int)$request->person_action_question7,
            (int)$request->person_action_question8,
            (int)$request->person_action_question9,
        ];

        // Collect victim assessment answers
        $victimQuestions = [
            (int)$request->victim_question1,
            (int)$request->victim_question2,
            (int)$request->victim_question3,
            (int)$request->victim_question4,
            (int)$request->victim_question5,
            (int)$request->victim_question6,
            (int)$request->victim_question7,
            (int)$request->victim_question8,
            (int)$request->victim_question9,
        ];

        // ✅ Create assessment data structure: [person_action, victim]
        $assessmentData = [
            $personActionQuestions, // person_action array (index 0)
            $victimQuestions        // victim array (index 1)
        ];

        // Create new assessment record
        $assessment = CyberbullyingAssessment::create([
            'assessment_data' => $assessmentData
        ]);

        // Calculate scores for both assessments
        $victimScore = array_sum($victimQuestions);
        $personActionScore = array_sum($personActionQuestions);
        $totalScore = $victimScore + $personActionScore;
        $maxPossibleScore = 18 * 4; // 18 questions total with max value of 4 each
        $percentageScore = ($totalScore / $maxPossibleScore) * 100;

        // Store the scores in the session for the results page
        session([
            'victim_score' => $victimScore,
            'person_action_score' => $personActionScore,
            'total_score' => $totalScore,
            'percentage' => $percentageScore,
            'assessment_id' => $assessment->id
        ]);

        // Redirect to the combined results page
        return redirect()->route('cyberbullying/result');
    }

    /**
     * Show the victim assessment results page.
     */
    public function showVictimResults()
    {
        $score = session('score', 0);
        $percentage = session('percentage', 0);

        return view('assessment.cyberbullying.victim.form.result', compact('score', 'percentage'));
    }

    /**
     * Show the person action assessment results page.
     */
    public function showPersonActionResults()
    {
        $score = session('score', 0);
        $percentage = session('percentage', 0);

        return view('assessment.cyberbullying.person_action.form.result', compact('score', 'percentage'));
    }

    /**
     * Show the combined assessment results page.
     */
    public function showCombinedResults()
    {
        $victimScore = session('victim_score', 0);
        $personActionScore = session('person_action_score', 0);
        $totalScore = session('total_score', 0);
        $percentage = session('percentage', 0);

        return view('assessment.cyberbullying.combined.result', compact(
            'victimScore', 
            'personActionScore', 
            'totalScore', 
            'percentage'
        ));
    }
}