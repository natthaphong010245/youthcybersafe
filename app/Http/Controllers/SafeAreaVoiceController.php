<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VoiceSafeArea;
use Illuminate\Support\Facades\Log;

class SafeAreaVoiceController extends Controller
{
    /**
     * Display the voice recording page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('safe_area.voice_recorder');
    }

    /**
     * Store a new voice recording.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'audio_file' => 'required|file',
        ]);

        try {
            // Create a new record in the database
            // For this case, we only track usage statistics, not the actual recording
            $voiceRecord = VoiceSafeArea::create([
                // Only storing timestamp automatically via created_at
            ]);

            // Log the successful creation
            Log::info('Voice record created with ID: ' . $voiceRecord->id);

            // If you want to return a JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'บันทึกข้อมูลสำเร็จ',
                    'id' => $voiceRecord->id
                ]);
            }

            // If coming from an HTML form, redirect to home
            return redirect()->route('home')->with('success', 'บันทึกข้อมูลสำเร็จ');
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error storing voice record: ' . $e->getMessage());

            // Return appropriate response based on request type
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล'
                ], 500);
            }

            // If coming from an HTML form, redirect back with error
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
}