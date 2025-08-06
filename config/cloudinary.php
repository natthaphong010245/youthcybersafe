<?php
// config/cloudinary.php
return [
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),
    'secure' => true,
    
    'folders' => [
        'behavioral_report_images' => 'behavioral_report/images',
        'behavioral_report_voice' => 'behavioral_report/voice',
    ],
];