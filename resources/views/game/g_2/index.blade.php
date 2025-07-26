{{-- resources/views/game/g_2/index.blade.php --}}
@extends('layouts.game.bullying.index')

@php
    $backUrl = '/category/game';
    $mainUrl = '/main';
@endphp

@section('content')
    @include('game.intro', [
        'title' => 'ความรู้เกี่ยวกับพฤติกรรมการรังแกกัน',
        'gameNumber' => '2',
        'description' => '"การรังแกกันผ่านโลกไซเบอร์" หรือว่า "ไซเบอร์บูลลี่" เป็นตัวเลือกกันน้า',
        'descriptionClass' => 'pr-4 pl-4',
    ])

    <div class="card-container space-y-6 px-8 md:px-0" id="game-content">
        <div class="text-center">
            <h2 class="text-xl ml-2 mr-2 font-bold text-indigo-800">"การรังแกกันผ่านโลกไซเบอร์" หรือว่า "ไซเบอร์บลูลี่"
                เป็นตัวเลือกกันน้า</h2>
            <p class="text-lg text-indigo-700 mt-2">คลิกเลือกตัวเลือกที่ถูกต้อง</p>
        </div>

        <div class="flex justify-center">
            <div class="w-20 h-20 sun-container">
                <img src="{{ asset('images/material/sun.png') }}" alt="Sun" class="w-full h-full object-contain">
            </div>
        </div>

        <div id="answer-clouds-container" class="relative min-h-80 mb-2">
        </div>

        <div class="text-center mb-2">
            <p class="text-sm text-gray-500">คำตอบ</p>
        </div>
        <div class="flex justify-center space-x-1 mb-6" id="drop-zones-container">
            <div class="drop-zone w-36 h-24 flex items-center justify-center relative" data-zone="0">
                <img src="{{ asset('images/material/cloud.png') }}" alt="Empty Cloud" class="w-32 h-22 opacity-30">
            </div>
            <div class="drop-zone w-36 h-24 flex items-center justify-center relative" data-zone="1">
                <img src="{{ asset('images/material/cloud.png') }}" alt="Empty Cloud" class="w-32 h-22 opacity-30">
            </div>
            <div class="drop-zone w-36 h-24 flex items-center justify-center relative" data-zone="2">
                <img src="{{ asset('images/material/cloud.png') }}" alt="Empty Cloud" class="w-32 h-22 opacity-30">
            </div>
        </div>
    </div>

    <div id="correct-overlay"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 opacity-0">
        <div class="bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <div class="mb-4">
                <img src="{{ asset('images/material/school_girl.png') }}" alt="Character" class="w-32 h-auto mx-auto mb-4">
                <h3 class="text-xl font-bold text-indigo-800">เยี่ยมมาก!</h3>
                 <p class="text-lg text-indigo-800 mb-4">คำตอบของคุณถูกต้อง</p>
            </div>
            <button id="continue-correct-btn"
                class="bg-[#929AFF] text-white font-medium text-md py-1 px-6 rounded-lg transition-colors ">
                ถัดไป
            </button>
        </div>
    </div>

    <div id="wrong-overlay"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 opacity-0">
        <div class="bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <div class="mb-6">
                <img src="{{ asset('images/material/school_girl_false.png') }}" alt="Character" class="w-32 h-auto mx-auto mb-4">
                <h3 class="text-xl font-bold text-indigo-800">พยายามต่อไป!</h3>
                <p class="text-lg text-indigo-800 mb-4">ตัวเลือกของคุณยังไม่ถูกต้อง</p>
            </div>
            <div class="flex space-x-6 justify-center">
                <button id="skip-btn"
                    class="bg-gray-400 text-white font-medium text-md py-1 px-4 rounded-lg transition-colors">
                    ข้าม
                </button>
                <button id="try-again-wrong-btn"
                    class="bg-[#929AFF] text-white font-medium text-md py-1 px-4 rounded-lg transition-colors">
                    อีกครั้ง
                </button>
            </div>
        </div>
    </div>

    <div id="complete-overlay"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 opacity-0">
        <div class="bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <div class="mb-6">
                <img src="{{ asset('images/material/school_girl.png') }}" alt="Character" class="w-32 h-auto mx-auto mb-4">
                <h3 class="text-xl font-bold text-indigo-800">เยี่ยมมาก!</h3>
            <p class="text-lg text-indigo-800 mb-4">คำตอบของคุณถูกต้อง</p>
            </div>
            <p class="text-indigo-800 text-lg mb-1">เริ่มความท้าทายในเกมถัดไปกันเลย</p>
            <button id="finish-game-btn"
                class="bg-[#929AFF] text-white font-medium text-md py-1 px-6 rounded-lg transition-colors ">
                เริ่ม
            </button>
        </div>
    </div>
    @include('layouts.game.script.2.index')
@endsection