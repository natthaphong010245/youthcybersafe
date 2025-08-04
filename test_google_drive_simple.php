<?php
// test_google_drive_simple.php - สร้างไฟล์นี้ในโฟลเดอร์ root

require_once 'vendor/autoload.php';

echo "🔍 Testing Google Drive API Connection...\n";
echo "========================================\n\n";

try {
    // ทดสอบ Google Client
    $client = new \Google\Client();
    echo "✅ Google Client class loaded\n";
    
    // ตั้งค่าพื้นฐาน
    $client->setApplicationName('Youth Cyber Safe App');
    $client->setScopes([\Google\Service\Drive::DRIVE]);
    $client->setAccessType('offline');
    
    // ตรวจสอบไฟล์ credentials
    $credentialsPath = 'storage/app/google-drive-credentials.json';
    
    if (!file_exists($credentialsPath)) {
        echo "❌ Credentials file not found: $credentialsPath\n";
        exit(1);
    }
    
    echo "✅ Credentials file exists\n";
    
    // อ่านไฟล์ credentials
    $credentialsContent = file_get_contents($credentialsPath);
    $credentials = json_decode($credentialsContent, true);
    
    if (!$credentials) {
        echo "❌ Invalid JSON in credentials file\n";
        exit(1);
    }
    
    echo "✅ Credentials JSON is valid\n";
    echo "📧 Service Account Email: " . ($credentials['client_email'] ?? 'Not found') . "\n";
    echo "🆔 Project ID: " . ($credentials['project_id'] ?? 'Not found') . "\n";
    
    // ตั้งค่า credentials
    $client->setAuthConfig($credentialsPath);
    echo "✅ Credentials loaded into Google Client\n";
    
    // สร้าง Drive service
    $service = new \Google\Service\Drive($client);
    echo "✅ Google Drive Service created\n";
    
    // ทดสอบการเชื่อมต่อด้วยการดึงข้อมูล user
    echo "\n🔗 Testing connection to Google Drive...\n";
    
    try {
        $about = $service->about->get(['fields' => 'user']);
        $user = $about->getUser();
        
        if ($user) {
            echo "✅ Successfully connected to Google Drive!\n";
            echo "👤 Connected as: " . $user->getDisplayName() . "\n";
            echo "📧 Email: " . $user->getEmailAddress() . "\n";
        } else {
            echo "⚠️  Connected but no user info available\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Connection test failed: " . $e->getMessage() . "\n";
        
        if (strpos($e->getMessage(), 'API has not been used') !== false) {
            echo "\n💡 Solution: You need to enable Google Drive API in Google Cloud Console\n";
            echo "   1. Go to https://console.cloud.google.com/\n";
            echo "   2. Select project: youthcybersafe\n";
            echo "   3. Go to APIs & Services > Library\n";
            echo "   4. Search for 'Google Drive API' and click Enable\n";
        }
        
        if (strpos($e->getMessage(), 'insufficient permission') !== false) {
            echo "\n💡 Solution: Service Account needs permissions\n";
            echo "   1. Login to Google Drive with youthcybersafe1@gmail.com\n";
            echo "   2. Create folder 'behavioral_report'\n";
            echo "   3. Share it with: " . ($credentials['client_email'] ?? 'your-service-account') . "\n";
            echo "   4. Give 'Editor' permissions\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
}

echo "\n========================================\n";
echo "🏁 Test completed!\n";