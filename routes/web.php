<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MainController;
use App\Http\Middleware\CheckRoleUser;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\MentalHealthController;
use App\Http\Controllers\BehavioralReportController;
use App\Http\Controllers\Game\BullyingGameController;
use App\Http\Controllers\Game\ScenarioController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\InfographicController;
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

// Safe Area Routes
Route::prefix('report_consultation/safe_area')->group(function () {
    Route::get('/', fn() => view('report&consultation/safe_area/safe_area'))->name('safe_area');
    Route::get('voice', fn() => view('report&consultation/safe_area/voice/voice'))->name('safe_area/voice');
    Route::get('voice/result', fn() => view('report&consultation/safe_area/voice/result'))->name('safe_area/voice/result');
    Route::get('message', fn() => view('report&consultation/safe_area/message/message'))->name('safe_area/message');
    Route::get('message/result', fn() => view('report&consultation/safe_area/message/result'))->name('safe_area/message/result');
});

// Safe Area Controllers
Route::get('/safe-area/voice', [App\Http\Controllers\SafeAreaVoiceController::class, 'index'])->name('safe-area.voice');
Route::post('/safe-area/voice/store', [App\Http\Controllers\SafeAreaVoiceController::class, 'store'])->name('safe-area.voice.store');
Route::get('/safe-area/message', [App\Http\Controllers\SafeAreaMessageController::class, 'index'])->name('safe-area.message');
Route::post('/safe-area/message/store', [App\Http\Controllers\SafeAreaMessageController::class, 'store'])->name('safe-area.message.store');

// Behavioral Report Routes
Route::get('/report&consultation/behavioral_report', [BehavioralReportController::class, 'index'])->name('behavioral-report.index');
Route::get('/report_consultation/behavioral_report', [BehavioralReportController::class, 'index'])->name('behavioral_report');
Route::post('/report&consultation/behavioral_report', [BehavioralReportController::class, 'store'])->name('behavioral-report.store');

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

// Infographic Routes (NEW)
Route::prefix('infographic')->name('infographic.')->group(function () {
    Route::get('/', [InfographicController::class, 'index'])->name('index');
    Route::get('/{topicId}', [InfographicController::class, 'show'])->name('show');
    Route::get('/api/images/{topicId}', [InfographicController::class, 'getImages'])->name('api.images');
    Route::get('/api/check/{topicId}', [InfographicController::class, 'checkAvailability'])->name('api.check');
});

// Update the main route to point to infographic (NEW)
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

// Protected Routes
Route::middleware(['auth', CheckRoleUser::class])->group(function () {
    Route::get('/test_login', [MainController::class, 'testLogin'])->name('test_login');
});

// Debug Routes (Development Only)
if (app()->environment('local')) {
    Route::get('/check-view-structure', function() {
        $viewPath = resource_path('views');
        $expectedPath = $viewPath . '/assessment/cyberbullying/person_action/form';
        
        return response()->json([
            'base_views_path' => $viewPath,
            'expected_directory' => $expectedPath,
            'directory_exists' => is_dir($expectedPath),
            'directory_readable' => is_readable($expectedPath),
            'expected_file' => $expectedPath . '/result.blade.php',
            'file_exists' => file_exists($expectedPath . '/result.blade.php'),
        ]);
    });

    Route::get('/check-view-files', function() {
        $viewPath = resource_path('views');
        $allFiles = [];
        
        $directory = new RecursiveDirectoryIterator($viewPath);
        $iterator = new RecursiveIteratorIterator($directory);
        
        foreach ($iterator as $info) {
            if ($info->isFile() && $info->getExtension() === 'php') {
                $allFiles[] = str_replace($viewPath . '/', '', $info->getPathname());
            }
        }
        
        return response()->json([
            'view_path' => $viewPath,
            'all_blade_files' => $allFiles
        ]);
    });

    // Test route for infographic structure (NEW - for debugging)
    Route::get('/test-infographic-structure', function() {
        $basePath = public_path('images/infographic');
        $result = [];
        
        for ($topicId = 1; $topicId <= 6; $topicId++) {
            $topicPath = $basePath . '/' . $topicId;
            $images = [];
            
            if (File::exists($topicPath)) {
                $files = File::files($topicPath);
                
                foreach ($files as $file) {
                    if (in_array($file->getExtension(), ['png', 'jpg', 'jpeg', 'gif'])) {
                        $images[] = $file->getFilename();
                    }
                }
                
                // Sort numerically
                usort($images, function($a, $b) {
                    $aNum = (int) pathinfo($a, PATHINFO_FILENAME);
                    $bNum = (int) pathinfo($b, PATHINFO_FILENAME);
                    return $aNum <=> $bNum;
                });
            }
            
            $result[$topicId] = [
                'path_exists' => File::exists($topicPath),
                'path' => $topicPath,
                'images' => $images,
                'count' => count($images)
            ];
        }
        
        return response()->json($result, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    })->name('test.infographic.structure');
}
Route::get('/main_dashboard', [DashboardController::class, 'index'])->name('main.dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/assessment', [DashboardController::class, 'assessment'])->name('assessment-dashboard');
Route::get('/dashboard/behavioral-report', [DashboardController::class, 'behavioralReport'])->name('behavioral-report-dashboard');
Route::get('/dashboard/safe-area', [DashboardController::class, 'safeArea'])->name('safe-area-dashboard');