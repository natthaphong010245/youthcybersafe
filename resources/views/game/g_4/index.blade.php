@extends('layouts.game.bullying.index')

@php
    $backUrl = '/category/game';
    $mainUrl = '/main';
@endphp

@section('content')
    @if(isset($showIntroModal) && $showIntroModal)
        @include('game.intro', [
            'title' => 'ความรู้เกี่ยวกับพฤติกรรมการรังแกกัน',
            'gameNumber' => '4',
            'description' => 'จับคู่รูปภาพกับข้อความที่เกี่ยวกับการรังแกทางไซเบอร์'
        ])
    @endif

    <div class="card-container space-y-6 px-6 md:px-0 {{ isset($showIntroModal) && $showIntroModal ? 'game-blur' : '' }}" id="game-content">
        <div class="text-center">
            <h2 class="text-xl font-bold text-indigo-800 ">จับคู่รูปภาพกับข้อความที่เกี่ยวกับการรังแกทางไซเบอร์</h2>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-2 gap-10 mb-8">
                <div class="text-option bg-[#5B21B6] text-white rounded-xl p-6 cursor-pointer transition-all duration-300 relative text-center font-bold text-xl h-28 flex items-center justify-center"
                    data-text="1">
                    การปะทะคารม
                </div>
                <div class="image-option border-2 rounded-xl p-2 border-gray-300 bg-white cursor-pointer transition-all duration-300 relative h-28"
                    data-image="1">
                    <div class="flex flex-col items-center justify-center h-full">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/game/4/disturbance.jpg') }}" alt="การก่อกวน" class="h-24 object-contain">
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-10 mb-8">
                <div class="text-option bg-[#5B21B6] text-white rounded-xl p-6 cursor-pointer transition-all duration-300 relative text-center font-bold text-xl h-28 flex items-center justify-center"
                    data-text="2">
                    การก่อกวน
                </div>
                <div class="image-option border-2 rounded-xl p-2 border-gray-300 bg-white cursor-pointer transition-all duration-300 relative h-28"
                    data-image="2">
                    <div class="flex flex-col items-center justify-center h-full">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/game/4/clash.jpg') }}" alt="การปะทะคารม"
                                class="h-24 object-contain">
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-10 mb-8">
                <div class="text-option bg-[#5B21B6] text-white rounded-xl p-6 cursor-pointer transition-all duration-300 relative text-center font-bold text-xl h-28 flex items-center justify-center"
                    data-text="3">
                    การใส่ร้ายป้ายสี
                </div>
                <div class="image-option border-2 rounded-xl p-2 border-gray-300 bg-white cursor-pointer transition-all duration-300 relative h-28"
                    data-image="3">
                    <div class="flex flex-col items-center justify-center h-full">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/game/4/Impersonate.png') }}" alt="รวมรอบ เป็นคนซัน"
                                class="h-24 object-contain text-center">
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-10 mb-8">
                <div class="text-option bg-[#5B21B6] text-white rounded-xl p-6 cursor-pointer transition-all duration-300 relative text-center font-bold text-xl h-28 flex items-center justify-center"
                    data-text="4">
                    สวมรอยเป็นคนอื่น
                </div>
                <div class="image-option border-2 rounded-xl p-4 border-gray-300 bg-white cursor-pointer transition-all duration-300 relative h-28"
                    data-image="4">
                    <div class="flex flex-col items-center justify-center h-full">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/game/4/slander.png') }}" alt="สวมรอยเป็นคนอื่น"
                                class="h-24 object-contain">
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($showFifthPair) && $showFifthPair)
            <div class="grid grid-cols-2 gap-10 mb-6">
                <div class="text-option bg-[#5B21B6] text-white rounded-xl p-6 cursor-pointer transition-all duration-300 relative text-center font-bold text-xl h-28 flex items-center justify-center"
                    data-text="5">
                    การข่มเหงทางออนไลน์
                </div>
                <div class="image-option border-2 rounded-xl p-4 border-gray-300 bg-white cursor-pointer transition-all duration-300 relative h-28"
                    data-image="5">
                    <div class="flex flex-col items-center justify-center h-full">
                        <div class="flex items-center justify-center mb-2">
                            <img src="{{ asset('images/game/4/cyberbully.png') }}" alt="การข่มเหงทางออนไลน์"
                                class="h-16 object-contain">
                        </div>
                        <div class="text-center text-gray-700 font-bold text-sm">การข่มเหงทางออนไลน์</div>
                    </div>
                </div>
            </div>
            @else
            <div class="mb-6"></div>
            @endif

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
                <img src="{{ asset('images/material/school_girl.png') }}" alt="Character" class="w-32 h-auto mx-auto mb-4">
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
        $defaultPairs = 4;
        $defaultMatches = ['1' => '2', '2' => '1', '3' => '4', '4' => '3'];
        $defaultNextRoute = route('main');
        $defaultShowIntroModal = false;
    @endphp
    
    <script>
        window.gamePairs = @json($totalPairs ?? $defaultPairs);
        window.gameMatches = @json($correctMatches ?? $defaultMatches);
        window.gameNextRoute = @json($nextRoute ?? $defaultNextRoute);
        window.gameHasIntroModal = @json($showIntroModal ?? $defaultShowIntroModal);
    </script>
@endsection