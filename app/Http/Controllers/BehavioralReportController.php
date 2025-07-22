<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class BehavioralReportController extends Controller
{
    /**
     * Display the behavioral report form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('report&consultation.behavioral_report.behavioral_report');
    }
    
    /**
     * Store a newly created behavioral report in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'report_to' => 'required|in:teacher,researcher',
            'school' => 'nullable|required_if:report_to,teacher',
            'message' => 'required',
            'photos.*' => 'nullable|image|max:10240', // 10MB max
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        
        // Create and save the report to get the ID using DB directly
        $reportId = DB::table('behavioral_report')->insertGetId([
            'who' => $request->report_to,
            'school' => $request->report_to === 'researcher' ? null : $request->school,
            'message' => $request->message,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Handle voice recording
        if ($request->filled('audio_recording')) {
            $audioData = $request->audio_recording;
            
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
                $audioFilename = $reportId . '.mp3';
                File::put($directory . '/' . $audioFilename, $audioData);
                
                // Update the report
                DB::table('behavioral_report')
                    ->where('id', $reportId)
                    ->update(['voice' => $audioFilename]);
            }
        }
        
        // Handle image uploads
        if ($request->hasFile('photos')) {
            $images = [];
            
            // Create directory if it doesn't exist
            $directory = public_path("images/behavioral_report/{$reportId}");
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            
            // Process each image
            foreach ($request->file('photos') as $index => $photo) {
                $index++; // Start from 1
                $extension = $photo->getClientOriginalExtension();
                $filename = "{$index}.{$extension}";
                
                // Move the file
                $photo->move($directory, $filename);
                
                // Add to images array
                $images[] = "images/behavioral_report/{$reportId}/{$filename}";
            }
            
            // Update the report - ใช้ JSON_UNESCAPED_SLASHES เพื่อไม่ให้เกิด backslash
            DB::table('behavioral_report')
                ->where('id', $reportId)
                ->update(['image' => json_encode($images, JSON_UNESCAPED_SLASHES)]);
        }
        
        // ส่ง session success เพื่อแสดงป๊อปอัพและ redirect ไปยังหน้า behavioral_report
        return redirect()->route('behavioral_report')->with('success', 'รายงานพฤติกรรมของคุณถูกส่งเรียบร้อยแล้ว');
    }
}