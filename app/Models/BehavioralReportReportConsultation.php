<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BehavioralReport extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'behavioral_report';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'who',
        'school', 
        'message',
        'voice',
        'voice_data',
        'voice_mime_type',
        'image',
        'image_data',
        'latitude',
        'longitude',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'image' => 'array',
        'image_data' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * บันทึกไฟล์เสียงเป็น base64 ในฐานข้อมูล
     *
     * @param string $audioData
     * @return void
     */
    public function saveVoiceRecording($audioData)
    {
        // Check if it's a base64 data URL
        if (strpos($audioData, 'data:audio') === 0) {
            // แยก mime type และ base64 data
            $dataParts = explode(',', $audioData, 2);
            if (count($dataParts) === 2) {
                $headerPart = $dataParts[0]; // data:audio/mpeg;base64
                $base64Data = $dataParts[1]; // actual base64 data
                
                // Extract mime type
                preg_match('/data:([^;]+)/', $headerPart, $matches);
                $mimeType = isset($matches[1]) ? $matches[1] : 'audio/mpeg';
                
                // Update the model
                $this->voice_data = $base64Data;
                $this->voice_mime_type = $mimeType;
                $this->voice = 'stored_in_database';
                $this->save();
            }
        }
    }

    /**
     * บันทึกรูปภาพเป็น base64 array ในฐานข้อมูล
     *
     * @param array $photos
     * @return void
     */
    public function saveImages($photos)
    {
        $imageDataArray = [];
        
        // Process each image
        foreach ($photos as $index => $photo) {
            // อ่านไฟล์และแปลงเป็น base64
            $imageContent = file_get_contents($photo->getPathname());
            $base64Image = base64_encode($imageContent);
            $mimeType = $photo->getMimeType();
            
            // เก็บข้อมูลรูปพร้อม metadata
            $imageDataArray[] = [
                'data' => $base64Image,
                'mime_type' => $mimeType,
                'original_name' => $photo->getClientOriginalName(),
                'size' => $photo->getSize(),
                'index' => $index + 1
            ];
        }
        
        // Update the model
        $this->image_data = json_encode($imageDataArray, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $this->image = json_encode(array_column($imageDataArray, 'original_name'), JSON_UNESCAPED_UNICODE);
        $this->save();
    }

    /**
     * ดึงข้อมูลเสียงเป็น data URL
     *
     * @return string|null
     */
    public function getVoiceDataUrl()
    {
        if ($this->voice_data && $this->voice_mime_type) {
            return "data:{$this->voice_mime_type};base64,{$this->voice_data}";
        }
        return null;
    }

    /**
     * ดึงข้อมูลรูปภาพทั้งหมดเป็น array ของ data URLs
     *
     * @return array
     */
    public function getImageDataUrls()
    {
        if (!$this->image_data) {
            return [];
        }

        $images = is_string($this->image_data) ? json_decode($this->image_data, true) : $this->image_data;
        if (!is_array($images)) {
            return [];
        }

        $dataUrls = [];
        foreach ($images as $image) {
            if (isset($image['data']) && isset($image['mime_type'])) {
                $dataUrls[] = [
                    'data_url' => "data:{$image['mime_type']};base64,{$image['data']}",
                    'original_name' => $image['original_name'] ?? 'unknown',
                    'size' => $image['size'] ?? 0,
                    'index' => $image['index'] ?? 1
                ];
            }
        }

        return $dataUrls;
    }

    /**
     * ดึงข้อมูลรูปภาพตาม index เป็น data URL
     *
     * @param int $index
     * @return string|null
     */
    public function getImageDataUrl($index)
    {
        if (!$this->image_data) {
            return null;
        }

        $images = is_string($this->image_data) ? json_decode($this->image_data, true) : $this->image_data;
        if (!is_array($images)) {
            return null;
        }
        
        foreach ($images as $image) {
            if (isset($image['index']) && $image['index'] == $index && isset($image['data']) && isset($image['mime_type'])) {
                return "data:{$image['mime_type']};base64,{$image['data']}";
            }
        }

        return null;
    }

    /**
     * ตรวจสอบว่ามีไฟล์เสียงหรือไม่
     *
     * @return bool
     */
    public function hasVoice()
    {
        return !empty($this->voice_data);
    }

    /**
     * ตรวจสอบว่ามีรูปภาพหรือไม่
     *
     * @return bool
     */
    public function hasImages()
    {
        return !empty($this->image_data);
    }

    /**
     * นับจำนวนรูปภาพ
     *
     * @return int
     */
    public function getImageCount()
    {
        if (!$this->image_data) {
            return 0;
        }

        $images = is_string($this->image_data) ? json_decode($this->image_data, true) : $this->image_data;
        return is_array($images) ? count($images) : 0;
    }

    /**
     * ตรวจสอบขนาดไฟล์เสียงใน bytes
     *
     * @return int
     */
    public function getVoiceSize()
    {
        if (!$this->voice_data) {
            return 0;
        }
        
        // คำนวณขนาดจาก base64 (base64 ใช้พื้นที่มากกว่าจริง ~33%)
        return intval(strlen($this->voice_data) * 0.75);
    }

    /**
     * ตรวจสอบขนาดรูปภาพทั้งหมดใน bytes
     *
     * @return int
     */
    public function getTotalImagesSize()
    {
        if (!$this->image_data) {
            return 0;
        }

        $images = is_string($this->image_data) ? json_decode($this->image_data, true) : $this->image_data;
        if (!is_array($images)) {
            return 0;
        }

        $totalSize = 0;
        foreach ($images as $image) {
            if (isset($image['size'])) {
                $totalSize += $image['size'];
            }
        }

        return $totalSize;
    }
}