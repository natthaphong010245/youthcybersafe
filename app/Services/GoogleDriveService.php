<?php
// app/Services/GoogleDriveService.php - Production Fixed Version

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class GoogleDriveService
{
    private $service;
    private $folderId;
    private $useLocalFallback = false;

    public function __construct()
    {
        $this->initializeService();
    }

    private function initializeService()
    {
        try {
            // ตรวจสอบว่า Google Client class มีอยู่หรือไม่
            if (!class_exists('Google_Client')) {
                throw new Exception('Google_Client class not found. Using local storage fallback.');
            }

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
                throw new Exception("Service account key file seems too small ({$fileSize} bytes).");
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
            $client = new \Google_Client();
            $client->setAuthConfig($keyPath);
            $client->addScope(\Google_Service_Drive::DRIVE_FILE);
            $client->setApplicationName('Youth Cyber Safe');

            Log::info('Google Client configured successfully');

            // สร้าง Google Drive Service
            $this->service = new \Google_Service_Drive($client);
            
            Log::info('Google Drive service initialized successfully');
            
        } catch (Exception $e) {
            $errorMessage = 'Failed to initialize Google Drive service: ' . $e->getMessage();
            Log::warning($errorMessage . ' - Using local storage fallback');
            
            $this->useLocalFallback = true;
            $this->initializeLocalStorage();
        }
    }

    /**
     * Initialize local storage fallback
     */
    private function initializeLocalStorage()
    {
        try {
            // สร้าง directories สำหรับ local storage
            $directories = ['behavioral_report', 'behavioral_report/images', 'behavioral_report/voices'];
            
            foreach ($directories as $dir) {
                if (!Storage::disk('public')->exists($dir)) {
                    Storage::disk('public')->makeDirectory($dir);
                }
            }
            
            Log::info('Local storage fallback initialized successfully');
            
        } catch (Exception $e) {
            Log::error('Failed to initialize local storage: ' . $e->getMessage());
        }
    }

    /**
     * Create folder if not exists
     */
    public function createFolderIfNotExists($folderName, $parentId = null)
    {
        if ($this->useLocalFallback) {
            return $this->createLocalFolder($folderName, $parentId);
        }

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
            $fileMetadata = new \Google_Service_Drive_DriveFile([
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
            return $this->createLocalFolder($folderName, $parentId);
        }
    }

    /**
     * Create local folder (fallback)
     */
    private function createLocalFolder($folderName, $parentId = null)
    {
        $path = $parentId ? "{$parentId}/{$folderName}" : $folderName;
        
        if (!Storage::disk('public')->exists("behavioral_report/{$path}")) {
            Storage::disk('public')->makeDirectory("behavioral_report/{$path}");
        }
        
        return "local_{$path}";
    }

    /**
     * Upload file to Google Drive
     */
    public function uploadFile($fileContent, $fileName, $mimeType, $folderId = null)
    {
        if ($this->useLocalFallback) {
            return $this->uploadLocalFile($fileContent, $fileName, $mimeType, $folderId);
        }

        try {
            $fileMetadata = new \Google_Service_Drive_DriveFile([
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
                'webViewLink' => $file->webViewLink,
                'storage_type' => 'google_drive'
            ];

        } catch (Exception $e) {
            Log::error("Failed to upload file {$fileName}: " . $e->getMessage());
            return $this->uploadLocalFile($fileContent, $fileName, $mimeType, $folderId);
        }
    }

    /**
     * Upload file to local storage (fallback)
     */
    private function uploadLocalFile($fileContent, $fileName, $mimeType, $folderId = null)
    {
        try {
            $path = $folderId ? "behavioral_report/{$folderId}/{$fileName}" : "behavioral_report/{$fileName}";
            
            Storage::disk('public')->put($path, $fileContent);
            
            $url = Storage::disk('public')->url($path);
            
            Log::info("Uploaded file to local storage: {$fileName}", ['path' => $path]);
            
            return [
                'id' => "local_" . md5($fileName . time()),
                'name' => $fileName,
                'webViewLink' => $url,
                'local_path' => $path,
                'storage_type' => 'local_storage'
            ];
            
        } catch (Exception $e) {
            Log::error("Failed to upload file to local storage: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Upload voice file
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
     * Upload image file
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
     * Generate filename with timestamp
     */
    public static function generateFileName($extension = 'png')
    {
        return now()->format('Y-m-d_H-i-s') . '_' . rand(1000, 9999) . '.' . $extension;
    }

    /**
     * Check if using local fallback
     */
    public function isUsingLocalFallback()
    {
        return $this->useLocalFallback;
    }

    /**
     * Get storage type
     */
    public function getStorageType()
    {
        return $this->useLocalFallback ? 'Local Storage' : 'Google Drive';
    }
}