<?php

namespace App\Http\Controllers;

use App\Models\MessageSafeArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SafeAreaMessageController extends Controller
{
    /**
     * Display the message form page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('safe_area.message.message');
    }

    /**
     * Store a new message in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        try {
            // Create a new record in the database
            $message = MessageSafeArea::create([
                'message' => $validated['message'],
            ]);

            // Log the successful creation
            Log::info('Message record created with ID: ' . $message->id);

            // Check if request wants JSON response (AJAX request)
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'ขอบคุณสำหรับข้อความของคุณ'
                ]);
            }

            // Redirect to home with success message for regular form submit
            return redirect()->route('home')->with('success', 'ขอบคุณสำหรับข้อความของคุณ');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error storing message: ' . $e->getMessage());

            // Return JSON error for AJAX requests
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อความ กรุณาลองใหม่อีกครั้ง'
                ], 500);
            }

            // Redirect back with error message for regular form submit
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อความ กรุณาลองใหม่อีกครั้ง')->withInput();
        }
    }
}