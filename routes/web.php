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
// SAFE AREA ROUTES
// =====================================

// Safe Area Main Routes (View Only)
Route::prefix('report_consultation/safe_area')->group(function () {
    Route::get('/', fn() => view('report&consultation.safe_area.safe_area'))->name('safe_area');
    
    // Legacy result routes (for backward compatibility)
    Route::get('voice/result', fn() => view('report&consultation.safe_area.voice.result'))->name('safe_area/voice/result');
    Route::get('message/result', fn() => view('report&consultation.safe_area.message.result'))->name('safe_area/message/result');
});

// Safe Area Functional Routes (Controller Based)
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
// BEHAVIORAL REPORT ROUTES
// =====================================

Route::get('/report&consultation/behavioral_report', [BehavioralReportController::class, 'index'])->name('behavioral-report.index');
Route::get('/report_consultation/behavioral_report', [BehavioralReportController::class, 'index'])->name('behavioral_report');
Route::post('/report&consultation/behavioral_report', [BehavioralReportController::class, 'store'])->name('behavioral-report.store');

// =====================================
// GAME ROUTES
// =====================================

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

// =====================================
// VIDEO ROUTES
// =====================================

Route::get('/main_video', [VideoController::class, 'mainVideo'])->name('main_video');

foreach ([1, 2, 3, 4, 5, 6, 7] as $lang) {
    Route::get("/main_video_language{$lang}",
        [VideoController::class, 'showVideos'])
        ->defaults('language', $lang)
        ->name("main_video_language{$lang}");
}

// =====================================
// INFOGRAPHIC ROUTES
// =====================================

Route::prefix('infographic')->name('infographic.')->group(function () {
    Route::get('/', [InfographicController::class, 'index'])->name('index');
    Route::get('/{topicId}', [InfographicController::class, 'show'])->name('show');
    Route::get('/api/images/{topicId}', [InfographicController::class, 'getImages'])->name('api.images');
    Route::get('/api/check/{topicId}', [InfographicController::class, 'checkAvailability'])->name('api.check');
});

// Update the main route to point to infographic
Route::get('/main_info', [InfographicController::class, 'index'])->name('main_info');

// =====================================
// SCENARIO ROUTES
// =====================================

Route::prefix('scenario')->group(function () {
    Route::get('/', [ScenarioController::class, 'index'])->name('scenario.index');
    Route::get('completion', [ScenarioController::class, 'completion'])->name('scenario.completion');
    
    // Individual Scenarios
    for ($i = 1; $i <= 13; $i++) {
        Route::get($i, [ScenarioController::class, "scenario{$i}"])->name("scenario_{$i}");
        Route::post("{$i}/submit", [ScenarioController::class, "submitScenario{$i}"])->name("scenario_{$i}.submit");
    }
});

// =====================================
// AUTHENTICATION ROUTES
// =====================================

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// =====================================
// PROTECTED ROUTES
// =====================================

// Protected Routes (General User)
Route::middleware(['auth', CheckRoleUser::class])->group(function () {
    Route::get('/test_login', [MainController::class, 'testLogin'])->name('test_login');
});

// Dashboard Routes (เฉพาะ Researcher ที่ได้รับอนุมัติ)
Route::middleware(['auth', CheckResearcher::class])->group(function () {
    Route::get('/main_dashboard', [DashboardController::class, 'index'])->name('main.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/assessment', [DashboardController::class, 'assessment'])->name('assessment-dashboard');
    Route::get('/dashboard/behavioral-report', [DashboardController::class, 'behavioralReport'])->name('behavioral-report-dashboard');
    Route::get('/dashboard/safe-area', [DashboardController::class, 'safeArea'])->name('safe-area-dashboard');
});

// =====================================
// ADMIN ROUTES
// =====================================

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
});

// =====================================
// API ROUTES
// =====================================

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
});

// เพิ่มใน routes/web.php

// Test Google Drive Upload
Route::get('/debug-google-drive', function() {
    try {
        Log::info('=== Debug Google Drive Upload ===');
        
        // ตรวจสอบว่ามี Google_Client หรือไม่
        if (!class_exists('Google_Client')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Google_Client class not found',
                'solution' => 'Run: composer require google/apiclient'
            ], 500, [], JSON_PRETTY_PRINT);
        }
        
        // ตรวจสอบ Service Account Key
        $possiblePaths = [
            storage_path('app/google/service-account-key.json'),
            storage_path('app/google-credentials.json'),
            base_path('google-credentials.json'),
        ];
        
        $keyPath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $keyPath = $path;
                break;
            }
        }
        
        if (!$keyPath) {
            return response()->json([
                'status' => 'error',
                'message' => 'Service account key file not found',
                'checked_paths' => $possiblePaths,
                'solution' => 'Copy google-credentials.json to storage/app/google/service-account-key.json'
            ], 500, [], JSON_PRETTY_PRINT);
        }
        
        // ทดสอบสร้าง GoogleDriveService
        $service = new \App\Services\GoogleDriveService();
        
        // สร้างไฟล์ทดสอบ
        $testContent = "Test file - " . now()->toDateTimeString();
        $testFileName = 'debug_test_' . now()->format('YmdHis') . '.txt';
        
        // อัปโหลดไฟล์ทดสอบ
        $result = $service->uploadFile($testContent, $testFileName, 'text/plain');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Google Drive upload test successful!',
            'file_uploaded' => $result,
            'key_file_used' => $keyPath,
            'timestamp' => now()->toDateTimeString()
        ], 200, [], JSON_PRETTY_PRINT);
        
    } catch (Exception $e) {
        Log::error('Debug Google Drive failed: ' . $e->getMessage());
        
        return response()->json([
            'status' => 'error',
            'message' => 'Google Drive test failed',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500, [], JSON_PRETTY_PRINT);
    }
});

// ตรวจสอบไฟล์ในระบบ
Route::get('/debug-files', function() {
    $results = [];
    
    // ตรวจสอบ Google credentials
    $googlePaths = [
        base_path('google-credentials.json'),
        storage_path('app/google/service-account-key.json'),
        storage_path('app/google-credentials.json')
    ];
    
    foreach ($googlePaths as $path) {
        $results['google_files'][] = [
            'path' => $path,
            'exists' => file_exists($path),
            'readable' => file_exists($path) ? is_readable($path) : false,
            'size' => file_exists($path) ? filesize($path) : 0
        ];
    }
    
    // ตรวจสอบไฟล์ behavioral reports
    $reportPaths = [
        storage_path('app/behavioral_reports/voices'),
        storage_path('app/behavioral_reports/images')
    ];
    
    foreach ($reportPaths as $path) {
        $files = [];
        if (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
        }
        
        $results['behavioral_files'][] = [
            'path' => $path,
            'exists' => is_dir($path),
            'files' => $files,
            'count' => count($files)
        ];
    }
    
    // ตรวจสอบ Composer packages
    $composerFile = base_path('vendor/composer/installed.json');
    if (file_exists($composerFile)) {
        $installed = json_decode(file_get_contents($composerFile), true);
        $googlePackages = array_filter($installed['packages'] ?? [], function($package) {
            return strpos($package['name'], 'google') !== false;
        });
        $results['composer_google_packages'] = array_column($googlePackages, 'name');
    }
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});

// ทดสอบสร้างไฟล์ local
Route::get('/debug-local-storage', function() {
    try {
        Storage::makeDirectory('behavioral_reports/test');
        
        $testContent = "Local storage test - " . now()->toDateTimeString();
        $testFileName = 'local_test_' . now()->format('YmdHis') . '.txt';
        
        Storage::put('behavioral_reports/test/' . $testFileName, $testContent);
        
        $filePath = storage_path('app/behavioral_reports/test/' . $testFileName);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Local storage test successful',
            'file_path' => $filePath,
            'file_exists' => file_exists($filePath),
            'file_content' => Storage::get('behavioral_reports/test/' . $testFileName)
        ], 200, [], JSON_PRETTY_PRINT);
        
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Local storage test failed',
            'error' => $e->getMessage()
        ], 500, [], JSON_PRETTY_PRINT);
    }
});

Route::get('/debug/google-client', function() {
    $results = [];
    
    // Check if composer autoload works
    try {
        require_once base_path('vendor/autoload.php');
        $results['autoload'] = 'OK';
    } catch (Exception $e) {
        $results['autoload'] = 'ERROR: ' . $e->getMessage();
    }
    
    // Check if Google_Client class exists
    $results['google_client_exists'] = class_exists('Google_Client') ? 'YES' : 'NO';
    
    // Check Google API Client package
    $composerLock = json_decode(file_get_contents(base_path('composer.lock')), true);
    $googlePackage = null;
    
    foreach ($composerLock['packages'] as $package) {
        if ($package['name'] === 'google/apiclient') {
            $googlePackage = $package;
            break;
        }
    }
    
    $results['google_package'] = $googlePackage ? 
        ['version' => $googlePackage['version'], 'installed' => true] : 
        ['installed' => false];
    
    // Check PHP extensions
    $requiredExtensions = ['curl', 'json', 'openssl', 'mbstring'];
    $results['php_extensions'] = [];
    
    foreach ($requiredExtensions as $ext) {
        $results['php_extensions'][$ext] = extension_loaded($ext) ? 'LOADED' : 'MISSING';
    }
    
    // Check service account key
    $keyPath = storage_path('app/google/service-account-key.json');
    $results['service_account_key'] = [
        'path' => $keyPath,
        'exists' => file_exists($keyPath),
        'readable' => file_exists($keyPath) && is_readable($keyPath),
        'size' => file_exists($keyPath) ? filesize($keyPath) : 0
    ];
    
    // Test Google Drive Service
    try {
        $service = new App\Services\GoogleDriveService();
        $results['google_drive_service'] = [
            'initialized' => true,
            'storage_type' => $service->getStorageType(),
            'using_fallback' => $service->isUsingLocalFallback()
        ];
    } catch (Exception $e) {
        $results['google_drive_service'] = [
            'initialized' => false,
            'error' => $e->getMessage()
        ];
    }
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
})->name('debug.google.client.production');

// Test Behavioral Report System in Production
Route::get('/debug/behavioral-report', function() {
    try {
        // Test database connection
        $dbTest = DB::connection()->getPdo();
        
        // Test table existence
        $tableExists = Schema::hasTable('behavioral_report');
        
        // Test model
        $modelClass = '\App\Models\ReportConsultation\BehavioralReportReportConsultation';
        $modelWorks = class_exists($modelClass);
        
        // Test record count
        $recordCount = $modelWorks ? $modelClass::count() : 'N/A';
        
        // Test Google Drive Service
        $googleService = new App\Services\GoogleDriveService();
        
        // Test file upload simulation
        $testResult = null;
        try {
            $folderId = $googleService->createFolderIfNotExists('test_folder');
            $testResult = [
                'folder_created' => true,
                'folder_id' => $folderId,
                'storage_type' => $googleService->getStorageType()
            ];
        } catch (Exception $e) {
            $testResult = [
                'folder_created' => false,
                'error' => $e->getMessage()
            ];
        }
        
        return response()->json([
            'success' => true,
            'database' => [
                'connection' => 'OK',
                'driver' => $dbTest->getAttribute(PDO::ATTR_DRIVER_NAME)
            ],
            'table' => [
                'exists' => $tableExists
            ],
            'model' => [
                'class_exists' => $modelWorks,
                'record_count' => $recordCount
            ],
            'google_service' => [
                'storage_type' => $googleService->getStorageType(),
                'using_fallback' => $googleService->isUsingLocalFallback(),
                'test_result' => $testResult
            ],
            'environment' => [
                'app_env' => config('app.env'),
                'app_debug' => config('app.debug'),
                'php_version' => PHP_VERSION
            ]
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
})->name('debug.behavioral.report.production');

// Fix Production Server Route
Route::get('/fix/production-server', function() {
    $results = [];
    
    try {
        // Clear all caches
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        \Artisan::call('cache:clear');
        
        $results['cache_cleared'] = 'SUCCESS';
        
        // Regenerate autoload
        exec('composer dump-autoload --optimize --no-dev 2>&1', $output, $return);
        $results['autoload_regenerated'] = $return === 0 ? 'SUCCESS' : 'FAILED';
        $results['composer_output'] = $output;
        
        // Test Google Drive Service after fixes
        try {
            $service = new App\Services\GoogleDriveService();
            $results['google_service_after_fix'] = [
                'working' => true,
                'storage_type' => $service->getStorageType(),
                'using_fallback' => $service->isUsingLocalFallback()
            ];
        } catch (Exception $e) {
            $results['google_service_after_fix'] = [
                'working' => false,
                'error' => $e->getMessage()
            ];
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Production server fixes applied',
            'results' => $results,
            'next_steps' => [
                'Test main site functionality',
                'Check behavioral report submission',
                'Monitor logs for any remaining issues'
            ]
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'partial_results' => $results
        ], 500, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
})->name('fix.production.server');