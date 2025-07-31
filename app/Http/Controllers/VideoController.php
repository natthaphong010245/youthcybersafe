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
                [
                    'id' => 3,
                    'title' => 'ผลกระทบของการรังแกทางออนไลน์ที่คุณต้องรู้ | คุณนายตำรวจเตือนภัย',
                    'youtube_id' => '2oUuSjwzgfQ',
                    'thumbnail' => $this->getYouTubeThumbnail('2oUuSjwzgfQ'),
                    'duration' => '01:09',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
                [
                    'id' => 4,
                    'title' => 'สัญญาณเตือนภัย Warning Signal การรังแก ที่ผู้ปกครองและครูต้องรู้',
                    'youtube_id' => '9k_V7hp5lRA',
                    'thumbnail' => $this->getYouTubeThumbnail('9k_V7hp5lRA'),
                    'duration' => '00:55',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
                [
                    'id' => 5,
                    'title' => 'สาเหตุของการรังแกกันทางไซเบอร์ Cyberbullying | รากเหง้าของปัญหาที่ต้องรู้',
                    'youtube_id' => 'VQFr4bg7FIc',
                    'thumbnail' => $this->getYouTubeThumbnail('VQFr4bg7FIc'),
                    'duration' => '02:37',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
                [
                    'id' => 6,
                    'title' => 'วิธีการรับมือ Cyberbullying | 5 ขั้นตอน STOP BLOCK TELL REMOVE BE STRONG',
                    'youtube_id' => '67z5uTSghZ4',
                    'thumbnail' => $this->getYouTubeThumbnail('67z5uTSghZ4'),
                    'duration' => '03:30',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
                [
                    'id' => 7,
                    'title' => 'การใช้คอมพิวเตอร์อย่างปลอดภัย ด้วยหลัก C.O.N.N.E.C.T | 7 ขั้นตอนสู่โลกดิจิทัลที่ปลอดภัย',
                    'youtube_id' => 'HH10Up0ZqNg',
                    'thumbnail' => $this->getYouTubeThumbnail('HH10Up0ZqNg'),
                    'duration' => '06:25',
                    'category' => 'การรังแกทางไซเบอร์'
                ],

            ],
            2 => [ // อาข่า
                [
                    'id' => 1,
                    'title' => 'รู้จัก Bullying การรังแก 4 ประเภท หลัก 3 จ. ที่เด็กต้องรู้',
                    'youtube_id' => '2w4hTlRtAkc',
                    'thumbnail' => $this->getYouTubeThumbnail('2w4hTlRtAkc'),
                    'duration' => '03:07',
                    'category' => 'การรังแก'
                ],
                [
                    'id' => 2,
                    'title' => 'รู้จัก Cyber Bullying การรังแกทางไซเบอร์ 9 รูปแบบที่เด็กควรรู้ และหลัก 2A✨',
                    'youtube_id' => '0KPtJG4MHpE',
                    'thumbnail' => $this->getYouTubeThumbnail('0KPtJG4MHpE'),
                    'duration' => '03:27',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
                [
                    'id' => 3,
                    'title' => 'ผลกระทบของการรังแกทางออนไลน์ที่คุณต้องรู้ | คุณนายตำรวจเตือนภัย',
                    'youtube_id' => '4ZDCkQCGacA',
                    'thumbnail' => $this->getYouTubeThumbnail('4ZDCkQCGacA'),
                    'duration' => '01:43',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
                [
                    'id' => 4,
                    'title' => 'สัญญาณเตือนภัย Warning Signal การรังแก ที่ผู้ปกครองและครูต้องรู้',
                    'youtube_id' => '5mek05e2cPw',
                    'thumbnail' => $this->getYouTubeThumbnail('5mek05e2cPw'),
                    'duration' => '01:25',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
            ],
            3 => [ // ลาหู่
               
            ],

            4 => [ // ม้ง
                [
                    'id' => 1,
                    'title' => 'รู้จัก Bullying การรังแก 4 ประเภท หลัก 3 จ. ที่เด็กต้องรู้',
                    'youtube_id' => 'vevLkt6vce8',
                    'thumbnail' => $this->getYouTubeThumbnail('vevLkt6vce8'),
                    'duration' => '02:50',
                    'category' => 'การรังแก'
                ],
                [
                    'id' => 2,
                    'title' => 'รู้จัก Cyber Bullying การรังแกทางไซเบอร์ 9 รูปแบบที่เด็กควรรู้ และหลัก 2A✨',
                    'youtube_id' => 'qzhSfP9PYVA',
                    'thumbnail' => $this->getYouTubeThumbnail('qzhSfP9PYVA'),
                    'duration' => '03:21',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
                [
                    'id' => 3,
                    'title' => 'ผลกระทบของการรังแกทางออนไลน์ที่คุณต้องรู้ | คุณนายตำรวจเตือนภัย',
                    'youtube_id' => '9tIHJ5nWFJE',
                    'thumbnail' => $this->getYouTubeThumbnail('9tIHJ5nWFJE'),
                    'duration' => '01:24',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
                [
                    'id' => 4,
                    'title' => 'สัญญาณเตือนภัย Warning Signal การรังแก ที่ผู้ปกครองและครูต้องรู้',
                    'youtube_id' => 'To3tuouQDEI',
                    'thumbnail' => $this->getYouTubeThumbnail('To3tuouQDEI'),
                    'duration' => '00:55',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
                [
                    'id' => 5,
                    'title' => 'สาเหตุของการรังแกกันทางไซเบอร์ Cyberbullying | รากเหง้าของปัญหาที่ต้องรู้',
                    'youtube_id' => 'MIps8dFe3v4',
                    'thumbnail' => $this->getYouTubeThumbnail('MIps8dFe3v4'),
                    'duration' => '03:22',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
                [
                    'id' => 6,
                    'title' => 'วิธีการรับมือ Cyberbullying | 5 ขั้นตอน STOP BLOCK TELL REMOVE BE STRONG',
                    'youtube_id' => 'e9dq8e5QwdY',
                    'thumbnail' => $this->getYouTubeThumbnail('e9dq8e5QwdY'),
                    'duration' => '03:38',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
                [
                    'id' => 7,
                    'title' => 'การใช้คอมพิวเตอร์อย่างปลอดภัย ด้วยหลัก C.O.N.N.E.C.T | 7 ขั้นตอนสู่โลกดิจิทัลที่ปลอดภัย',
                    'youtube_id' => 'BD4Hjq4UC0Q',
                    'thumbnail' => $this->getYouTubeThumbnail('BD4Hjq4UC0Q'),
                    'duration' => '09:30',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
            ],

            5 => [ // เย้า
               
            ],

            6 => [ // กระเหรี่ยง
        
               
            ],

            7 => [ // ลีซอ
                                [
                    'id' => 1,
                    'title' => 'รู้จัก Bullying การรังแก 4 ประเภท หลัก 3 จ. ที่เด็กต้องรู้',
                    'youtube_id' => 'xD2GnOeGsCw',
                    'thumbnail' => $this->getYouTubeThumbnail('xD2GnOeGsCw'),
                    'duration' => '03:07',
                    'category' => 'การรังแก'
                ],

                [
                    'id' => 3,
                    'title' => 'ผลกระทบของการรังแกทางออนไลน์ที่คุณต้องรู้ | คุณนายตำรวจเตือนภัย',
                    'youtube_id' => '6K-DGwx7Xt4',
                    'thumbnail' => $this->getYouTubeThumbnail('6K-DGwx7Xt4'),
                    'duration' => '01:42',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
               [
                    'id' => 4,
                    'title' => 'สัญญาณเตือนภัย Warning Signal การรังแก ที่ผู้ปกครองและครูต้องรู้',
                    'youtube_id' => 'QSREpMRFABk',
                    'thumbnail' => $this->getYouTubeThumbnail('QSREpMRFABk'),
                    'duration' => '01:12',
                    'category' => 'การรังแกทางไซเบอร์'
                ],
            ],
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