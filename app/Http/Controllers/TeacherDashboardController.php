<?php
//app/Http/Controllers/TeacherDashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ReportConsultation\BehavioralReportReportConsultation;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $teacherSchool = $teacher->school;
        
        // Data for Behavioral Report Overview Summary - จำนวนรายงานของโรงเรียนของครู
        $schoolReportCount = BehavioralReportReportConsultation::where('school', $teacherSchool)->count();
        $overview = [
            $teacherSchool => $schoolReportCount
        ];

        // List Report Student - เฉพาะรายงานที่ status = false และเป็นของโรงเรียนของครู
        $studentReports = BehavioralReportReportConsultation::where('school', $teacherSchool)
            ->where('status', false) // เฉพาะรายงานที่รอตรวจสอบ
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($report) {
                return [
                    'date' => $report->created_at->format('d/m/Y'), // วัน/เดือน/ปี
                    'message' => $report->message
                ];
            });

        // School report data for chart - ข้อมูลรายเดือนของโรงเรียน
        $currentYear = date('Y');
        $schoolReportData = $this->getSchoolMonthlyData($teacherSchool, $currentYear);

        $data = [
            'overview' => $overview,
            'student_reports' => $studentReports,
            'school_report_data' => $schoolReportData,
            'school_name' => $teacherSchool,
            'current_year' => $currentYear,
            'available_years' => range(2025, 2035),
            'current_page' => 1
        ];

        return view('teacher.dashboard.index', compact('data'));
    }

    public function behavioralReport(Request $request)
    {
        $teacher = Auth::user();
        $teacherSchool = $teacher->school;
        
        $statusFilter = $request->get('status');
        $page = (int) $request->get('page', 1);
        $perPage = 15;
        
        // Get reports for teacher's school only
        $query = BehavioralReportReportConsultation::where('school', $teacherSchool)
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
                'date' => $report->created_at->format('d/m/Y'), // วัน/เดือน/ปี
                'message' => $report->message,
                'status' => $report->status ? 'reviewed' : 'pending'
            ];
        });
        
        $totalPages = $totalCount > 0 ? ceil($totalCount / $perPage) : 1;
        if ($page > $totalPages) {
            $page = 1;
        }
        
        $data = [
            'school_name' => $teacherSchool,
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
            'total_before_filter' => BehavioralReportReportConsultation::where('school', $teacherSchool)->count()
        ];

        if ($request->ajax()) {
            return response()->json($data);
        }

        return view('teacher.dashboard.behavioral-report', compact('data'));
    }

    public function updateReportStatus(Request $request, $id)
    {
        try {
            $teacher = Auth::user();
            
            // ตรวจสอบว่ารายงานนี้เป็นของโรงเรียนของครูหรือไม่
            $report = BehavioralReportReportConsultation::where('id', $id)
                ->where('school', $teacher->school)
                ->firstOrFail();
                
            $report->status = true; // Mark as reviewed
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
            $teacher = Auth::user();
            
            // ตรวจสอบว่ารายงานนี้เป็นของโรงเรียนของครูหรือไม่
            $report = BehavioralReportReportConsultation::where('id', $id)
                ->where('school', $teacher->school)
                ->firstOrFail();
            
            // Decode images if they exist
            $images = [];
            if ($report->image && !empty($report->image)) {
                $decodedImages = json_decode($report->image, true);
                if (is_array($decodedImages) && count($decodedImages) > 0) {
                    // Filter out empty values
                    $images = array_filter($decodedImages, function($img) {
                        return !empty($img) && $img !== null;
                    });
                    $images = array_values($images); // Reset array indices
                }
            }
            
            return response()->json([
                'id' => $report->id,
                'date' => $report->created_at->format('d/m/Y'), // วัน/เดือน/ปี
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

    public function getSchoolDataByYear(Request $request, $year = null)
    {
        $teacher = Auth::user();
        $teacherSchool = $teacher->school;
        
        if ($year === null) {
            $year = $request->get('year', date('Y'));
        }
        
        if ($year < 2025 || $year > 2035) {
            return response()->json([
                'error' => 'ปีต้องอยู่ระหว่าง 2025-2035'
            ], 400);
        }
        
        $monthlyData = $this->getSchoolMonthlyData($teacherSchool, $year);
        
        return response()->json([
            'monthly_data' => $monthlyData,
            'school_name' => $teacherSchool,
            'year' => $year,
            'available_years' => range(2025, 2035)
        ]);
    }

    private function getSchoolMonthlyData($school, $year = null)
    {
        if ($year === null) {
            $year = date('Y');
        }
        
        $monthlyStats = BehavioralReportReportConsultation::selectRaw('
                MONTH(created_at) as month,
                COUNT(*) as report_count
            ')
            ->where('school', $school)
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthlyData[] = $monthlyStats->get($month)->report_count ?? 0;
        }
        
        return $monthlyData;
    }

    private function getLocationFromCoordinates($latitude, $longitude)
    {
        if (!$latitude || !$longitude) {
            return 'Thailand';
        }
        
        // You can implement reverse geocoding here if needed
        // For now, return Thailand as default
        return 'Thailand';
    }
}