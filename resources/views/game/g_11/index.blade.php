@extends('layouts.game.dealing_bullying.index')

@php
    $backUrl = '/category/game';
    $mainUrl = '/main';
@endphp

@section('content')
    @include('game.intro', [
        'title' => 'การรับมือการกลั่นแกล้งบนโลกออนไลน์',
        'gameNumber' => '11',
        'description' => 'สัญญาณเตือนภัยกับการรังแกทางไซเบอร์'
    ])

    <div class="card-container space-y-6 px-6 md:px-0" id="game-content">
        <div class="text-center">
            <h2 class="text-xl font-bold text-indigo-800">สัญญาณเตือนภัยกับการรังแกทางไซเบอร์</h2>
        </div>

        <div class="flex items-center text-gray-500 text-sm mb-4">
            <div class="flex-1 border-b border-gray-200"></div>
            <span class="px-4">เลือกเหตุการณ์ เพื่ออ่านข้อมูล</span>
            <div class="flex-1 border-b border-gray-200"></div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="card-hover bg-white border-2 border-gray-300 rounded-2xl p-2 text-center"
                onclick="showSignalDetails(1)">
                <div class="flex justify-center mb-4 mt-2">
                    <img src="{{ asset('images/game/11/warning_signal.png') }}" alt="สัญญาณเตือน"
                        class="w-16 h-16 object-contain pulse-animation">
                </div>
                <div class="text-indigo-800 font-bold text-sm">สัญญาณเตือน</div>
                <div class="text-indigo-800 text-2xl font-bold">1</div>
            </div>

            <div class="card-hover bg-white border-2 border-gray-300 rounded-2xl p-2 text-center"
                onclick="showSignalDetails(2)">
                <div class="flex justify-center mb-4 mt-2">
                    <img src="{{ asset('images/game/11/warning_signal.png') }}" alt="สัญญาณเตือน"
                        class="w-16 h-16 object-contain pulse-animation">
                </div>
                <div class="text-indigo-800 font-bold text-sm">สัญญาณเตือน</div>
                <div class="text-indigo-800 text-2xl font-bold">2</div>
            </div>
        </div>

        <div class="flex items-center text-gray-500 text-sm mb-2 mt-8">
            <div class="flex-1 border-b border-gray-200"></div>
            <span class="px-4">ตัวเลือก</span>
            <div class="flex-1 border-b border-gray-200"></div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <button class="bg-indigo-800 text-white rounded-xl py-6 text-lg card-hover" onclick="selectAnswer(1)">
                สัญญาณเตือน<br>
                <h class="text-3xl font-bold">1</h>
            </button>
            <button class="bg-indigo-800 text-white rounded-xl py-6 text-lg card-hover" onclick="selectAnswer(2)">
                สัญญาณเตือน<br>
                <h class="text-3xl font-bold">2</h>
            </button>
        </div>
    </div>

    <div id="signal-modal"
        class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop hidden items-center justify-center z-50">
        <div class="modal-content bg-white rounded-3xl shadow-2xl p-8 max-w-sm mx-4 text-center">
            <h2 class="text-xl font-bold text-indigo-800 mb-4">
                สัญญาณเตือนภัยกับงบนอกถังผู้ถูกกลั่นแกล้งทางไซเบอร์
            </h2>

            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/game/11/warning_signal.png') }}" alt="สัญญาณเตือน"
                    class="w-20 h-20 object-contain pulse-animation">
            </div>
            <div class="text-indigo-800 font-bold text-xl mb-4 text-center">
                <div class="flex items-center mb-2">
                    <div class="flex-1 border-b-2 border-indigo-700"></div>
                    <span class="px-4 text-sm">สัญญาณเตือน</span>
                    <div class="flex-1 border-b-2 border-indigo-700"></div>
                </div>
                <div class="text-3xl" id="signal-number">1</div>
            </div>

            <div id="signal-content" class="text-indigo-800 text-lg mb-6 leading-relaxed text-left pr-1 pl-1">
            </div>

            <button onclick="closeSignalModal()"
                class="bg-[#929AFF] text-white px-6 py-1 rounded-xl font-medium  transition-colors">
                ปิด
            </button>
        </div>
    </div>

    <div id="success-modal"
        class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop hidden items-center justify-center z-50">
        <div class="modal-content bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/material/school_girl.png') }}" alt="Character"
                    class="w-40 h-auto object-contain">
            </div>

           <h3 class="text-xl font-bold text-indigo-800">เยี่ยมมาก!</h3>
            <p class="text-lg text-indigo-800 mb-4">คำตอบของคุณถูกต้อง</p>
            <p class="text-indigo-800 text-lg mb-1">เริ่มความท้าทายในเกมถัดไปกันเลย</p>

            <button onclick="goToNextGame()" class="bg-[#929AFF] text-white font-medium text-md py-1 px-6 rounded-lg transition-colors ">
                เริ่ม
            </button>
        </div>
    </div>

    <div id="retry-modal"
        class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop hidden items-center justify-center z-50">
        <div class="modal-content bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/material/school_girl_false.png') }}" alt="Character"
                    class="w-40 h-auto object-contain">
            </div>

             <h3 class="text-xl font-bold text-indigo-800">พยายามต่อไป!</h3>
            <p class="text-lg text-indigo-800 mb-4">ตัวเลือกของคุณยังไม่ถูกต้อง</p>

            <div class="flex gap-8 justify-center">
                <button onclick="skipGame()" class="bg-gray-400 text-white font-medium text-md py-1 px-4 rounded-lg transition-colors ">
                    ข้าม
                </button>
                <button onclick="tryAgain()" class="bg-[#929AFF] text-white font-medium text-md py-1 px-4 rounded-lg transition-colors ">
                    อีกครั้ง
                </button>
            </div>
        </div>
    </div>

    @include('layouts.game.script.11.index')
@endsection