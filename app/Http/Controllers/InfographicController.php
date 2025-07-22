<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class InfographicController extends Controller
{
    private $topics = [
        1 => [
            'title' => 'ความรู้เกี่ยวกับพฤติกรรมการรังแกกัน'
        ],
        2 => [
            'title' => 'มารู้จักการรังแกกันผ่านโลกไซเบอร์'
        ],
        3 => [
            'title' => 'สาเหตุของการกลั่นแกล้งบนโลกไซเบอร์'
        ],
        4 => [
            'title' => 'สัญญาณเตือนภัยของการรังแกกันโลกไซเบอร์'
        ],
        5 => [
            'title' => 'ผลกระทบของการรังแกกันบนโลกไซเบอร์'
        ],
        6 => [
            'title' => 'การรับมือการกลั่นแกล้งบนโลกออนไลน์'
        ]
    ];

    /**
     * Display infographic main page
     */
    public function index()
    {
        $topicsWithImages = [];
        
        foreach ($this->topics as $id => $topic) {
            $imageCount = $this->getImageCount($id);
            $topicsWithImages[$id] = array_merge($topic, [
                'image_count' => $imageCount,
                'available' => $imageCount > 0
            ]);
        }

        return view('infographic.index', [
            'topics' => $topicsWithImages
        ]);
    }

    /**
     * Display slideshow for specific topic
     */
    public function show($topicId)
    {
        if (!array_key_exists($topicId, $this->topics)) {
            abort(404, 'ไม่พบหัวข้อที่เลือก');
        }

        $topic = $this->topics[$topicId];
        $images = $this->getTopicImages($topicId);

        if (empty($images)) {
            return redirect()->route('infographic.index')
                ->with('error', 'ไม่พบเนื้อหาสำหรับหัวข้อนี้');
        }

        return view('infographic.slideshow', [
            'topic' => $topic,
            'images' => $images,
            'topicId' => $topicId,
            'totalImages' => count($images)
        ]);
    }

    /**
     * Get all images for a specific topic
     */
    private function getTopicImages($topicId)
    {
        $imagePath = public_path("images/infographic/{$topicId}");
        $images = [];

        if (!File::exists($imagePath)) {
            return $images;
        }

        $files = File::files($imagePath);
        
        // Sort files numerically
        usort($files, function($a, $b) {
            $aNum = (int) pathinfo($a->getFilename(), PATHINFO_FILENAME);
            $bNum = (int) pathinfo($b->getFilename(), PATHINFO_FILENAME);
            return $aNum <=> $bNum;
        });

        foreach ($files as $file) {
            if (in_array($file->getExtension(), ['png', 'jpg', 'jpeg', 'gif'])) {
                $images[] = [
                    'filename' => $file->getFilename(),
                    'path' => "images/infographic/{$topicId}/" . $file->getFilename(),
                    'number' => (int) pathinfo($file->getFilename(), PATHINFO_FILENAME)
                ];
            }
        }

        return $images;
    }

    /**
     * Get image count for a topic
     */
    private function getImageCount($topicId)
    {
        return count($this->getTopicImages($topicId));
    }

    /**
     * API endpoint to get images for AJAX loading
     */
    public function getImages($topicId)
    {
        $images = $this->getTopicImages($topicId);
        
        return response()->json([
            'success' => true,
            'images' => $images,
            'total' => count($images)
        ]);
    }

    /**
     * Check if topic has images available
     */
    public function checkAvailability($topicId)
    {
        $imageCount = $this->getImageCount($topicId);
        
        return response()->json([
            'available' => $imageCount > 0,
            'count' => $imageCount
        ]);
    }
}