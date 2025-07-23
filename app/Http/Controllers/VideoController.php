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
                    'title' => 'รู้จัก Bullying การรังแก 4 ประเภท หลัก 3 จ. ที่เด็กต้องรู้',
                    'youtube_id' => 'fJwfh6GR03w',
                    'thumbnail' => $this->getYouTubeThumbnail('fJwfh6GR03w'),
                    'duration' => '02:18',
                    'category' => 'การรังแก'
                ],
                [
                    'id' => 2,
                    'title' => 'รู้จัก Cyber Bullying การรังแกทางไซเบอร์ 9 รูปแบบที่เด็กควรรู้ และหลัก 2A✨',
                    'youtube_id' => 'qNyWgnQul_s',
                    'thumbnail' => $this->getYouTubeThumbnail('qNyWgnQul_s'),
                    'duration' => '02:58',
                    'category' => 'การรังแกทางไซเบอร์'
                ],

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
                'youtube_id' => 'fJwfh6GR03w',
                'thumbnail' => $this->getYouTubeThumbnail('fJwfh6GR03w'),
                'duration' => '00:00',
                'category' => 'ทั่วไป'
            ]
        ];
    }

    public function getYouTubeThumbnail($youtubeId, $quality = 'mqdefault')
    {
        $qualities = [
            'maxresdefault', 
            'sddefault',    
            'hqdefault',     
            'mqdefault',     
            'default'        
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