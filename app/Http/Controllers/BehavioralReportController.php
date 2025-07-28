<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        
        // เตรียมข้อมูลสำหรับบันทึก
        $reportData = [
            'who' => $request->report_to,
            'school' => $request->report_to === 'researcher' ? null : $request->school,
            'message' => $request->message,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Handle voice recording - เก็บเป็น base64 ใน database
        if ($request->filled('audio_recording')) {
            $audioData = $request->audio_recording;
            
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
                    
                    // เก็บข้อมูลเสียงใน database
                    $reportData['voice_data'] = $base64Data;
                    $reportData['voice_mime_type'] = $mimeType;
                    $reportData['voice'] = 'stored_in_database'; // เก็บไว้เพื่อ backward compatibility
                }
            }
        }

        // Handle image uploads - เก็บเป็น base64 array ใน database
        $imageDataArray = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
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
            
            // เก็บ array ของรูปภาพเป็น JSON
            $reportData['image_data'] = json_encode($imageDataArray, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $reportData['image'] = json_encode(array_column($imageDataArray, 'original_name'), JSON_UNESCAPED_UNICODE); // เก็บชื่อไฟล์เพื่อ backward compatibility
        }
        
        // Create and save the report
        $reportId = DB::table('behavioral_report')->insertGetId($reportData);
        
        // ส่ง session success เพื่อแสดงป๊อปอัพและ redirect ไปยังหน้า behavioral_report
        return redirect()->route('behavioral_report')->with('success', 'รายงานพฤติกรรมของคุณถูกส่งเรียบร้อยแล้ว');
    }

    /**
     * แสดงไฟล์เสียงจาก database
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getVoice($id)
    {
        $report = DB::table('behavioral_report')
            ->select('voice_data', 'voice_mime_type')
            ->where('id', $id)
            ->first();

        if (!$report || !$report->voice_data) {
            abort(404, 'ไม่พบไฟล์เสียง');
        }

        $audioData = base64_decode($report->voice_data);
        $mimeType = $report->voice_mime_type ?: 'audio/mpeg';

        return response($audioData)
            ->header('Content-Type', $mimeType)
            ->header('Content-Length', strlen($audioData))
            ->header('Accept-Ranges', 'bytes');
    }

    /**
     * แสดงรูปภาพจาก database
     *
     * @param int $reportId
     * @param int $imageIndex
     * @return \Illuminate\Http\Response
     */
    public function getImage($reportId, $imageIndex)
    {
        $report = DB::table('behavioral_report')
            ->select('image_data')
            ->where('id', $reportId)
            ->first();

        if (!$report || !$report->image_data) {
            abort(404, 'ไม่พบรูปภาพ');
        }

        $images = json_decode($report->image_data, true);
        if (!$images || !is_array($images)) {
            abort(404, 'ข้อมูลรูปภาพไม่ถูกต้อง');
        }
        
        // หาภาพที่ต้องการตาม index
        $targetImage = null;
        foreach ($images as $image) {
            if (isset($image['index']) && $image['index'] == $imageIndex) {
                $targetImage = $image;
                break;
            }
        }

        if (!$targetImage || !isset($targetImage['data'])) {
            abort(404, 'ไม่พบรูปภาพที่ต้องการ');
        }

        $imageData = base64_decode($targetImage['data']);
        $mimeType = $targetImage['mime_type'] ?? 'image/jpeg';

        return response($imageData)
            ->header('Content-Type', $mimeType)
            ->header('Content-Length', strlen($imageData))
            ->header('Cache-Control', 'public, max-age=31536000'); // Cache 1 year
    }

    /**
     * แสดงรายละเอียดรายงาน
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $report = DB::table('behavioral_report')->where('id', $id)->first();
        
        if (!$report) {
            abort(404, 'ไม่พบรายงาน');
        }

        return view('report&consultation.behavioral_report.show', compact('report'));
    }

    /**
     * แสดงรายการรายงานทั้งหมด
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        // ดึงข้อมูลแบบ pagination โดยไม่รวม base64 เพื่อไม่ให้ช้า
        $reports = DB::table('behavioral_report')
            ->select([
                'id', 'who', 'school', 'message', 
                'latitude', 'longitude', 'created_at',
                // เช็คว่ามีไฟล์หรือไม่ โดยไม่ดึง base64 มา
                DB::raw('CASE WHEN voice_data IS NOT NULL AND LENGTH(voice_data) > 0 THEN 1 ELSE 0 END as has_voice'),
                DB::raw('CASE WHEN image_data IS NOT NULL AND LENGTH(image_data) > 0 THEN 1 ELSE 0 END as has_images')
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('report&consultation.behavioral_report.list', compact('reports'));
    }
}