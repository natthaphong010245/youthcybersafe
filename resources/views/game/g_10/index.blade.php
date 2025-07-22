@extends('layouts.game.causes_bullying.index')

@php
    $backUrl = '/category/game';
    $mainUrl = '/main';
@endphp

@section('content')
    @include('game.intro', [
        'title' => 'สาเหตุของการกลั่นแกล้งบนโลกออนไลน์',
        'gameNumber' => '10',
        'description' => 'น้องๆ คิดว่าการ CYBERBULLYING ผิดกฎหมายหรือไม่',
        'actionText' => 'เริ่มความก้าวหน้ากันเลย'
    ])

    <div class="card-container space-y-6 px-6 md:px-0" id="game-content">
        <div class="text-center mb-2">
            <h2 class="text-xl font-bold text-indigo-800 pl-4 pr-4">น้องๆ คิดว่าการ CYBERBULLYING ผิดกฎหมายหรือไม่</h2>
        </div>

        <div class="space-y-4 max-w-md mx-auto">
            <button id="illegal-btn"
                class="w-full bg-[#929AFF] text-white font-medium py-2 px-3 rounded-xl transition-all hover:bg-[#7B85FF] hover:transform hover:translateY(-1px) text-lg shadow-lg hover:shadow-xl mb-2">
                ผิดกฎหมาย
            </button>

            <button id="legal-btn"
                class="w-full bg-[#929AFF] text-white font-medium py-2 px-3 rounded-xl transition-all hover:bg-[#7B85FF] hover:transform hover:translateY(-1px) text-lg shadow-lg hover:shadow-xl">
                ไม่ผิดกฎหมาย
            </button>
        </div>

        <div class="flex justify-center mt-10">
            <div class="bg-white rounded-xl p-6 max-w-sm w-full ml-6 mr-6" style="box-shadow: 0 0 15px rgba(0,0,0,0.1);">
                <img src="{{ asset('images/game/10/law.png') }}" alt="Cyberbullying Illustration"
                    class="w-full h-auto object-contain">
            </div>
        </div>
    </div>

    <div id="info-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 opacity-0">
        <div class="bg-white rounded-2xl shadow-xl p-6 max-w-md w-full mx-4 text-center">
            <div class="flex items-center mb-2">
                <div class="flex-1 border-b-2 border-indigo-700"></div>
                <span class="px-4 text-indigo-800 font-bold text-lg">ข้อควรระวัง</span>
                <div class="flex-1 border-b-2 border-indigo-700"></div>
            </div>

            <div class="text-center mb-10 relative">
                <div class="flex items-center justify-center">
                    <div class="relative">
                        <h1 class="text-2xl font-bold text-indigo-800 inline-block">CYBERBULLYING</h1>
                        <p class="text-xl text-indigo-800 absolute -bottom-6 right-0">ผิดกฎหมาย</p>
                    </div>
                </div>
            </div>

            <div class="text-left text-lg text-indigo-800 leading-relaxed space-y-2 pr-2 pl-2 mb-6">
                <p class="indent-8"><span class="font-medium">พ.ร.บ.</span> คอมพิวเตอร์ <span class="font-medium">มาตรา 14</span>
                    กรณีโพสต์ข้อมูลที่บิดเบือน หรือปลอมแปลง ไม่ว่าจะทั้งหมดหรือบางส่วน หรือข้อมูลที่เป็นเท็จ
                    ซึ่งคนอื่นสามารถเข้าไปดูข้อมูลนั้นได้ ทำให้ผู้อื่นเสียหาย รวมทั้งข้อมูลลามกต่างๆ
                    ทั้งผู้โพสต์และผู้เผยแพร่ส่งต่อ จะมีความผิดต้องระวางโทษจำคุกไม่เกิน <span class="font-medium">5
                        ปี</span>
                    หรือปรับไม่เกิน <span class="font-medium">100,000 บาท</span> หรือทั้งจำทั้งปรับ</p>
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
        window.gameNextRoute = "{{ route('game_11') }}";
    </script>
@endsection