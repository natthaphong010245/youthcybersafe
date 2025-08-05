<?php

namespace App\Models\ReportConsultation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class BehavioralReportReportConsultation extends Model
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
        'image',
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
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'created_at' => 'datetime',
    ];

    /**
     * บันทึกไฟล์เสียงและอัปเดตค่าในฐานข้อมูล
     *
     * @param string $audioData
     * @return void
     */
    public function saveVoiceRecording($audioData)
    {
        // Check if it's a base64 data URL
        if (strpos($audioData, 'data:audio') === 0) {
            // Extract base64 data
            $audioData = substr($audioData, strpos($audioData, ',') + 1);
            $audioData = base64_decode($audioData);
            
            // Create directory if it doesn't exist
            $directory = public_path('voice/behavioral_report');
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            
            // Save the file
            $audioFilename = $this->id . '.mp3';
            File::put($directory . '/' . $audioFilename, $audioData);
            
            // Update the model
            $this->voice = $audioFilename;
            $this->save();
        }
    }

    /**
     * บันทึกรูปภาพและอัปเดตค่าในฐานข้อมูล
     *
     * @param array $photos
     * @return void
     */
    public function saveImages($photos)
    {
        $images = [];
        
        // Create directory if it doesn't exist
        $directory = public_path("images/behavioral_report/{$this->id}");
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        // Process each image
        foreach ($photos as $index => $photo) {
            $index++; // Start from 1
            $extension = $photo->getClientOriginalExtension();
            $filename = "{$index}.{$extension}";
            
            // Move the file
            $photo->move($directory, $filename);
            
            // Add to images array
            $images[] = "images/behavioral_report/{$this->id}/{$filename}";
        }
        
        // Update the model - เก็บเป็น JSON String โดยไม่หลบ slashes
        $this->image = json_encode($images, JSON_UNESCAPED_SLASHES);
        $this->save();
    }
}