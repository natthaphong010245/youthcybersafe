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
    }

    /**
     * สร้างชื่อไฟล์ตามรูปแบบ ปี เดือน วัน เวลา
     */
    public function generateFileName($extension = 'mp3')
    {
        return Carbon::now()->format('Y_m_d_H_i_s') . '.' . $extension;
    }

    /**
     * สร้างโฟลเดอร์ถ้าไม่มี
     */
    public function createFolderIfNotExists($folderName, $parentId = null)
    {
        $parentId = $parentId ?: $this->parentFolderId;
        
        // ตรวจสอบว่าโฟลเดอร์มีอยู่แล้วหรือไม่
        $existingFolder = $this->findFolder($folderName, $parentId);
        if ($existingFolder) {
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
    }

    /**
     * ค้นหาโฟลเดอร์
     */
    private function findFolder($folderName, $parentId)
    {
        $query = "mimeType='application/vnd.google-apps.folder' and name='{$folderName}' and '{$parentId}' in parents and trashed=false";
        $results = $this->service->files->listFiles(['q' => $query]);
        
        return $results->getFiles() ? $results->getFiles()[0] : null;
    }

    /**
     * อัปโหลดไฟล์เสียง
     */
    public function uploadVoiceFile($audioData, $filename = null)
    {
        try {
            // สร้างโฟลเดอร์ voices ถ้าไม่มี
            $voicesFolderId = $this->createFolderIfNotExists('voices');
            
            // ถ้าไม่ได้กำหนดชื่อไฟล์ให้สร้างใหม่
            if (!$filename) {
                $filename = $this->generateFileName('mp3');
            }

            // แปลง base64 เป็น binary data
            if (strpos($audioData, 'data:audio') === 0) {
                $audioData = substr($audioData, strpos($audioData, ',') + 1);
                $audioData = base64_decode($audioData);
            }

            // สร้าง temporary file
            $tempFile = tmpfile();
            fwrite($tempFile, $audioData);
            $tempFilePath = stream_get_meta_data($tempFile)['uri'];

            $fileMetadata = new Google_Service_Drive_DriveFile([
                'name' => $filename,
                'parents' => [$voicesFolderId]
            ]);

            $file = $this->service->files->create($fileMetadata, [
                'data' => file_get_contents($tempFilePath),
                'mimeType' => 'audio/mpeg',
                'uploadType' => 'multipart'
            ]);

            // ปิด temporary file
            fclose($tempFile);

            Log::info("Voice file uploaded to Google Drive: {$filename}");
            return $filename;

        } catch (\Exception $e) {
            Log::error("Failed to upload voice file: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * อัปโหลดไฟล์รูปภาพ
     */
    public function uploadImageFiles($imageFiles, $baseFilename = null)
    {
        try {
            // สร้างโฟลเดอร์ images ถ้าไม่มี
            $imagesFolderId = $this->createFolderIfNotExists('images');
            
            $uploadedFiles = [];
            
            // ถ้าไม่ได้กำหนด baseFilename ให้สร้างใหม่
            if (!$baseFilename) {
                $baseFilename = $this->generateFileName('');
                $baseFilename = rtrim($baseFilename, '.'); // ตัด extension ออก
            }

            foreach ($imageFiles as $index => $imageFile) {
                $extension = $imageFile->getClientOriginalExtension();
                $filename = $baseFilename . '_' . ($index + 1) . '.' . $extension;

                $fileMetadata = new Google_Service_Drive_DriveFile([
                    'name' => $filename,
                    'parents' => [$imagesFolderId]
                ]);

                $file = $this->service->files->create($fileMetadata, [
                    'data' => file_get_contents($imageFile->getPathname()),
                    'mimeType' => $imageFile->getMimeType(),
                    'uploadType' => 'multipart'
                ]);

                $uploadedFiles[] = $filename;
                Log::info("Image file uploaded to Google Drive: {$filename}");
            }

            return $uploadedFiles;

        } catch (\Exception $e) {
            Log::error("Failed to upload image files: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * ดาวน์โหลดไฟล์จาก Google Drive
     */
    public function downloadFile($filename, $type = 'voice')
    {
        try {
            $folderId = $type === 'voice' ? 
                $this->createFolderIfNotExists('voices') : 
                $this->createFolderIfNotExists('images');

            $query = "name='{$filename}' and '{$folderId}' in parents and trashed=false";
            $results = $this->service->files->listFiles(['q' => $query]);
            
            if (empty($results->getFiles())) {
                return null;
            }

            $file = $results->getFiles()[0];
            $response = $this->service->files->get($file->getId(), ['alt' => 'media']);
            
            return $response->getBody()->getContents();

        } catch (\Exception $e) {
            Log::error("Failed to download file {$filename}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * ลบไฟล์จาก Google Drive
     */
    public function deleteFile($filename, $type = 'voice')
    {
        try {
            $folderId = $type === 'voice' ? 
                $this->createFolderIfNotExists('voices') : 
                $this->createFolderIfNotExists('images');

            $query = "name='{$filename}' and '{$folderId}' in parents and trashed=false";
            $results = $this->service->files->listFiles(['q' => $query]);
            
            if (empty($results->getFiles())) {
                return false;
            }

            $file = $results->getFiles()[0];
            $this->service->files->delete($file->getId());
            
            Log::info("File deleted from Google Drive: {$filename}");
            return true;

        } catch (\Exception $e) {
            Log::error("Failed to delete file {$filename}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ตรวจสอบการเชื่อมต่อ Google Drive
     */
    public function testConnection()
    {
        try {
            // ทดสอบโดยการดึงข้อมูลโฟลเดอร์หลัก
            $file = $this->service->files->get($this->parentFolderId);
            return [
                'success' => true,
                'folder_name' => $file->getName(),
                'folder_id' => $file->getId()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}