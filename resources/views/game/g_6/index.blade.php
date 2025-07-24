@extends('layouts.game.bullying.index')

@php
    $backUrl = '/category/game';
    $mainUrl = '/main';
@endphp

@section('content')
    @include('game.intro', [
        'title' => 'ความรู้เกี่ยวกับพฤติกรรมการรังแกกัน',
        'gameNumber' => '6',
        'description' => 'พบปัญหา สิ่งที่เขาได้ยินได้ทุกข์ทรมาน หรือกลั่นแกล้งบนโลกออนไลน์ที่ผ่านมาได้เลย',
        'actionText' => 'เริ่มความท้าทายกันเลย'
    ])

    <div class="bg-white min-h-0" id="game-content">
        <div class="card-container space-y-2 px-4 py-2 pb-4">
            <div class="text-center mb-6">
                <h2 class="text-xl sm:text-xl font-bold text-indigo-800 leading-tight px-4 pr-2 pl-2">
                    พบปัญหา สิ่งที่เขาได้ยินได้ทุกข์ทรมาน หรือกลั่นแกล้งบนโลกออนไลน์ที่ผ่านมาได้เลย
                </h2>
            </div>

            <div class="flex flex-col items-center space-y-2">
                <div class="relative w-80 h-80 sm:w-96 sm:h-96">
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/game/6/stop_cyberbullying.png') }}" alt="Character"
                                class="w-28 h-28 sm:w-20 sm:h-20 object-contain">
                        </div>
                    </div>

                    <div id="messages-container" class="absolute inset-0">
                    </div>
                </div>

                <div class="w-full max-w-lg mr-4 ml-4 mt-8">
                    <div class="relative flex items-center border-2 border-gray-300 rounded-2xl p-2 mb-3">
                        <input type="text" id="message-input" placeholder="CYBERBULLYING"
                            class="flex-1 px-6 py-2 bg-transparent outline-none focus:outline-none text-gray-700 placeholder-gray-400 text-lg"
                            maxlength="20">
                        <button id="add-message-btn" class="bg-[#5F58C9] text-white p-3 rounded-full transition-colors  ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex justify-end mt-8 mr-2">
                        <button id="next-btn"
                            class="bg-[#929AFF] text-white font-medium py-3 px-8 rounded-2xl transition-colors hover:bg-[#7B85FF] disabled:bg-gray-300 disabled:cursor-not-allowed shadow-lg"
                            disabled>
                            ถัดไป
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.game.script.6.index')
@endsection
