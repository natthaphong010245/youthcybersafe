<?php

namespace App\Http\Controllers;

use App\Models\SafeArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SafeAreaMessageController extends Controller
{
    public function index()
    {
        return view('report&consultation.safe_area.message.message');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        try {
            $safeAreaRecord = SafeArea::createMessage();

            Log::info('Safe Area message record created with ID: ' . $safeAreaRecord->id);

            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'ขอบคุณสำหรับข้อความของคุณ',
                    'id' => $safeAreaRecord->id,
                    'type' => $safeAreaRecord->type
                ]);
            }

            return redirect()->route('main')->with('success', 'ขอบคุณสำหรับข้อความของคุณ');
        } catch (\Exception $e) {
            Log::error('Error storing safe area message: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อความ กรุณาลองใหม่อีกครั้ง'
                ], 500);
            }

            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อความ กรุณาลองใหม่อีกครั้ง')->withInput();
        }
    }
}