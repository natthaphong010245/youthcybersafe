<?php

namespace App\Services;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    private $client;
    private $service;
    private $parentFolderId;

    public function __construct()
    {
        $this->initializeClient();
    }

    private function initializeClient()
    {
        try {
            $this->client = new Google_Client();
            
            // ใช้ Service Account JSON file
            $credentialsPath = storage_path('app/google-credentials.json');
            
            if (!file_exists($credentialsPath)) {
                throw new \Exception('Google credentials file not found at: ' . $credentialsPath);
            }

            $this->client->setAuthConfig($credentialsPath);
            $this->client->setScopes([Google_Service_Drive::DRIVE_FILE]);
            $this->service = new Google_Service_Drive($this->client);
            
            // ID ของโฟลเดอร์หลัก behavioral_report ใน Google Drive
            $this->parentFolderId = env('GOOGLE_DRIVE_BEHAVIORAL_REPORT_FOLDER_ID');
            
            if (!$this->parentFolderId) {
                throw new \Exception('GOOGLE_DRIVE_BEHAVIORAL_REPORT_FOLDER_ID not set in .env file');
            }

            Log::info('GoogleDriveService initialized successfully');
            
        } catch (\Exception $e) {
            Log::error('GoogleDriveService initialization failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * สร้างชื่อไฟล์ตามรูปแบบ ปี เดือน วัน เวลา
     */
    public function generateFileName($extension = 'mp3')
    {
        return Carbon::now()->format('Y_m_d_H_i_s') . '.' . $extension;
    }

    /**
     * ทดสอบการเชื่อมต่อ Google Drive
     */
    public function testConnection()
    {
        try {
            // ทดสอบโดยการดึงข้อมูลโฟลเดอร์หลัก
            $file = $this->service->files->get($this->parentFolderId);
            
            Log::info('Google Drive connection test successful');
            
            return [
                'success' => true,
                'folder_name' => $file->getName(),
                'folder_id' => $file->getId()
            ];
        } catch (\Exception $e) {
            Log::error('Google Drive connection test failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * สร้างโฟลเดอร์ถ้าไม่มี
     */
    public function createFolderIfNotExists($folderName, $parentId = null)
    {
        try {
            $parentId = $parentId ?: $this->parentFolderId;
            
            // ตรวจสอบว่าโฟลเดอร์มีอยู่แล้วหรือไม่
            $existingFolder = $this->findFolder($folderName, $parentId);
            if ($existingFolder) {
                Log::info("Folder '{$folderName}' already exists with ID: " . $existingFolder->getId());
                return $existingFolder->getId();
            }

            // สร้างโฟลเดอร์ใหม่
            $fileMetadata = new Google_Service_Drive_DriveFile([
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$parentId]
            ]);

            $folder = $this->service->files->create($fileMetadata);
            Log::info("Created new folder: {$folderName} with ID: " . $folder->getId());
            
            return $folder->getId();
            
        } catch (\Exception $e) {
            Log::error("Failed to create folder '{$folderName}': " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * ค้นหาโฟลเดอร์
     */
    private function findFolder($folderName, $parentId)
    {
        try {
            $query = "mimeType='application/vnd.google-apps.folder' and name='{$folderName}' and '{$parentId}' in parents and trashed=false";
            $results = $this->service->files->listFiles(['q' => $query]);
            
            return $results->getFiles() ? $results->getFiles()[0] : null;
            
        } catch (\Exception $e) {
            Log::error("Failed to find folder '{$folderName}': " . $e->getMessage());
            return null;
        }
    }

    /**
     * ตรวจสอบว่า Service พร้อมใช้งานหรือไม่
     */
    public function isReady()
    {
        try {
            return $this->service && $this->parentFolderId;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * ดึงข้อมูลสถานะ
     */
    public function getStatus()
    {
        return [
            'service_initialized' => isset($this->service),
            'parent_folder_id' => $this->parentFolderId,
            'credentials_file_exists' => file_exists(storage_path('app/google-credentials.json')),
            'ready' => $this->isReady()
        ];
    }
}