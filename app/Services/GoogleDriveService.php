<?php
// app/Services/GoogleDriveService.php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    private $client;
    private $service;
    
    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Youth Cyber Safe App');
        $this->client->setScopes([Drive::DRIVE]);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
        
        // ใช้ Service Account JSON file
        $this->client->setAuthConfig(storage_path('app/google-drive-credentials.json'));
        
        $this->service = new Drive($this->client);
    }
    
    /**
     * สร้างโฟลเดอร์ใน Google Drive
     */
    public function createFolder($folderName, $parentFolderId = null)
    {
        $fileMetadata = new DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder'
        ]);
        
        if ($parentFolderId) {
            $fileMetadata->setParents([$parentFolderId]);
        }
        
        try {
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
        try {
            $fileMetadata = new DriveFile([
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
    
    /**
     * ลบไฟล์จาก Google Drive
     */
    public function deleteFile($fileId)
    {
        try {
            $this->service->files->delete($fileId);
            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting file: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * รับ URL สำหรับดาวน์โหลดไฟล์
     */
    public function getFileDownloadUrl($fileId)
    {
        return "https://drive.google.com/uc?id={$fileId}&export=download";
    }
    
    /**
     * รับข้อมูลไฟล์
     */
    public function getFileInfo($fileId)
    {
        try {
            return $this->service->files->get($fileId, [
                'fields' => 'id, name, size, mimeType, createdTime'
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting file info: ' . $e->getMessage());
            return null;
        }
    }
}