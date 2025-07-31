<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'stats' => [
                'assessment' => 1202,
                'mental_health' => 212,
                'behavioral_report' => 100,
                'safe_area' => 60
            ],
            'action_experiences' => [
                'total' => 16,
                'assessed' => 8,
                'percentage' => 50
            ],
            'victim_experiences' => [
                'total' => 21,
                'assessed' => 16,
                'percentage' => 76
            ],
            'mental_health_data' => [
                'ระดับปกติ' => ['serious' => 25, 'moderate' => 12, 'mild' => 15],
                'ระดับเล็กน้อย' => ['serious' => 18, 'moderate' => 25, 'mild' => 10],
                'ระดับปานกลาง' => ['serious' => 22, 'moderate' => 8, 'mild' => 28],
                'ระดับรุนแรง' => ['serious' => 26, 'moderate' => 12, 'mild' => 5],
                'ระดับรุนแรงมาก' => ['serious' => 5, 'moderate' => 26, 'mild' => 15]
            ],
            'behavioral_schools' => [
                'โรงเรียนวารีวิทยาคม' => 10,
                'โรงเรียนสหศาสตร์ศึกษา' => 8,
                'โรงเรียนราชประชานุเคราะห์ 62' => 4,
                'โรงเรียนห้วยไร่สามัคคี' => 15,
            ],
            'safe_area' => [
                'voice_reports' => 25,
                'message_reports' => 35
            ]
        ];

        return view('dashboard.index', compact('data'));
    }

    public function assessment()
    {
        $data = [
            'action_experiences' => [
                'total' => 16,
                'assessed' => 8,
                'percentage' => 50
            ],
            'victim_experiences' => [
                'total' => 21,
                'assessed' => 16,
                'percentage' => 76
            ],
            // Updated to use mental health severity levels instead of education levels
            'mental_health_data' => [
                'ระดับปกติ' => ['serious' => 25, 'moderate' => 12, 'mild' => 15],
                'ระดับเล็กน้อย' => ['serious' => 18, 'moderate' => 25, 'mild' => 10],
                'ระดับปานกลาง' => ['serious' => 22, 'moderate' => 8, 'mild' => 28],
                'ระดับรุนแรง' => ['serious' => 26, 'moderate' => 12, 'mild' => 5],
                'ระดับรุนแรงมาก' => ['serious' => 5, 'moderate' => 26, 'mild' => 15]
            ]
        ];

        return view('dashboard.assessment', compact('data'));
    }

    public function behavioralReport()
    {
        $data = [
            'overview' => [
                'โรงเรียนวารีวิทยาคม' => 10,
                'โรงเรียนสหศาสตร์ศึกษา' => 8,
                'โรงเรียนราชประชานุเคราะห์ 62' => 4,
                'โรงเรียนห้วยไร่สามัคคี' => 15
            ],
            'schools_data' => [
                'โรงเรียนวารีวิทยาคม' => 25,
                'โรงเรียนสหศาสตร์ศึกษา' => 32,
                'โรงเรียนราชประชานุเคราะห์ 62' => 4,
                'โรงเรียนห้วยไร่สามัคคี' => 16
            ]
        ];

        return view('dashboard.behavioral-report', compact('data'));
    }

    public function safeArea()
    {
        $data = [
            'voice_reports' => 25,
            'message_reports' => 35,
            'monthly_data' => [
                'voice' => [15, 18, 16, 22, 28, 25, 20, 18, 24, 28, 26, 24],
                'message' => [20, 25, 22, 30, 35, 28, 26, 22, 28, 32, 30, 28]
            ],
            // Added mental health data for the chart
            'mental_health_data' => [
                'ระดับปกติ' => ['serious' => 25, 'moderate' => 12, 'mild' => 15],
                'ระดับเล็กน้อย' => ['serious' => 18, 'moderate' => 25, 'mild' => 10],
                'ระดับปานกลาง' => ['serious' => 22, 'moderate' => 8, 'mild' => 28],
                'ระดับรุนแรง' => ['serious' => 26, 'moderate' => 12, 'mild' => 5],
                'ระดับรุนแรงมาก' => ['serious' => 5, 'moderate' => 26, 'mild' => 15]
            ]
        ];

        return view('dashboard.safe-area', compact('data'));
    }
}