<?php
//app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CyberbullyingAssessment;
use App\Models\MentalHealthAssessment;
use App\Models\SafeArea;
use App\Models\ReportConsultation\BehavioralReportReportConsultation;

class DashboardController extends Controller
{
    public function index()
    {
        $assessmentStats = $this->getCyberbullyingStats();
        $mentalHealthData = $this->getMentalHealthStats();
        $safeAreaStats = $this->getSafeAreaStats();
        $behavioralSchoolsData = $this->getBehavioralSchoolsData();
        
        $data = [
            'stats' => [
                'assessment' => $assessmentStats['total_assessments'],
                'mental_health' => MentalHealthAssessment::count(),
                'behavioral_report' => BehavioralReportReportConsultation::count(), 
                'safe_area' => SafeArea::count() 
            ],
            'action_experiences' => $assessmentStats['action_experiences'],
            'victim_experiences' => $assessmentStats['victim_experiences'],
            'mental_health_data' => $mentalHealthData,
            'behavioral_schools' => $behavioralSchoolsData,
            'safe_area' => [
                'voice_reports' => $safeAreaStats['voice'],
                'message_reports' => $safeAreaStats['message']
            ]
        ];

        return view('dashboard.index', compact('data'));
    }

    public function assessment()
    {
        $assessmentStats = $this->getCyberbullyingStats();
        $mentalHealthData = $this->getMentalHealthStats();
        
        $data = [
            'action_experiences' => $assessmentStats['action_experiences'],
            'victim_experiences' => $assessmentStats['victim_experiences'],
            'mental_health_data' => $mentalHealthData
        ];

        return view('dashboard.assessment', compact('data'));
    }

    public function behavioralReport(Request $request)
    {
        $statusFilter = $request->get('status');
        $page = (int) $request->get('page', 1);
        $perPage = 7;
        
        // Get behavioral schools data
        $behavioralSchoolsData = $this->getBehavioralSchoolsData();
        
        // Get behavioral overview data
        $behavioralOverviewData = $this->getBehavioralOverviewData();
        
        // Get reports with filtering and pagination - ONLY RESEARCHER REPORTS
        $query = BehavioralReportReportConsultation::where('who', 'researcher')
                    ->orderBy('created_at', 'desc');
        
        // Apply status filter
        if ($statusFilter && $statusFilter !== '') {
            if ($statusFilter === 'pending') {
                $query->where('status', false);
            } elseif ($statusFilter === 'reviewed') {
                $query->where('status', true);
            }
        }
        
        $totalCount = $query->count();
        $reports = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
        
        // Transform reports for view
        $transformedReports = $reports->map(function($report) {
            return [
                'id' => $report->id,
                'date' => $report->created_at->format('d/m/Y'),
                'message' => $report->message,
                'status' => $report->status ? 'reviewed' : 'pending'
            ];
        });
        
        $totalPages = $totalCount > 0 ? ceil($totalCount / $perPage) : 1;
        if ($page > $totalPages) {
            $page = 1;
        }
        
        $data = [
            'overview' => $behavioralOverviewData,
            'schools_data' => $behavioralSchoolsData,
            'reports' => $transformedReports,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total' => $totalCount,
                'per_page' => $perPage,
                'has_more_pages' => $page < $totalPages,
                'from' => $totalCount > 0 ? (($page - 1) * $perPage) + 1 : 0,
                'to' => min($page * $perPage, $totalCount)
            ],
            'current_filter' => $statusFilter,
            'total_before_filter' => BehavioralReportReportConsultation::where('who', 'researcher')->count()
        ];

        if ($request->ajax()) {
            return response()->json($data);
        }

        return view('dashboard.behavioral-report', compact('data'));
    }

    public function updateReportStatus(Request $request, $id)
    {
        try {
            $report = BehavioralReportReportConsultation::findOrFail($id);
            $report->status = true;
            $report->save();
            
            return response()->json([
                'success' => true, 
                'message' => 'สถานะได้รับการอัปเดตแล้ว'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'เกิดข้อผิดพลาดในการอัปเดตสถานะ'
            ], 500);
        }
    }

    public function getReportDetail($id)
    {
        try {
            $report = BehavioralReportReportConsultation::findOrFail($id);
            
            $images = [];
            if ($report->image && !empty($report->image)) {
                $decodedImages = json_decode($report->image, true);
                if (is_array($decodedImages) && count($decodedImages) > 0) {
                    $images = array_filter($decodedImages, function($img) {
                        return !empty($img) && $img !== null;
                    });
                    $images = array_values($images); 
                }
            }
            
            return response()->json([
                'id' => $report->id,
                'date' => $report->created_at->format('m/d/Y'),
                'message' => $report->message,
                'images' => $images,
                'audio' => $report->voice,
                'latitude' => $report->latitude,
                'longitude' => $report->longitude,
                'location' => $this->getLocationFromCoordinates($report->latitude, $report->longitude),
                'status' => $report->status ? 'reviewed' : 'pending'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'ไม่พบข้อมูลรายงาน'
            ], 404);
        }
    }

    private function getLocationFromCoordinates($latitude, $longitude)
    {
        if (!$latitude || !$longitude) {
            return 'Thailand';
        }

        return 'Thailand';
    }

    private function getBehavioralSchoolsData()
    {
        $researcherCount = BehavioralReportReportConsultation::where('who', 'researcher')->count();
        
        $schoolCounts = [
            'โรงเรียนวาวีวิทยาคม' => BehavioralReportReportConsultation::where('school', 'โรงเรียนวาวีวิทยาคม')->count(),
            'โรงเรียนสหศาสตร์ศึกษา' => BehavioralReportReportConsultation::where('school', 'โรงเรียนสหศาสตร์ศึกษา')->count(),
            'โรงเรียนราชประชานุเคราะห์ 62' => BehavioralReportReportConsultation::where('school', 'โรงเรียนราชประชานุเคราะห์ 62')->count(),
            'โรงเรียนห้วยไร่สามัคคี' => BehavioralReportReportConsultation::where('school', 'โรงเรียนห้วยไร่สามัคคี')->count(),
        ];
        
        return [
            'นักวิจัย' => $researcherCount,
            'โรงเรียนวาวีวิทยาคม' => $schoolCounts['โรงเรียนวาวีวิทยาคม'],
            'โรงเรียนสหศาสตร์ศึกษา' => $schoolCounts['โรงเรียนสหศาสตร์ศึกษา'],
            'โรงเรียนราชประชานุเคราะห์ 62' => $schoolCounts['โรงเรียนราชประชานุเคราะห์ 62'],
            'โรงเรียนห้วยไร่สามัคคี' => $schoolCounts['โรงเรียนห้วยไร่สามัคคี']
        ];
    }

    private function getBehavioralOverviewData()
    {
        return $this->getBehavioralSchoolsData();
    }

    public function safeArea()
    {
        $currentYear = date('Y');
        $safeAreaStats = $this->getSafeAreaStats();
        $monthlyData = $this->getSafeAreaMonthlyData($currentYear);
        $mentalHealthData = $this->getMentalHealthStats();
        
        $data = [
            'voice_reports' => $safeAreaStats['voice'],
            'message_reports' => $safeAreaStats['message'],
            'monthly_data' => $monthlyData,
            'mental_health_data' => $mentalHealthData,
            'current_year' => $currentYear,
            'available_years' => range(2025, 2035)
        ];

        return view('dashboard.safe-area', compact('data'));
    }

    private function getMentalHealthStats()
    {
        $assessments = MentalHealthAssessment::all();
        
        $stressLevels = ['normal' => 0, 'mild' => 0, 'moderate' => 0, 'severe' => 0, 'verysevere' => 0];
        $anxietyLevels = ['normal' => 0, 'mild' => 0, 'moderate' => 0, 'severe' => 0, 'verysevere' => 0];
        $depressionLevels = ['normal' => 0, 'mild' => 0, 'moderate' => 0, 'severe' => 0, 'verysevere' => 0];
        
        foreach ($assessments as $assessment) {
            $stressLevel = $assessment->stress_level ?? 'normal';
            $anxietyLevel = $assessment->anxiety_level ?? 'normal';
            $depressionLevel = $assessment->depression_level ?? 'normal';
            
            if (isset($stressLevels[$stressLevel])) {
                $stressLevels[$stressLevel]++;
            }
            if (isset($anxietyLevels[$anxietyLevel])) {
                $anxietyLevels[$anxietyLevel]++;
            }
            if (isset($depressionLevels[$depressionLevel])) {
                $depressionLevels[$depressionLevel]++;
            }
        }
        
        $levelNames = [
            'normal' => 'ระดับปกติ',
            'mild' => 'ระดับเล็กน้อย',
            'moderate' => 'ระดับปานกลาง',
            'severe' => 'ระดับรุนแรง',
            'verysevere' => 'ระดับรุนแรงมาก'
        ];
        
        $result = [];
        foreach ($levelNames as $level => $levelName) {
            $result[$levelName] = [
                'serious' => $depressionLevels[$level], 
                'moderate' => $anxietyLevels[$level],  
                'mild' => $stressLevels[$level]       
            ];
        }
        
        return $result;
    }

    private function getSafeAreaStats()
    {
        $voiceCount = SafeArea::whereRaw("JSON_EXTRACT(voice_message, '$[0][0]') = 1")
                             ->whereRaw("JSON_EXTRACT(voice_message, '$[1][0]') = 0")
                             ->count();
        
        $messageCount = SafeArea::whereRaw("JSON_EXTRACT(voice_message, '$[0][0]') = 0")
                               ->whereRaw("JSON_EXTRACT(voice_message, '$[1][0]') = 1")
                               ->count();
        
        $total = $voiceCount + $messageCount;
        
        return [
            'total' => $total,
            'voice' => $voiceCount,
            'message' => $messageCount,
            'voice_percentage' => $total > 0 ? round(($voiceCount / $total) * 100, 2) : 0,
            'message_percentage' => $total > 0 ? round(($messageCount / $total) * 100, 2) : 0,
        ];
    }

    private function getSafeAreaMonthlyData($year = null)
    {
        if ($year === null) {
            $year = date('Y');
        }
        
        $monthlyStats = SafeArea::selectRaw('
                MONTH(created_at) as month,
                SUM(CASE 
                    WHEN JSON_EXTRACT(voice_message, "$[0][0]") = 1 
                    AND JSON_EXTRACT(voice_message, "$[1][0]") = 0 
                    THEN 1 ELSE 0 
                END) as voice_count,
                SUM(CASE 
                    WHEN JSON_EXTRACT(voice_message, "$[0][0]") = 0 
                    AND JSON_EXTRACT(voice_message, "$[1][0]") = 1 
                    THEN 1 ELSE 0 
                END) as message_count
            ')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $voiceData = [];
        $messageData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $voiceData[] = $monthlyStats->get($month)->voice_count ?? 0;
            $messageData[] = $monthlyStats->get($month)->message_count ?? 0;
        }
        
        return [
            'voice' => $voiceData,
            'message' => $messageData,
            'year' => $year
        ];
    }

    public function getSafeAreaDataByYear(Request $request, $year = null)
    {
        if ($year === null) {
            $year = $request->get('year', date('Y'));
        }
        
        if ($year < 2025 || $year > 2035) {
            return response()->json([
                'error' => 'ปีต้องอยู่ระหว่าง 2025-2035'
            ], 400);
        }
        
        $monthlyData = $this->getSafeAreaMonthlyData($year);
        $stats = $this->getSafeAreaStats();
        
        return response()->json([
            'monthly_data' => $monthlyData,
            'statistics' => $stats,
            'year' => $year,
            'available_years' => range(2025, 2035)
        ]);
    }

    public function getAvailableYears()
    {
        $years = SafeArea::selectRaw('DISTINCT YEAR(created_at) as year')
                        ->whereNotNull('created_at')
                        ->orderBy('year', 'desc')
                        ->pluck('year')
                        ->filter(function($year) {
                            return $year >= 2025 && $year <= 2035;
                        })
                        ->values();
        
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }
        
        return response()->json([
            'years' => $years,
            'current_year' => date('Y'),
            'default_range' => range(2025, 2035)
        ]);
    }

    private function getCyberbullyingStats()
    {
        $assessments = CyberbullyingAssessment::all();
        $totalAssessments = $assessments->count();
        
        $personActionTrue = 0;
        $personActionFalse = 0;
        $personActionTotal = 0;
        
        $victimTrue = 0;
        $victimFalse = 0;
        $victimTotal = 0;
        
        foreach ($assessments as $assessment) {
            $data = $assessment->assessment_data;
            
            $personActionData = $data[0] ?? [];
            if (!empty($personActionData)) {
                $personActionTotal++;
                
                if (is_array($personActionData) && isset($personActionData[1]) && is_array($personActionData[1])) {
                    $result = $personActionData[1][0] ?? false;
                    if ($result === true) {
                        $personActionTrue++;
                    } else {
                        $personActionFalse++;
                    }
                }
                elseif (is_array($personActionData) && !empty($personActionData)) {
                    $lastElement = end($personActionData);
                    if (is_bool($lastElement)) {
                        if ($lastElement === true) {
                            $personActionTrue++;
                        } else {
                            $personActionFalse++;
                        }
                    } else {
                        $scores = array_filter($personActionData, 'is_numeric');
                        $sum = array_sum($scores);
                        if ($sum > 0) {
                            $personActionTrue++;
                        } else {
                            $personActionFalse++;
                        }
                    }
                }
            }
            
            $victimData = $data[1] ?? [];
            if (!empty($victimData)) {
                $victimTotal++;
                
                if (is_array($victimData) && isset($victimData[1]) && is_array($victimData[1])) {
                    $result = $victimData[1][0] ?? false;
                    if ($result === true) {
                        $victimTrue++;
                    } else {
                        $victimFalse++;
                    }
                }
                elseif (is_array($victimData) && !empty($victimData)) {
                    $lastElement = end($victimData);
                    if (is_bool($lastElement)) {
                        if ($lastElement === true) {
                            $victimTrue++;
                        } else {
                            $victimFalse++;
                        }
                    } else {
                        $scores = array_filter($victimData, 'is_numeric');
                        $sum = array_sum($scores);
                        if ($sum > 0) {
                            $victimTrue++;
                        } else {
                            $victimFalse++;
                        }
                    }
                }
            }
        }
        
        $personActionPercentage = $personActionTotal > 0 ? round(($personActionTrue / $personActionTotal) * 100) : 0;
        $victimPercentage = $victimTotal > 0 ? round(($victimTrue / $victimTotal) * 100) : 0;
        
        return [
            'total_assessments' => $totalAssessments,
            'action_experiences' => [
                'total' => $personActionTotal,
                'assessed' => $personActionTrue,
                'not_assessed' => $personActionFalse,
                'percentage' => $personActionPercentage
            ],
            'victim_experiences' => [
                'total' => $victimTotal,
                'assessed' => $victimTrue,
                'not_assessed' => $victimFalse,
                'percentage' => $victimPercentage
            ]
        ];
    }

    public function getAssessmentStats()
    {
        $stats = $this->getCyberbullyingStats();
        return response()->json($stats);
    }

    public function getMentalHealthStatsApi()
    {
        $stats = $this->getMentalHealthStats();
        return response()->json($stats);
    }

    public function getSafeAreaStatsApi()
    {
        $stats = $this->getSafeAreaStats();
        $monthlyData = $this->getSafeAreaMonthlyData();
        
        return response()->json([
            'statistics' => $stats,
            'monthly_data' => $monthlyData
        ]);
    }

    public function debugAssessmentData()
    {
        $assessments = CyberbullyingAssessment::all();
        $debugData = [];
        
        foreach ($assessments as $assessment) {
            $data = $assessment->assessment_data;
            $personActionData = $data[0] ?? [];
            $victimData = $data[1] ?? [];
            
            $debugData[] = [
                'id' => $assessment->id,
                'created_at' => $assessment->created_at,
                'raw_data' => $data,
                'person_action' => [
                    'data' => $personActionData,
                    'has_data' => !empty($personActionData),
                    'scores' => $this->extractScores($personActionData),
                    'result' => $this->extractBoolean($personActionData)
                ],
                'victim' => [
                    'data' => $victimData,
                    'has_data' => !empty($victimData),
                    'scores' => $this->extractScores($victimData),
                    'result' => $this->extractBoolean($victimData)
                ]
            ];
        }
        
        return response()->json($debugData);
    }

    public function debugMentalHealthData()
    {
        $assessments = MentalHealthAssessment::all();
        $debugData = [];
        
        foreach ($assessments as $assessment) {
            $debugData[] = [
                'id' => $assessment->id,
                'created_at' => $assessment->created_at,
                'stress' => [
                    'raw_data' => $assessment->stress,
                    'scores' => $assessment->stress_scores,
                    'level' => $assessment->stress_level,
                    'level_thai' => $assessment->stress_level_thai,
                    'total_score' => $assessment->stress_total_score
                ],
                'anxiety' => [
                    'raw_data' => $assessment->anxiety,
                    'scores' => $assessment->anxiety_scores,
                    'level' => $assessment->anxiety_level,
                    'level_thai' => $assessment->anxiety_level_thai,
                    'total_score' => $assessment->anxiety_total_score
                ],
                'depression' => [
                    'raw_data' => $assessment->depression,
                    'scores' => $assessment->depression_scores,
                    'level' => $assessment->depression_level,
                    'level_thai' => $assessment->depression_level_thai,
                    'total_score' => $assessment->depression_total_score
                ]
            ];
        }
        
        return response()->json([
            'mental_health_assessments' => $debugData,
            'statistics' => $this->getMentalHealthStats()
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function debugSafeAreaData()
    {
        $records = SafeArea::latest()->take(20)->get();
        $debugData = [];
        
        foreach ($records as $record) {
            $isVoice = isset($record->voice_message[0][0]) && $record->voice_message[0][0] == 1 
                    && isset($record->voice_message[1][0]) && $record->voice_message[1][0] == 0;
            
            $isMessage = isset($record->voice_message[0][0]) && $record->voice_message[0][0] == 0 
                      && isset($record->voice_message[1][0]) && $record->voice_message[1][0] == 1;
            
            $debugData[] = [
                'id' => $record->id,
                'voice_message' => $record->voice_message,
                'is_voice_by_rule' => $isVoice,
                'is_message_by_rule' => $isMessage,
                'type_by_model' => $record->type,
                'created_at' => $record->created_at
            ];
        }
        
        $stats = $this->getSafeAreaStats();
        $monthlyData = $this->getSafeAreaMonthlyData();
        
        return response()->json([
            'recent_records' => $debugData,
            'statistics' => $stats,
            'monthly_data' => $monthlyData,
            'count_verification' => [
                'total_records' => SafeArea::count(),
                'voice_count' => SafeArea::whereRaw("JSON_EXTRACT(voice_message, '$[0][0]') = 1")
                                       ->whereRaw("JSON_EXTRACT(voice_message, '$[1][0]') = 0")
                                       ->count(),
                'message_count' => SafeArea::whereRaw("JSON_EXTRACT(voice_message, '$[0][0]') = 0")
                                         ->whereRaw("JSON_EXTRACT(voice_message, '$[1][0]') = 1")
                                         ->count(),
            ]
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    
    private function extractScores($data)
    {
        if (empty($data)) return [];
        
        if (is_array($data) && isset($data[0]) && is_array($data[0])) {
            return $data[0];
        }
        
        if (is_array($data)) {
            $lastElement = end($data);
            if (is_bool($lastElement)) {
                return array_slice($data, 0, -1);
            }
            return array_filter($data, 'is_numeric');
        }
        
        return [];
    }
    
    private function extractBoolean($data)
    {
        if (empty($data)) return false;
        
        if (is_array($data) && isset($data[1]) && is_array($data[1])) {
            return $data[1][0] ?? false;
        }
        
        if (is_array($data)) {
            $lastElement = end($data);
            if (is_bool($lastElement)) {
                return $lastElement;
            }
            $scores = $this->extractScores($data);
            return array_sum($scores) > 0;
        }
        
        return false;
    }
}