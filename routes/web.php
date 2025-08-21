<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MainController;
use App\Http\Middleware\CheckRoleUser;
use App\Http\Middleware\CheckResearcher; 
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\MentalHealthController;
use App\Http\Controllers\BehavioralReportController;
use App\Http\Controllers\Game\BullyingGameController;
use App\Http\Controllers\Game\ScenarioController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\InfographicController;
use App\Http\Controllers\SafeAreaVoiceController;
use App\Http\Controllers\SafeAreaMessageController;
use App\Http\Controllers\SafeAreaStatsController;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Middleware\CheckTeacher; 

/*
|--------------------------------------------------------------------------
| Web Routes  
|--------------------------------------------------------------------------
*/

// Basic Pages
$basicRoutes = [
    '/' => ['view' => 'index', 'name' => 'index'],
    '/home' => ['view' => 'home', 'name' => 'home'],
    '/main' => ['view' => 'main', 'name' => 'main'],
    '/faq' => ['view' => 'faq/faq', 'name' => 'faq'],
    '/inforgraphic' => ['view' => 'inforgraphic/inforgraphic', 'name' => 'inforgraphic'],
];

foreach ($basicRoutes as $uri => $config) {
    Route::get($uri, fn() => view($config['view']))->name($config['name']);
}

// Assessment Routes
$assessmentRoutes = [
    '/assessment' => ['view' => 'assessment/assessment', 'name' => 'assessment'],
    '/assessment/cyberbullying' => ['view' => 'assessment/cyberbullying/index', 'name' => 'cyberbullying'],
    '/assessment/cyberbullying/person_action' => ['view' => 'assessment/cyberbullying/person_action/person_action', 'name' => 'person_action'],
    '/assessment/cyberbullying/victim' => ['view' => 'assessment/cyberbullying/victim/victim', 'name' => 'victim'],
    '/assessment/cyberbullying/overview' => ['view' => 'assessment/cyberbullying/overview/overview', 'name' => 'overview'],
    '/assessment/mental_health' => ['view' => 'assessment/mental_health/mental_health', 'name' => 'mental_health'],
];

foreach ($assessmentRoutes as $uri => $config) {
    Route::get($uri, fn() => view($config['view']))->name($config['name']);
}

Route::prefix('assessment/cyberbullying')->group(function () {
    Route::get('person_action/form', [AssessmentController::class, 'showPersonActionForm'])->name('person_action/form');
    Route::post('person_action/form', [AssessmentController::class, 'submitPersonActionForm']);
    Route::get('person_action/result', [AssessmentController::class, 'showPersonActionResults'])->name('person_action/result');

    Route::get('victim/form', [AssessmentController::class, 'showVictimForm'])->name('victim/form');
    Route::post('victim/form', [AssessmentController::class, 'submitVictimForm']);
    Route::get('victim/result', [AssessmentController::class, 'showVictimResults'])->name('victim/result');

    Route::get('overview/form', [OverviewController::class, 'showForm'])->name('overview/form');
    Route::post('overview/form', [OverviewController::class, 'submitForm']);
    Route::get('overview/result', [OverviewController::class, 'showResults'])->name('overview/result');
});

Route::prefix('assessment/mental_health')->group(function () {
    Route::get('form', [MentalHealthController::class, 'showForm'])->name('mental_health/form');
    Route::post('form', [MentalHealthController::class, 'submitForm']);
    Route::get('result', [MentalHealthController::class, 'showResults'])->name('mental_health/result');
});

// Report & Consultation Routes (handle & character in view names)
Route::get('/report_consultation', function () {
    return view('report&consultation.report&consultation');
})->name('report&consultation');

Route::get('/report_consultation/request_consultation', function () {
    return view('report&consultation.request_consultation.request_consultation');
})->name('request_consultation');

Route::get('/report_consultation/behavioral_report/result', function () {
    return view('report&consultation.behavioral_report.result');
})->name('result_report');

Route::get('/report_consultation/request_consultation/teacher', function () {
    return view('report&consultation.request_consultation.teacher.teacher');
})->name('teacher_report');

Route::get('/report_consultation/request_consultation/province', function () {
    return view('report&consultation.request_consultation.province.province');
})->name('province_report');

Route::get('/report_consultation/request_consultation/country', function () {
    return view('report&consultation.request_consultation.country.country');
})->name('country_report');

Route::get('/report_consultation/request_consultation/app_center', function () {
    return view('report&consultation.request_consultation.app_center.app_center');
})->name('app_center_report');

// =====================================
// SAFE AREA ROUTES - UPDATED SYSTEM
// =====================================

// Safe Area Main Routes (View Only)
Route::prefix('report_consultation/safe_area')->group(function () {
    Route::get('/', fn() => view('report&consultation.safe_area.safe_area'))->name('safe_area');
    
    // Legacy result routes (for backward compatibility)
    Route::get('voice/result', fn() => view('report&consultation.safe_area.voice.result'))->name('safe_area/voice/result');
    Route::get('message/result', fn() => view('report&consultation.safe_area.message.result'))->name('safe_area/message/result');
});

// Safe Area Functional Routes (Controller Based) - NEW SYSTEM
Route::prefix('safe-area')->name('safe-area.')->group(function () {
    // Voice routes
    Route::get('/voice', [SafeAreaVoiceController::class, 'index'])->name('voice');
    Route::post('/voice/store', [SafeAreaVoiceController::class, 'store'])->name('voice.store');
    
    // Message routes  
    Route::get('/message', [SafeAreaMessageController::class, 'index'])->name('message');
    Route::post('/message/store', [SafeAreaMessageController::class, 'store'])->name('message.store');
    
    // Statistics routes
    Route::get('/stats', [SafeAreaStatsController::class, 'index'])->name('stats');
    Route::get('/stats/json', [SafeAreaStatsController::class, 'getStats'])->name('stats.json');
    Route::get('/export', [SafeAreaStatsController::class, 'export'])->name('export');
});

// Legacy Safe Area Routes (for backward compatibility)
Route::get('/safe_area/voice', [SafeAreaVoiceController::class, 'index'])->name('safe_area/voice');
Route::get('/safe_area/message', [SafeAreaMessageController::class, 'index'])->name('safe_area/message');

// Alternative legacy routes (if used in JavaScript or views)
Route::get('report_consultation/safe_area/voice', [SafeAreaVoiceController::class, 'index'])->name('report_consultation.safe_area.voice');
Route::get('report_consultation/safe_area/message', [SafeAreaMessageController::class, 'index'])->name('report_consultation.safe_area.message');

// =====================================
// END SAFE AREA ROUTES
// =====================================

// Behavioral Report Routes
Route::get('/report&consultation/behavioral_report', [BehavioralReportController::class, 'index'])->name('behavioral-report.index');
Route::get('/report_consultation/behavioral_report', [BehavioralReportController::class, 'index'])->name('behavioral_report');
Route::post('/report&consultation/behavioral_report', [BehavioralReportController::class, 'store'])->name('behavioral-report.store');
Route::post('/report_consultation/behavioral_report', [BehavioralReportController::class, 'store'])->name('behavioral-report.store.alt');

// Game Routes
Route::get('/main/game', fn() => view('game'))->name('main_game');
Route::get('/category/game', fn() => view('game/index'))->name('game');

// Game Controller Routes
Route::prefix('game')->group(function () {
    Route::get('1_1', [BullyingGameController::class, 'game1_1'])->name('game_1_1');
    Route::get('1_2', [BullyingGameController::class, 'game1_2'])->name('game_1_2');
    Route::get('1_3', [BullyingGameController::class, 'game1_3'])->name('game_1_3');
    Route::get('1_4', [BullyingGameController::class, 'game1_4'])->name('game_1_4');
    Route::get('4_1', [BullyingGameController::class, 'game4_1'])->name('game_4_1');
    Route::get('4_2', [BullyingGameController::class, 'game4_2'])->name('game_4_2');
    Route::get('5_1', [BullyingGameController::class, 'game5_1'])->name('game_5_1');
    Route::get('5_2', [BullyingGameController::class, 'game5_2'])->name('game_5_2');
    Route::get('custom', [BullyingGameController::class, 'customSequenceGame'])->name('game_custom');
});

$simpleGames = [2, 3, 6, 7, 8, 9, 10, 11, 12, 13, 14];
foreach ($simpleGames as $gameId) {
    Route::get("/game/{$gameId}", fn() => view("game/g_{$gameId}/index"))->name("game_{$gameId}");
}

// Video Routes
Route::get('/main_video', [VideoController::class, 'mainVideo'])->name('main_video');

foreach ([1, 2, 3, 4, 5, 6, 7] as $lang) {
    Route::get("/main_video_language{$lang}",
        [VideoController::class, 'showVideos'])
        ->defaults('language', $lang)
        ->name("main_video_language{$lang}");
}

// Infographic Routes
Route::prefix('infographic')->name('infographic.')->group(function () {
    Route::get('/', [InfographicController::class, 'index'])->name('index');
    Route::get('/{topicId}', [InfographicController::class, 'show'])->name('show');
    Route::get('/api/images/{topicId}', [InfographicController::class, 'getImages'])->name('api.images');
    Route::get('/api/check/{topicId}', [InfographicController::class, 'checkAvailability'])->name('api.check');
});

// Update the main route to point to infographic
Route::get('/main_info', [InfographicController::class, 'index'])->name('main_info');

// Scenario Routes
Route::prefix('scenario')->group(function () {
    Route::get('/', [ScenarioController::class, 'index'])->name('scenario.index');
    Route::get('completion', [ScenarioController::class, 'completion'])->name('scenario.completion');
    
    // Individual Scenarios
    for ($i = 1; $i <= 13; $i++) {
        Route::get($i, [ScenarioController::class, "scenario{$i}"])->name("scenario_{$i}");
        Route::post("{$i}/submit", [ScenarioController::class, "submitScenario{$i}"])->name("scenario_{$i}.submit");
    }
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// =============================================
// PROTECTED ROUTES - ใช้ middleware ที่มีอยู่แล้ว
// =============================================

// General User Routes (คุณครูและนักวิจัยที่ได้รับอนุมัติ)
Route::middleware(['auth', CheckRoleUser::class])->group(function () {
    Route::get('/test_login', [MainController::class, 'testLogin'])->name('test_login');
});

// =============================================
// TEACHER DASHBOARD ROUTES (เฉพาะคุณครู) - ✅ แก้ไขแล้ว
// =============================================
Route::middleware(['auth', CheckTeacher::class])->group(function () {
    Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');
    Route::get('/teacher/behavioral-report', [TeacherDashboardController::class, 'behavioralReport'])->name('teacher.behavioral-report');
    
    // API routes สำหรับ teacher - ✅ เพิ่ม route ที่ขาดหายไป
    Route::prefix('api/teacher')->name('api.teacher.')->group(function () {
        Route::post('/behavioral-report/update-status/{id}', [TeacherDashboardController::class, 'updateReportStatus'])->name('behavioral-report.update-status');
        Route::get('/behavioral-report/detail/{id}', [TeacherDashboardController::class, 'getReportDetail'])->name('behavioral-report.detail');
        
        // ✅ เพิ่ม route สำหรับการเปลี่ยนปี - เหมือน Safe Area
        Route::get('/school-data-by-year/{year?}', [TeacherDashboardController::class, 'getSchoolDataByYear'])->name('school-data-by-year');
    });
});

// =============================================
// RESEARCHER DASHBOARD ROUTES (เฉพาะนักวิจัย)
// =============================================
Route::middleware(['auth', CheckResearcher::class])->group(function () {
    Route::get('/main_dashboard', [DashboardController::class, 'index'])->name('main.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/assessment', [DashboardController::class, 'assessment'])->name('assessment-dashboard');
    Route::get('/dashboard/behavioral-report', [DashboardController::class, 'behavioralReport'])->name('behavioral-report-dashboard');
    Route::get('/dashboard/safe-area', [DashboardController::class, 'safeArea'])->name('safe-area-dashboard');
});

// Admin Routes (Protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Safe Area Admin Routes
    Route::prefix('safe-area')->name('safe-area.')->group(function () {
        Route::get('/dashboard', [SafeAreaStatsController::class, 'index'])->name('dashboard');
        Route::get('/statistics', [SafeAreaStatsController::class, 'getStats'])->name('statistics');
        Route::get('/export', [SafeAreaStatsController::class, 'export'])->name('export');
        Route::get('/chart-data', function() {
            return response()->json(\App\Models\SafeArea::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                SUM(CASE WHEN JSON_EXTRACT(voice_message, "$[0][0]") = 1 AND JSON_EXTRACT(voice_message, "$[1][0]") = 0 THEN 1 ELSE 0 END) as voice_count,
                SUM(CASE WHEN JSON_EXTRACT(voice_message, "$[0][0]") = 0 AND JSON_EXTRACT(voice_message, "$[1][0]") = 1 THEN 1 ELSE 0 END) as message_count
            ')
            ->groupBy('month')
            ->orderBy('month')
            ->get());
        })->name('chart-data');
    });

    // Behavioral Report Admin Routes
    Route::prefix('behavioral-reports')->name('behavioral-reports.')->group(function () {
        Route::get('/', [BehavioralReportController::class, 'adminIndex'])->name('index');
        Route::get('/{id}', [BehavioralReportController::class, 'show'])->name('show');
        Route::patch('/{id}/status', [BehavioralReportController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{id}', [BehavioralReportController::class, 'destroy'])->name('destroy');
        Route::get('/statistics/data', [BehavioralReportController::class, 'getStatistics'])->name('statistics');
    });
});

// API Routes (for AJAX and mobile apps)
Route::prefix('api')->name('api.')->group(function () {
    // Safe Area API
    Route::prefix('safe-area')->name('safe-area.')->group(function () {
        Route::post('/voice', [SafeAreaVoiceController::class, 'store'])->name('voice.store');
        Route::post('/message', [SafeAreaMessageController::class, 'store'])->name('message.store');
        Route::get('/stats', [SafeAreaStatsController::class, 'getStats'])->name('stats');
        Route::get('/export', [SafeAreaStatsController::class, 'export'])->name('export');
        
        // Routes สำหรับดึงข้อมูลตามปี
        Route::get('/data-by-year/{year}', [DashboardController::class, 'getSafeAreaDataByYear'])->name('data-by-year');
        Route::get('/available-years', [DashboardController::class, 'getAvailableYears'])->name('available-years');
        Route::get('/monthly-data/{year?}', function($year = null) {
            $controller = new DashboardController();
            return $controller->getSafeAreaDataByYear(request(), $year);
        })->name('monthly-data');
    });
    
    // Mental Health API
    Route::prefix('mental-health')->name('mental-health.')->group(function () {
        Route::post('/submit', [MentalHealthController::class, 'submitForm'])->name('submit');
        Route::get('/stats', function() {
            return response()->json([
                'total_assessments' => \App\Models\MentalHealthAssessment::count(),
                'high_risk' => \App\Models\MentalHealthAssessment::highRisk()->count(),
            ]);
        })->name('stats');
    });

    // Dashboard API Routes
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/cyberbullying-stats', [DashboardController::class, 'getAssessmentStats'])->name('cyberbullying.stats');
        Route::get('/mental-health-stats', [DashboardController::class, 'getMentalHealthStatsApi'])->name('mental-health.stats');
        Route::get('/safe-area-stats', [DashboardController::class, 'getSafeAreaStatsApi'])->name('safe-area.stats');
        Route::get('/behavioral-schools-stats', [DashboardController::class, 'getBehavioralSchoolsStatsApi'])->name('behavioral-schools.stats');
    });

    // Behavioral Report API Routes - UPDATED AND EXPANDED
    Route::prefix('behavioral-report')->name('behavioral-report.')->group(function () {
        // Public API routes (no authentication required)
        Route::get('/statistics', [BehavioralReportController::class, 'getStatistics'])->name('statistics');
        Route::post('/', [BehavioralReportController::class, 'store'])->name('store');
        
        // Protected API routes (require authentication) - FOR DASHBOARD
        Route::middleware(['auth'])->group(function () {
            Route::get('/detail/{id}', [DashboardController::class, 'getReportDetail'])->name('detail');
            Route::post('/update-status/{id}', [DashboardController::class, 'updateReportStatus'])->name('update-status');
            Route::get('/', [BehavioralReportController::class, 'adminIndex'])->name('index');
            Route::get('/{id}', [BehavioralReportController::class, 'show'])->name('show');
            Route::patch('/{id}/status', [BehavioralReportController::class, 'updateStatus'])->name('update-status.auth');
            Route::delete('/{id}', [BehavioralReportController::class, 'destroy'])->name('destroy');
            
            // Dashboard specific routes
            Route::get('/schools-data', function() {
                $controller = new DashboardController();
                return response()->json($controller->getBehavioralSchoolsData());
            })->name('schools-data');
            
            Route::get('/overview-data', function() {
                $controller = new DashboardController();
                return response()->json($controller->getBehavioralOverviewData());
            })->name('overview-data');
        });
    });
});
