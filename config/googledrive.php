<?php
// config/googledrive.php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Drive Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Google Drive settings for file uploads
    | and management. Make sure to set up your Service Account properly.
    |
    */

    'credentials_path' => storage_path('app/google-drive-credentials.json'),
    
    'folders' => [
        'behavioral_report' => [
            'main' => 'behavioral_report',
            'images' => 'images',
            'voices' => 'voices',
        ],
    ],
    
    'file_naming' => [
        'format' => 'Y_m_d_H_i_s', // PHP date format for file naming
        'timezone' => 'Asia/Bangkok',
    ],
    
    'upload_limits' => [
        'max_image_size' => 10240, // KB
        'max_voice_size' => 51200, // KB (50MB)
        'allowed_image_types' => ['jpg', 'jpeg', 'png', 'gif'],
        'allowed_voice_types' => ['mp3', 'wav', 'ogg'],
    ],
    
    'sharing' => [
        'default_permissions' => 'reader',
        'service_account_email' => env('GOOGLE_DRIVE_SERVICE_ACCOUNT_EMAIL'),
    ],
];