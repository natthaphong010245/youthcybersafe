<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoController extends Controller
{
    private $languages = [
        1 => 'ไทย',
        2 => 'อาข่า', 
        3 => 'ลาหู่',
        4 => 'ม้ง',
        5 => 'เย้า',
        6 => 'กระเหรี่ยง',
        7 => 'ลีซู'
    ];

    public function selectLanguage()
    {
        return view('video.select_language', [
            'languages' => $this->languages
        ]);
    }

    public function showVideos($language)
    {
        if (!array_key_exists($language, $this->languages)) {
            abort(404);
        }

        $videos = $this->getVideosByLanguage($language);
        
        return view('video.show_videos', [
            'language' => $language,
            'languageName' => $this->languages[$language],
            'videos' => $videos
        ]);
    }

    private function getVideosByLanguage($language)
    {
        $allVideos = [
            1 => [ // ภาษาไทย
                [
                    'id' => 1,
                    'title' => 'วิดีโอตัวอย่างภาษาไทย ตอนที่ 1',
                    'youtube_id' => 'S9lSM99___w',
                    'thumbnail' => $this->getYouTubeThumbnail('S9lSM99___w'),
                    'duration' => '03:11',
                    'category' => 'การป้องกัน'
                ],
                [
                    'id' => 2,
                    'title' => 'การป้องกันไซเบอร์บูลลี่ภาษาไทย ตอนที่ 2',
                    'youtube_id' => 'jNQXAC9IVRw',
                    'thumbnail' => $this->getYouTubeThumbnail('jNQXAC9IVRw'),
                    'duration' => '08:15',
                    'category' => 'การป้องกัน'
                ],
                [
                    'id' => 3,
                    'title' => 'จิตวิทยาเด็กและเยาวชนภาษาไทย ตอนที่ 1',
                    'youtube_id' => 'y6120QOlsfU',
                    'thumbnail' => $this->getYouTubeThumbnail('y6120QOlsfU'),
                    'duration' => '10:20',
                    'category' => 'จิตวิทยา'
                ]
            ],
            2 => [ // อาข่า
                [
                    'id' => 4,
                    'title' => 'การป้องกันไซเบอร์บูลลี่ภาษาอาข่า ตอนที่ 1',
                    'youtube_id' => 'M7lc1UVf-VE',
                    'thumbnail' => $this->getYouTubeThumbnail('M7lc1UVf-VE'),
                    'duration' => '06:45',
                    'category' => 'การป้องกัน'
                ]
            ],
            3 => [ // ลาหู่
                [
                    'id' => 5,
                    'title' => 'การป้องกันไซเบอร์บูลลี่ภาษาลาหู่ ตอนที่ 1',
                    'youtube_id' => 'dQw4w9WgXcQ',
                    'thumbnail' => $this->getYouTubeThumbnail('dQw4w9WgXcQ'),
                    'duration' => '07:30',
                    'category' => 'การป้องกัน'
                ]
            ]
        ];

        return $allVideos[$language] ?? [
            [
                'id' => 0,
                'title' => "วิดีโอ{$this->languages[$language]} (กำลังอัพเดท)",
                'youtube_id' => 'dQw4w9WgXcQ',
                'thumbnail' => $this->getYouTubeThumbnail('dQw4w9WgXcQ'),
                'duration' => '00:00',
                'category' => 'ทั่วไป'
            ]
        ];
    }

    public function getYouTubeThumbnail($youtubeId, $quality = 'mqdefault')
    {
        $qualities = [
            'maxresdefault', // 1280x720 (HD)
            'sddefault',     // 640x480 (SD)
            'hqdefault',     // 480x360 (HQ)
            'mqdefault',     // 320x180 (MQ)
            'default'        // 120x90 (thumbnail)
        ];
        
        return "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg";
    }

    public static function getYouTubeEmbedUrl($youtubeId)
    {
        return "https://www.youtube.com/embed/{$youtubeId}";
    }

    public function mainVideo()
    {
        return $this->selectLanguage();
    }
}