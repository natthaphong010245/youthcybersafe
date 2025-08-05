<?php
// app/Services/GoogleDriveService.php

namespace App\Services;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Exception;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    private $service;
    private $folderId;

    public function __construct()
    {
        $this->initializeService();
    }

    private function initializeService()
    {
        try {
            // ตรวจสอบไฟล์ service account key ก่อน
            $keyPath = storage_path('app/google/service-account-key.json');
            
            Log::info('Initializing Google Drive service', ['key_path' => $keyPath]);
            
            if (!file_exists($keyPath)) {
                throw new Exception("Service account key file not found at: {$keyPath}");
            }
            
            if (!is_readable($keyPath)) {
                throw new Exception("Service account key file is not readable at: {$keyPath}");
            }
            
            // ตรวจสอบขนาดไฟล์
            $fileSize = filesize($keyPath);
            if ($fileSize < 100) {
                throw new Exception("Service account key file seems too small ({$fileSize} bytes). Please check file content.");
            }
            
            // ตรวจสอบว่าไฟล์เป็น JSON หรือไม่
            $keyContent = file_get_contents($keyPath);
            $keyData = json_decode($keyContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Service account key file is not valid JSON: " . json_last_error_msg());
            }
            
            // ตรวจสอบ required fields
            $requiredFields = ['type', 'project_id', 'private_key', 'client_email'];
            foreach ($requiredFields as $field) {
                if (!isset($keyData[$field]) || empty($keyData[$field])) {
                    throw new Exception("Missing or empty required field '{$field}' in service account key file");
                }
            }
            
            Log::info('Service account key file validation passed', [
                'file_size' => $fileSize,
                'project_id' => $keyData['project_id'],
                'client_email' => $keyData['client_email']
            ]);

            // สร้าง Google Client
            $client = new Google_Client();
            $client->setAuthConfig($keyPath);
            $client->addScope(Google_Service_Drive::DRIVE_FILE);
            $client->setApplicationName('Youth Cyber Safe');

            Log::info('Google Client configured successfully');

            // สร้าง Google Drive Service
            $this->service = new Google_Service_Drive($client);
            
            Log::info('Google Drive service initialized successfully');
            
        } catch (Exception $e) {
            $errorMessage = 'Failed to initialize Google Drive service: ' . $e->getMessage();
            Log::error($errorMessage, [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception($errorMessage);
        }
    }

    /**
     * Create folder if not exists
     */
    public function createFolderIfNotExists($folderName, $parentId = null)
    {
        try {
            // Search for existing folder
            $query = "name='{$folderName}' and mimeType='application/vnd.google-apps.folder' and trashed=false";
            if ($parentId) {
                $query .= " and '{$parentId}' in parents";
            }

            $response = $this->service->files->listFiles([
                'q' => $query,
                'spaces' => 'drive'
            ]);

            if (count($response->files) > 0) {
                Log::info("Found existing folder: {$folderName}", ['id' => $response->files[0]->id]);
                return $response->files[0]->id;
            }

            // Create new folder
            $fileMetadata = new Google_Service_Drive_DriveFile([
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder'
            ]);

            if ($parentId) {
                $fileMetadata->setParents([$parentId]);
            }

            $folder = $this->service->files->create($fileMetadata, [
                'fields' => 'id'
            ]);

            Log::info("Created folder: {$folderName}", ['id' => $folder->id]);
            return $folder->id;

        } catch (Exception $e) {
            Log::error("Failed to create folder {$folderName}: " . $e->getMessage());
            throw new Exception("Failed to create folder: {$folderName}. Error: " . $e->getMessage());
        }
    }

    /**
     * Upload file to Google Drive
     */
    public function uploadFile($fileContent, $fileName, $mimeType, $folderId = null)
    {
        try {
            $fileMetadata = new Google_Service_Drive_DriveFile([
                'name' => $fileName
            ]);

            if ($folderId) {
                $fileMetadata->setParents([$folderId]);
            }

            $file = $this->service->files->create($fileMetadata, [
                'data' => $fileContent,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id,name,webViewLink'
            ]);

            Log::info("Uploaded file: {$fileName}", ['id' => $file->id]);
            
            return [
                'id' => $file->id,
                'name' => $file->name,
                'webViewLink' => $file->webViewLink
            ];

        } catch (Exception $e) {
            Log::error("Failed to upload file {$fileName}: " . $e->getMessage());
            throw new Exception("Failed to upload file: {$fileName}. Error: " . $e->getMessage());
        }
    }

    /**
     * Upload voice file to behavioral_report/voices folder
     */
    public function uploadVoiceFile($audioData, $fileName)
    {
        try {
            // Create main folder
            $mainFolderId = $this->createFolderIfNotExists('behavioral_report');
            
            // Create voices subfolder
            $voicesFolderId = $this->createFolderIfNotExists('voices', $mainFolderId);

            // Decode base64 audio data
            if (strpos($audioData, 'data:audio') === 0) {
                $audioData = substr($audioData, strpos($audioData, ',') + 1);
                $audioData = base64_decode($audioData);
            }

            return $this->uploadFile($audioData, $fileName, 'audio/mpeg', $voicesFolderId);

        } catch (Exception $e) {
            Log::error("Failed to upload voice file: " . $e->getMessage());
            throw new Exception("Failed to upload voice file: " . $e->getMessage());
        }
    }

    /**
     * Upload image file to behavioral_report/images folder
     */
    public function uploadImageFile($imageFile, $fileName)
    {
        try {
            // Create main folder
            $mainFolderId = $this->createFolderIfNotExists('behavioral_report');
            
            // Create images subfolder
            $imagesFolderId = $this->createFolderIfNotExists('images', $mainFolderId);

            // Get file content
            $fileContent = file_get_contents($imageFile->getPathname());
            $mimeType = $imageFile->getMimeType();

            return $this->uploadFile($fileContent, $fileName, $mimeType, $imagesFolderId);

        } catch (Exception $e) {
            Log::error("Failed to upload image file: " . $e->getMessage());
            throw new Exception("Failed to upload image file: " . $e->getMessage());
        }
    }

    /**
     * Delete file from Google Drive
     */
    public function deleteFile($fileId)
    {
        try {
            $this->service->files->delete($fileId);
            Log::info("Deleted file with ID: {$fileId}");
            return true;
        } catch (Exception $e) {
            Log::error("Failed to delete file {$fileId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate filename with timestamp
     */
    public static function generateFileName($extension = 'png')
    {
        return now()->format('Y-m-d_H-i') . '.' . $extension;
    }

    /**
     * Test connection without initializing full service
     */
    public static function testBasicConnection()
    {
        $keyPath = storage_path('app/google/service-account-key.json');
        
        $result = [
            'key_file_exists' => file_exists($keyPath),
            'key_file_readable' => is_readable($keyPath),
            'key_file_size' => file_exists($keyPath) ? filesize($keyPath) : 0,
            'key_path' => $keyPath
        ];
        
        if ($result['key_file_exists'] && $result['key_file_readable']) {
            $keyContent = file_get_contents($keyPath);
            $keyData = json_decode($keyContent, true);
            
            $result['json_valid'] = json_last_error() === JSON_ERROR_NONE;
            $result['json_error'] = json_last_error_msg();
            
            if ($result['json_valid']) {
                $result['project_id'] = $keyData['project_id'] ?? 'missing';
                $result['client_email'] = $keyData['client_email'] ?? 'missing';
                $result['has_private_key'] = isset($keyData['private_key']) && !empty($keyData['private_key']);
            }
        }
        
        return $result;
    }
}