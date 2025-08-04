<?php
// test_google_api.php - สร้างไฟล์นี้ในโฟลเดอร์ root ของโปรเจค

require_once 'vendor/autoload.php';

echo "🔍 Testing Google API Client...\n";
echo "=================================\n\n";

try {
    // ทดสอบว่า Google Client class มีอยู่หรือไม่
    if (class_exists('\Google\Client')) {
        echo "✅ Google\\Client class found!\n";
        
        // ลองสร้าง instance
        $client = new \Google\Client();
        echo "✅ Google Client instance created successfully!\n";
        
        // ทดสอบ method พื้นฐาน
        $client->setApplicationName('Test Application');
        echo "✅ setApplicationName() method works!\n";
        
        // ตรวจสอบว่า Drive class มีอยู่หรือไม่
        if (class_exists('\Google\Service\Drive')) {
            echo "✅ Google\\Service\\Drive class found!\n";
            
            // ลองสร้าง Drive service (แต่ยังไม่ต้องมี credentials)
            try {
                $drive = new \Google\Service\Drive($client);
                echo "✅ Google Drive Service can be instantiated!\n";
            } catch (Exception $e) {
                echo "ℹ️  Drive Service needs credentials (expected): " . $e->getMessage() . "\n";
            }
            
        } else {
            echo "❌ Google\\Service\\Drive class not found!\n";
        }
        
        // ตรวจสอบ Dependencies อื่นๆ
        if (class_exists('\Google\Auth\OAuth2')) {
            echo "✅ Google Auth classes available!\n";
        }
        
        if (class_exists('\Firebase\JWT\JWT')) {
            echo "✅ Firebase JWT library available!\n";
        }
        
        echo "\n🎉 Google API Client is fully functional!\n";
        echo "📋 Ready for integration with your Laravel app.\n";
        
    } else {
        echo "❌ Google\\Client class not found!\n";
        echo "🔧 Please check if composer install completed successfully.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
}

echo "\n=================================\n";
echo "🏁 Test completed!\n";

// แสดงข้อมูล PHP version สำหรับ debug
echo "\n📊 System Information:\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Extensions loaded: " . (extension_loaded('curl') ? '✅' : '❌') . " cURL, ";
echo (extension_loaded('openssl') ? '✅' : '❌') . " OpenSSL, ";
echo (extension_loaded('json') ? '✅' : '❌') . " JSON\n";
echo "Composer autoload: " . (file_exists('vendor/autoload.php') ? '✅' : '❌') . "\n";