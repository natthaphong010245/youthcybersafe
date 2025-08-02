@extends('layouts.game.bullying.index')

@php
    $backUrl = '/category/game';
    $mainUrl = '/main';
@endphp

@section('next-route', $nextRoute)

@section('content')
    @if ($showIntroModal)
        @include('game.intro', [
            'title' => 'ความรู้เกี่ยวกับพฤติกรรมการรังแกกัน',
            'gameNumber' => '1',
            'description' => 'พฤติกรรมแบบนี้เป็นการรังแกรูปแบบไหนกัน',
            'descriptionClass' => 'pr-2 pl-2',
            'actionText' => 'เริ่มความท้าทายกันเลย'
        ])
    @endif

    <div class="card-container space-y-6 px-6 md:px-0 mb-6" id="game-content">
        <div class="text-center mb-2">
            <h2 class="text-xl font-bold text-indigo-800 pr-4 pl-4">พฤติกรรมแบบนี้เป็นการรังแกรูปแบบไหนกัน</h2>
        </div>

        <div class="flex justify-center mb-8">
            <div class="bg-white rounded-2xl shadow-lg">
                <div class="rounded-lg overflow-hidden">
                    <img src="{{ asset('images/game/1/' . $scenarioImage) }}" alt="{{ $altText }}"
                        class="w-full h-52 object-cover rounded-lg">
                </div>
            </div>
        </div>

        <div class="mb-2">
            <div class="flex items-center justify-center mb-4 mt-2">
                <div class="flex-1 h-px bg-gray-400 max-w-28"></div>
                <h3 class="text-center text-gray-500 text-lg mx-4">ตัวเลือก</h3>
                <div class="flex-1 h-px bg-gray-400 max-w-28"></div>
            </div>
            <div class="grid grid-cols-2 gap-3 max-w-xs mx-auto" id="answer-options">
                @foreach ($answerOptions as $option)
                    <div class="answer-option cursor-pointer" data-answer="{{ $option['value'] }}"
                        data-correct="{{ $option['correct'] ? 'true' : 'false' }}">
                        <div
                            class="bg-indigo-500 text-white rounded-xl p-4 text-center font-medium transition-all hover:bg-indigo-600 hover:scale-105 h-20 flex flex-col justify-center">
                            <div class="text-lg">การรังแกทาง</div>
                            <div class="text-xl">{{ $option['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div id="success-modal"
        class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30">
        <div class="modal-content bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <img src="{{ asset('images/material/school_girl.png') }}" alt="Happy Student"
                class="w-32 h-auto rounded-full mx-auto mb-4 object-cover">
            <h3 class="text-xl font-bold text-indigo-800">เยี่ยมมาก!</h3>
            <p class="text-lg text-indigo-800 mb-4">คำตอบของคุณถูกต้อง</p>
            <p class="text-indigo-800 text-lg mb-1">เริ่มความท้าทายในเกมถัดไปกันเลย</p>
            <button id="success-btn"
                class="bg-[#929AFF] text-white font-medium text-md py-1 px-6 rounded-lg transition-colors ">
                เริ่ม
            </button>
        </div>
    </div>

    <div id="failure-modal"
        class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30">
        <div class="modal-content bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <img src="{{ asset('images/material/school_girl_false.png') }}" alt="School Girl Character"
                class="w-32 h-auto mx-auto mb-4 object-cover">
            <h3 class="text-xl font-bold text-indigo-800">พยายามต่อไป!</h3>
            <p class="text-lg text-indigo-800 mb-4">ตัวเลือกของคุณยังไม่ถูกต้อง</p>

            <div class="flex space-x-8 justify-center">
                <button id="skip-btn"
                    class="bg-gray-400 text-white font-medium text-md py-1 px-4 rounded-lg transition-colors hover:bg-gray-500">
                    ข้าม
                </button>
                <button id="try-again-btn"
                    class="bg-[#929AFF] text-white font-medium text-md py-1 px-4 rounded-lg transition-colors hover:bg-[#7B85FF]">
                    อีกครั้ง
                </button>
            </div>
        </div>
    </div>

    <div id="answer-reveal-modal"
        class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30">
        <div class="modal-content bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <h3 class="text-xl font-bold text-indigo-800 mb-4">ตัวเลือกที่ถูก</h3>
            
            <div class="mb-4">
                <img src="{{ asset('images/game/1/' . $scenarioImage) }}" alt="{{ $altText }}"
                    class="w-full h-auto object-cover rounded-lg mb-2">
            </div>

            <p class="text-indigo-800 text-xl font-bold mb-4">{{ $correctAnswerText }}</p>
            <p class="text-indigo-800 text-lg font-medium mb-2">เริ่มความท้าทายเกมถัดไปกันเลย</p>
            
            <button id="continue-btn"
                class="bg-[#929AFF] text-white font-medium text-md py-1 px-6 rounded-lg transition-colors">
                เริ่ม
            </button>
        </div>
    </div>

    @include('layouts.game.script.1.index')
@endsection