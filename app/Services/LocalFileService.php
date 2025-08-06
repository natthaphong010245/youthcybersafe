<?php
// app/Services/LocalFileService.php
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LocalFileService
{
    /**
     * สร้างชื่อไฟล์ตามรูปแบบ ปี-เดือน-วัน_ชั่วโมง-นาที
     */
    private function generateFileName($extension = null): string
    {
        $now = Carbon::now();
        $filename = $now->format('Y-n-j_G-i'); // 2025-6-15_10-15
        
        if ($extension) {
            $filename .= '.' . $extension;
        }
        
        return $filename;
    }

    /**
     * ทดสอบการทำงาน
     */
    public function testConnection(): array
    {
        try {
            // ทดสอบสร้างโฟลเดอร์
            $testDir = public_path('test_upload');
            if (!File::isDirectory($testDir)) {
                File::makeDirectory($testDir, 0755, true);
            }
            
            // ลบโฟลเดอร์ทดสอบ
            File::deleteDirectory($testDir);
            
            return [
                'success' => true,
                'message' => 'Local file service is working',
                'storage_path' => public_path('uploads')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * อัปโหลดรูปภาพ
     */
    public function uploadImage(UploadedFile $file, int $reportId, int $imageIndex): array
    {
        try {
            $extension = $file->getClientOriginalExtension();
            $filename = $this->generateFileName() . '_' . $imageIndex . '.' . $extension;
            
            // สร้างโฟลเดอร์
            $directory = public_path("uploads/behavioral_report/images/{$reportId}");
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            
            // ย้ายไฟล์
            $file->move($directory, $filename);
            
            return [
                'success' => true,
                'public_id' => "behavioral_report/images/{$reportId}/{$filename}",
                'secure_url' => asset("uploads/behavioral_report/images/{$reportId}/{$filename}"),
                'filename' => $filename
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * อัปโหลดไฟล์เสียง
     */
    public function uploadVoice(string $audioData, int $reportId): array
{
    try {
        $filename = $this->generateFileName('mp3');
        
        // เพิ่ม logging เพื่อ debug
        \Log::info('Upload voice debug:', [
            'report_id' => $reportId,
            'filename' => $filename,
            'audio_data_length' => strlen($audioData),
            'has_data_prefix' => strpos($audioData, 'data:audio') === 0
        ]);
        
        // ตรวจสอบว่าเป็น base64 data URL หรือไม่
        if (strpos($audioData, 'data:audio') === 0) {
            // แยกส่วน base64 data ออกมา
            $audioData = substr($audioData, strpos($audioData, ',') + 1);
            \Log::info('After removing data prefix:', ['length' => strlen($audioData)]);
        }

        // Decode base64
        $decodedAudio = base64_decode($audioData);
        $decodedLength = strlen($decodedAudio);
        
        \Log::info('Decoded audio:', [
            'decoded_length' => $decodedLength,
            'is_valid' => $decodedLength > 0
        ]);
        
        if ($decodedLength === 0) {
            return [
                'success' => false,
                'error' => 'Decoded audio data is empty'
            ];
        }

        // สร้างโฟลเดอร์
        $directory = public_path("uploads/behavioral_report/voice/{$reportId}");
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
            \Log::info('Created directory:', ['path' => $directory]);
        }
        
        // บันทึกไฟล์
        $filePath = $directory . '/' . $filename;
        $writeResult = File::put($filePath, $decodedAudio);
        
        \Log::info('File write result:', [
            'file_path' => $filePath,
            'write_result' => $writeResult,
            'file_exists' => file_exists($filePath),
            'file_size' => file_exists($filePath) ? filesize($filePath) : 0
        ]);

        // ตรวจสอบว่าไฟล์ถูกสร้างจริงหรือไม่
        if (!file_exists($filePath) || filesize($filePath) === 0) {
            return [
                'success' => false,
                'error' => 'Failed to create voice file or file is empty'
            ];
        }

        // สร้าง URL เต็ม
        $fullUrl = asset("uploads/behavioral_report/voice/{$reportId}/{$filename}");

        return [
            'success' => true,
            'public_id' => "behavioral_report/voice/{$reportId}/{$filename}",
            'secure_url' => $fullUrl,
            'filename' => $filename,
            'full_url' => $fullUrl,
            'file_size' => filesize($filePath)
        ];
    } catch (\Exception $e) {
        \Log::error('Voice upload error:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}
    /**
     * อัปโหลดไฟล์หลายไฟล์พร้อมกัน
     */
    public function uploadMultipleImages(array $files, int $reportId): array
    {
        $results = [];
        $urls = [];
        $filenames = [];

        foreach ($files as $index => $file) {
            $result = $this->uploadImage($file, $reportId, $index + 1);
            
            if ($result['success']) {
                $urls[] = $result['secure_url'];
                $filenames[] = $result['filename'];
            }
            
            $results[] = $result;
        }

        return [
            'results' => $results,
            'urls' => $urls,
            'filenames' => $filenames,
            'success_count' => count($urls),
            'total_count' => count($files)
        ];
    }

    /**
     * ลบไฟล์
     */
    public function deleteFile(string $publicId, string $resourceType = 'image'): array
    {
        try {
            $filePath = public_path("uploads/{$publicId}");
            
            if (File::exists($filePath)) {
                File::delete($filePath);
                
                return [
                    'success' => true,
                    'result' => 'File deleted successfully'
                ];
            }
            
            return [
                'success' => false,
                'error' => 'File not found'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}