<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SafeArea;
use Illuminate\Support\Facades\Log;

class SafeAreaVoiceController extends Controller
{
    public function index()
    {
        return view('report&consultation.safe_area.voice.voice');
    }

    public function store(Request $request)
    {
        $request->validate([
            'audio_file' => 'required|file',
        ]);

        try {
            $safeAreaRecord = SafeArea::createVoice();

            Log::info('Safe Area voice record created with ID: ' . $safeAreaRecord->id);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'บันทึกข้อมูลสำเร็จ',
                    'id' => $safeAreaRecord->id,
                    'type' => $safeAreaRecord->type
                ]);
            }

            return redirect()->route('main')->with('success', 'บันทึกข้อมูลสำเร็จ');
            
        } catch (\Exception $e) {
            Log::error('Error storing safe area voice record: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล'
                ], 500);
            }

            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
}