@extends('layouts.game.causes_bullying.index')

@php
    $backUrl = '/category/game';
    $mainUrl = '/main';
@endphp

@section('content')
    @include('game.intro', [
        'title' => 'สาเหตุของการกลั่นแกล้งบนโลกออนไลน์',
        'gameNumber' => '9',
        'description' => 'ผลกระทบของผู้ถูกรังแกทางไซเบอร์',
        'actionText' => 'เริ่มความก้าวหน้ากันเลย'
    ])

    <div class="card-container space-y-6 px-6 md:px-0" id="game-content">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-indigo-800 mb-4">ผลกระทบของผู้ถูกรังแกทางไซเบอร์</h2>
            <p class="text-lg text-indigo-800 mt-2">เลือกรูปให้ตรงกับคำถาม</p>
        </div>
        
        <div class="space-y-4 mr-4 ml-4">
            <div class="choice-card wrong-choice bg-white rounded-xl p-6 cursor-pointer transition-all border-2 border-transparent hover:border-indigo-200 mr-2 ml-2 mb-12" style="box-shadow: 0 0 15px rgba(0,0,0,0.2);">
                <div class="flex justify-center">
                    <img src="{{ asset('images/game/9/true.png') }}" alt="Emotional Impact" class="w-80 h-48 object-contain">
                </div>
            </div>
            
            <div class="choice-card correct-choice bg-white rounded-xl p-6 cursor-pointer transition-all border-2 border-transparent hover:border-indigo-200 mr-2 ml-2 mb-12" style="box-shadow: 0 0 15px rgba(0,0,0,0.2);">
                <div class="flex justify-center">
                    <img src="{{ asset('images/game/9/false.png') }}" alt="Work Impact" class="w-80 h-48 object-contain">
                </div>
            </div>
        </div>
    </div>

    <div id="info-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 opacity-0">
        <div class="bg-white rounded-2xl shadow-xl p-6 max-w-md w-full mx-4 text-center">
            <h3 class="text-xl font-bold text-indigo-800 mb-4">ผลกระทบของผู้ถูกรังแกทางไซเบอร์</h3>
            
            <div class="mb-4">
                <img src="{{ asset('images/game/9/false.png') }}" alt="Character with emotions" class="w-40 h-32 mx-auto">
            </div>
            
            <div class="text-left space-y-2 mb-6 pr-4 pl-4">
                <p class="text-indigo-800"><span class="font-bold">1.</span> ส่งผลต่อการเรียน เด็กขาดสมาธิในการเรียน ไม่อยากไปโรงเรียน</p>
                <p class="text-indigo-800"><span class="font-bold">2.</span> เผชิญกับภาวะวิตกกังวล หวาดระแวง รู้สึกไม่ปลอดภัย นอนไม่หลับ ภาวะซึมเศร้าและฆ่าตัวตาย</p>
                <p class="text-indigo-800"><span class="font-bold">3.</span> สูญเสียความมั่นใจในตัวเอง</p>
            </div>

            <button id="next-btn" class="bg-[#929AFF] text-white font-medium py-2 px-8 rounded-xl transition-colors hover:bg-[#7B85FF]">
                ถัดไป
            </button>
        </div>
    </div>

    <div id="success-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 opacity-0">
        <div class="bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <img src="{{ asset('images/material/school_girl.png') }}" alt="School Girl Character"
                class="w-32 h-auto mx-auto mb-4 object-cover">
            
            <h3 class="text-2xl font-bold text-indigo-800">เยี่ยมมาก!</h3>
            <p class="text-indigo-800 mb-4 text-lg">คุณตอบได้ถูกต้อง</p>
            
            <p class="text-indigo-800 text-xl font-bold mb-4">เริ่มความท้าทายในเกมต่อไปกัน</p>
            
            <button id="finish-btn" class="bg-[#929AFF] text-white font-medium py-2 px-8 rounded-xl transition-colors hover:bg-[#7B85FF]">
                เริ่ม
            </button>
        </div>
    </div>

    <div id="wrong-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 opacity-0">
        <div class="bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <img src="{{ asset('images/material/school_girl.png') }}" alt="School Girl Character"
                class="w-32 h-auto mx-auto mb-4 object-cover">
            
            <h3 class="text-2xl font-bold text-indigo-800">พยายามต่อไป!</h3>
            <p class="text-indigo-800 mb-6 text-lg">ตัวเลือกของคุณยังไม่ถูกต้อง</p>
            
            <div class="flex gap-6 justify-center">
                <button id="skip-btn" class="bg-gray-400 text-white font-medium py-2 px-6 rounded-xl transition-colors hover:bg-gray-500">
                    ข้าม
                </button>
                <button id="try-again-btn" class="bg-[#929AFF] text-white font-medium py-2 px-6 rounded-xl transition-colors hover:bg-[#7B85FF]">
                    อีกครั้ง
                </button>
            </div>
        </div>
    </div>

    @include('layouts.game.script.8_9_10.index')

    <script>
        window.gameNextRoute = "{{ route('game_10') }}";
    </script>
@endsection