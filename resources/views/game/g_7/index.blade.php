@extends('layouts.game.causes_bullying.index')

@php
    $backUrl = '/category/game';
    $mainUrl = '/main';
@endphp

@section('content')
    @include('game.intro', [
        'title' => 'สาเหตุของการกลั่นแกล้งบนโลกออนไลน์',
        'gameNumber' => '7',
        'description' => 'อยากรู้ไหมว่า สาเหตุของการกลั่นแกล้งบนโลกออนไลน์ เกิดจากอะไร.....'
    ])

    <div class="card-container space-y-6 px-6 md:px-0" id="game-content">
        <div class="text-center mb-8">
            <h2 class="text-xl font-bold text-indigo-800">อยากรู้ไหมว่า สาเหตุของการกลั่นแกล้ง บนโลกออนไลน์ เกิดจากอะไร...</h2>
        </div>
        
        <div class="relative min-h-96 flex items-center justify-center" id="mystery-container">
        </div>
    </div>

    <div id="wrong-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 opacity-0">
        <div class="bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <img src="{{ asset('images/material/school_girl_false.png') }}" alt="School Girl Character"
                class="w-32 h-auto mx-auto mb-4 object-cover">
            
            <h3 class="text-xl font-bold text-indigo-800">พยายามต่อไป!</h3>
            <p class="text-lg text-indigo-800 mb-4">ตัวเลือกของคุณยังไม่ถูกต้อง</p>
            
            <div class="flex gap-6 justify-center">
                <button id="skip-btn" class="bg-gray-400 text-white font-medium text-md py-1 px-4 rounded-lg transition-colors hover:bg-gray-500">
                    ข้าม
                </button>
                <button id="try-again-btn" class="bg-[#929AFF] text-white font-medium text-md py-1 px-4 rounded-lg transition-colors hover:bg-[#7B85FF]">
                    อีกครั้ง
                </button>
            </div>
        </div>
    </div>

    <div id="info-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 opacity-0">
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full mx-4 text-center">
            <div class="mb-4">
                <img src="{{ asset('images/game/7/mystery_box.png') }}" alt="Mystery Box Open" 
                     class="w-32 h-32 mx-auto mb-4 object-contain">
            </div>
            
            <h3 class="text-xl font-bold text-indigo-800 mb-4">การกลั่นแกล้งบนโลกออนไลน์ เกิดจากอะไร...</h3>
            
            <div class="text-indigo-800 text-base leading-relaxed mb-6 text-left">
                <p class="mb-2 indent-6">
                    สาเหตุของการกลั่นแกล้งบนโลกออนไลน์ ไม่สามารถเกิดได้หลากหลาย แต่ส่วนมากเกิดขึ้นจากความเกลียดชังและจงใจที่จะล้อเลียน 
                    เพื่อทำให้เป้าหมายเกิดความอับอายบนโลกไซเบอร์ มีทั้งทำครั้งเดียวเพื่อความสะใจแล้วหายไปและการจงใจตามคุกคาม
                    เป็นระยะเวลานานๆ แบบล็อคเป้าหมายจนกว่าจะพอใจ
                </p>
            </div>
            
            <button id="next-btn" class="bg-[#929AFF] text-white font-medium py-2 px-6 rounded-xl">
                ถัดไป
            </button>
        </div>
    </div>

    <div id="success-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 opacity-0">
        <div class="bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <img src="{{ asset('images/material/school_girl.png') }}" alt="School Girl Character"
                class="w-32 h-auto mx-auto mb-4 object-cover">
            
            <h3 class="text-xl font-bold text-indigo-800">เยี่ยมมาก!</h3>
            <p class="text-lg text-indigo-800 mb-4">คำตอบของคุณถูกต้อง</p>
            <p class="text-indigo-800 text-lg mb-1">เริ่มความท้าทายในเกมถัดไปกันเลย</p>
            
            <button id="finish-btn"  class="bg-[#929AFF] text-white font-medium text-md py-1 px-6 rounded-lg transition-colors ">
                เริ่ม
            </button>
        </div>
    </div>

    @include('layouts.game.script.7.index')

@endsection