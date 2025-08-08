@extends('layouts.game.bullying.index')

@php
    $backUrl = '/category/game';
    $mainUrl = '/main';
@endphp

@section('content')
    <div class="card-container space-y-6 px-6 md:px-0" id="game-content">
        <div class="text-center">
            <h2 class="text-xl font-bold text-indigo-800 ">จับคู่รูปภาพกับข้อความที่เกี่ยวกับการรังแกทางไซเบอร์ขั้นสูง</h2>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-2 gap-10 mb-8">
                <div class="text-option bg-[#5B21B6] text-white rounded-xl p-2 cursor-pointer transition-all duration-300 relative text-center font-bold text-lg h-28 flex items-center justify-center"
                    data-text="1">
                    เผยแพร่ข้อมูลส่วนตัวของผู้อื่น
                </div>
                <div class="image-option border-2 rounded-xl p-2 border-gray-300 bg-white cursor-pointer transition-all duration-300 relative h-28"
                    data-image="1">
                    <div class="flex flex-col items-center justify-center h-full">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/game/4/monitoring.jpg') }}" alt="เผยแพร่ข้อมูลส่วนตัว" class="h-24 object-contain">
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-10 mb-8">
                <div class="text-option bg-[#5B21B6] text-white rounded-xl p-2 cursor-pointer transition-all duration-300 relative text-center font-bold text-lg h-28 flex items-center justify-center"
                    data-text="2">
                    การลบหรือบล็อกผู้อื่นออกจากกลุ่ม
                </div>
                <div class="image-option border-2 rounded-xl p-2 border-gray-300 bg-white cursor-pointer transition-all duration-300 relative h-28"
                    data-image="2">
                    <div class="flex flex-col items-center justify-center h-full">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/game/4/disseminate.jpg') }}" alt="บล็อกจากกลุ่ม"
                                class="h-24 object-contain">
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-10 mb-8">
                <div class="text-option bg-[#5B21B6] text-white rounded-xl p-2 cursor-pointer transition-all duration-300 relative text-center font-bold text-lg h-28 flex items-center justify-center"
                    data-text="3">
                    เฝ้าติดตามทางอินเตอร์เน็ต
                </div>
                <div class="image-option border-2 rounded-xl p-2 border-gray-300 bg-white cursor-pointer transition-all duration-300 relative h-28"
                    data-image="3">
                    <div class="flex flex-col items-center justify-center h-full">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/game/4/sexually.jpg') }}" alt="เฝ้าติดตาม"
                                class="h-24 object-contain text-center">
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-10 mb-8">
                <div class="text-option bg-[#5B21B6] text-white rounded-xl p-2 cursor-pointer transition-all duration-300 relative text-center font-bold text-lg h-28 flex items-center justify-center"
                    data-text="4">
                    ถ่ายคลิปวีดิโอและเผยแพร่บนอินเตอร์เน็ต
                </div>
                <div class="image-option border-2 rounded-xl p-2 border-gray-300 bg-white cursor-pointer transition-all duration-300 relative h-28"
                    data-image="4">
                    <div class="flex flex-col items-center justify-center h-full">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/game/4/removing_blocking.jpg') }}" alt="ถ่ายและเผยแพร่วีดิโอ"
                                class="h-24 object-contain">
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-10 mb-6">
                <div class="text-option bg-[#5B21B6] text-white rounded-xl p-2 cursor-pointer transition-all duration-300 relative text-center font-bold text-lg h-28 flex items-center justify-center"
                    data-text="5">
                    ส่งต่อภาพหรือวีดิโอที่ล่อแหลมทางเพศ
                </div>
                <div class="image-option border-2 rounded-xl p-2 border-gray-300 bg-white cursor-pointer transition-all duration-300 relative h-28"
                    data-image="5">
                    <div class="flex flex-col items-center justify-center h-full">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/game/4/video_clips.jpg') }}" alt="เผยแพร่เนื้อหาทางเพศ"
                                class="h-24 object-contain">
                        </div>
                    </div>
                </div>
            </div>

            <div class="progress-container pl-2 pr-2 mt-8 mb-2">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progress-bar"
                        class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all duration-500 ease-out"
                        style="width: 0%"></div>
                </div>
                <div class="flex justify-between items-center mt-2 pl-2 pr-2">
                    <span class="text-center text-gray-600 text-sm block">ความคืบหน้า</span>
                    <span id="progress-percentage" class="text-gray-600 text-sm">0%</span>
                </div>
            </div>
        </div>
    </div>

    <div id="complete-overlay"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 opacity-0">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <div class="mb-2">
                <img src="{{ asset('images/material/school_man.png') }}" alt="Character" class="w-32 h-auto mx-auto mb-4">
                  <h3 class="text-xl font-bold text-indigo-800">เยี่ยมมาก!</h3>
            <p class="text-lg text-indigo-800 mb-4">คำตอบของคุณถูกต้อง</p>
            <p class="text-indigo-800 text-lg mb-1">เริ่มความท้าทายในเกมถัดไปกันเลย</p>
            </div>
            <button id="finish-game-btn"
                class="bg-[#929AFF] text-white font-medium text-md py-1 px-6 rounded-lg transition-colors ">
                เริ่ม
            </button>
        </div>
    </div>

    @include('layouts.game.script.4.index')

    @php
        $defaultPairs = 5;
        $defaultMatches = ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'];
        $defaultNextRoute = route('game_5_1');
    @endphp
    
    <script>
        window.gamePairs = @json($totalPairs ?? $defaultPairs);
        window.gameMatches = @json($correctMatches ?? $defaultMatches);
        window.gameNextRoute = @json($nextRoute ?? $defaultNextRoute);
    </script>
@endsection