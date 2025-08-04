<?php
// app/Services/GoogleDriveService.php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    private $client;
    private $service;
    private $isAvailable = false;
    
    public function __construct()
    {
        try {
            // ตรวจสอบว่า Google Client class มีอยู่หรือไม่
            if (!class_exists('\Google\Client')) {
                Log::error('Google Client class not found. Please install google/apiclient package.');
                throw new \Exception('Google API Client not installed');
            }

            $this->client = new \Google\Client();
            $this->client->setApplicationName('Youth Cyber Safe App');
            $this->client->setScopes([\Google\Service\Drive::DRIVE]);
            $this->client->setAccessType('offline');
            $this->client->setPrompt('select_account consent');
            
            // ตรวจสอบว่าไฟล์ credentials มีอยู่หรือไม่
            $credentialsPath = storage_path('app/google-drive-credentials.json');
            if (!file_exists($credentialsPath)) {
                Log::error('Google Drive credentials file not found at: ' . $credentialsPath);
                throw new \Exception('Google Drive credentials file not found');
            }
            
            $this->client->setAuthConfig($credentialsPath);
            $this->service = new \Google\Service\Drive($this->client);
            $this->isAvailable = true;
            
            Log::info('Google Drive Service initialized successfully');
            
        } catch (\Exception $e) {
            Log::error('Failed to initialize Google Drive Service: ' . $e->getMessage());
            $this->isAvailable = false;
        }
    }
    
    /**
     * ตรวจสอบว่า Google Drive Service พร้อมใช้งานหรือไม่
     */
    public function isAvailable()
    {
        return $this->isAvailable;
    }
    
    /**
     * สร้างโฟลเดอร์ใน Google Drive
     */
    public function createFolder($folderName, $parentFolderId = null)
    {
        if (!$this->isAvailable()) {
            throw new \Exception('Google Drive Service is not available');
        }
        
        try {
            $fileMetadata = new \Google\Service\Drive\DriveFile([
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder'
            ]);
            
            if ($parentFolderId) {
                $fileMetadata->setParents([$parentFolderId]);
            }
            
            $folder = $this->service->files->create($fileMetadata, [
                'fields' => 'id'
            ]);
            
            return $folder->getId();
        } catch (\Exception $e) {
            Log::error('Error creating folder: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * ค้นหาโฟลเดอร์
     */
    public function findFolder($folderName, $parentFolderId = null)
    {
        if (!$this->isAvailable()) {
            return null;
        }
        
        try {
            $query = "name = '{$folderName}' and mimeType = 'application/vnd.google-apps.folder' and trashed = false";
            
            if ($parentFolderId) {
                $query .= " and '{$parentFolderId}' in parents";
            }
            
            $response = $this->service->files->listFiles([
                'q' => $query,
                'fields' => 'files(id, name)'
            ]);
            
            $files = $response->getFiles();
            
            if (count($files) > 0) {
                return $files[0]->getId();
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Error finding folder: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * สร้างหรือหาโฟลเดอร์
     */
    public function getOrCreateFolder($folderName, $parentFolderId = null)
    {
        if (!$this->isAvailable()) {
            return null;
        }
        
        $folderId = $this->findFolder($folderName, $parentFolderId);
        
        if (!$folderId) {
            $folderId = $this->createFolder($folderName, $parentFolderId);
        }
        
        return $folderId;
    }
    
    /**
     * อัปโหลดไฟล์ไป Google Drive
     */
    public function uploadFile($fileContent, $fileName, $mimeType, $folderId = null)
    {
        if (!$this->isAvailable()) {
            return null;
        }
        
        try {
            $fileMetadata = new \Google\Service\Drive\DriveFile([
                'name' => $fileName
            ]);
            
            if ($folderId) {
                $fileMetadata->setParents([$folderId]);
            }
            
            $file = $this->service->files->create($fileMetadata, [
                'data' => $fileContent,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);
            
            return $file->getId();
        } catch (\Exception $e) {
            Log::error('Error uploading file: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * สร้างโครงสร้างโฟลเดอร์สำหรับ behavioral_report
     */
    public function setupBehavioralReportFolders()
    {
        if (!$this->isAvailable()) {
            throw new \Exception('Google Drive Service is not available');
        }
        
        // สร้างโฟลเดอร์หลัก behavioral_report
        $mainFolderId = $this->getOrCreateFolder('behavioral_report');
        
        if (!$mainFolderId) {
            throw new \Exception('Cannot create main behavioral_report folder');
        }
        
        // สร้างโฟลเดอร์ย่อย images และ voices
        $imagesFolderId = $this->getOrCreateFolder('images', $mainFolderId);
        $voicesFolderId = $this->getOrCreateFolder('voices', $mainFolderId);
        
        return [
            'main' => $mainFolderId,
            'images' => $imagesFolderId,
            'voices' => $voicesFolderId
        ];
    }
    
    /**
     * สร้างชื่อไฟล์ตามรูปแบบ ปี เดือน วัน เวลา
     */
    public function generateFileName($extension = '', $suffix = '')
    {
        $timestamp = now()->format('Y_m_d_H_i_s');
        $filename = $timestamp;
        
        if ($suffix) {
            $filename .= '_' . $suffix;
        }
        
        if ($extension) {
            $filename .= '.' . $extension;
        }
        
        return $filename;
    }
    
    /**
     * อัปโหลดรูปภาพไป Google Drive
     */
    public function uploadImage($imageData, $originalName, $reportId)
    {
        if (!$this->isAvailable()) {
            return null;
        }
        
        $folders = $this->setupBehavioralReportFolders();
        
        if (!$folders['images']) {
            throw new \Exception('Cannot access images folder');
        }
        
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $fileName = $this->generateFileName($extension, $reportId);
        
        $fileId = $this->uploadFile($imageData, $fileName, 'image/' . $extension, $folders['images']);
        
        return [
            'file_id' => $fileId,
            'file_name' => $fileName
        ];
    }
    
    /**
     * อัปโหลดไฟล์เสียงไป Google Drive
     */
    public function uploadVoice($audioData, $reportId)
    {
        if (!$this->isAvailable()) {
            return null;
        }
        
        $folders = $this->setupBehavioralReportFolders();
        
        if (!$folders['voices']) {
            throw new \Exception('Cannot access voices folder');
        }
        
        $fileName = $this->generateFileName('mp3', $reportId);
        
        $fileId = $this->uploadFile($audioData, $fileName, 'audio/mpeg', $folders['voices']);
        
        return [
            'file_id' => $fileId,
            'file_name' => $fileName
        ];
    }
}