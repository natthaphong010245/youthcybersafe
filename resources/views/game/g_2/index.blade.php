@extends('layouts.game.bullying.index')

@php
    $backUrl = '/category/game';
    $mainUrl = '/main';
    $nextRoute = '/game/3';
@endphp

@section('next-route', $nextRoute)

@section('content')
    @if (true) 
        @include('game.intro', [
            'title' => 'ความรู้เกี่ยวกับพฤติกรรมการรังแกกัน',
            'gameNumber' => '2',
            'description' => '“การรังแกกันผ่านโลกไซเบอร์” หรือว่า “ไซเบอร์บลูลี่” เป็นตัวเลือกกันน้า',
            'descriptionClass' => 'pr-2 pl-2',
            'actionText' => 'เริ่มความท้าทายกันเลย'
        ])
    @endif

    <div class="card-container space-y-6 px-6 md:px-0 mb-6" id="game-content">
        <div class="text-center mb-2">
            <h2 class="text-xl font-bold text-indigo-800 pr-4 pl-4">“การรังแกกันผ่านโลกไซเบอร์” หรือว่า “ไซเบอร์บลูลี่” เป็นตัวเลือกกันน้า</h2>
        </div>

        <div class="relative min-h-[280px] mb-8">
            <div class="cloud-options grid grid-cols-2 gap-3 max-w-xs mx-auto mb-8">
                <div class="cloud-option relative cursor-pointer transition-all duration-300 hover:scale-105" 
                     data-text="เจตนา" data-correct="true">
                    <img src="{{ asset('images/game/2/intent.png') }}" alt="เจตนา" class="w-full h-auto">
                </div>
                
                <div class="cloud-option relative cursor-pointer transition-all duration-300 hover:scale-105" 
                     data-text="จงใจทำซ้ำ" data-correct="true">
                    <img src="{{ asset('images/game/2/intentionally_repetitive.png') }}" alt="จงใจทำซ้ำ" class="w-full h-auto">
                </div>
                
                <div class="cloud-option relative cursor-pointer transition-all duration-300 hover:scale-105" 
                     data-text="จ๊ะจ๋า" data-correct="false">
                    <img src="{{ asset('images/game/2/jaja.png') }}" alt="จ๊ะจ๋า" class="w-full h-auto">
                </div>
                
                <div class="cloud-option relative cursor-pointer transition-all duration-300 hover:scale-105" 
                     data-text="จิ๊จ๊ะ" data-correct="false">
                    <img src="{{ asset('images/game/2/jijja.png') }}" alt="จิ๊จ๊ะ" class="w-full h-auto">
                </div>
                
                <div class="cloud-option relative cursor-pointer transition-all duration-300 hover:scale-105 col-span-2 max-w-[130px] mx-auto" 
                     data-text="เจ็บปวด" data-correct="true">
                    <img src="{{ asset('images/game/2/painful.png') }}" alt="เจ็บปวด" class="w-full h-auto">
                </div>
            </div>
        </div>

        <div class="mb-2">
            <div class="flex items-center justify-center mb-4 mt-2">
                <div class="flex-1 h-px bg-gray-400 max-w-28"></div>
                <h3 class="text-center text-gray-500 text-lg mx-4">คำตอบ</h3>
                <div class="flex-1 h-px bg-gray-400 max-w-28"></div>
            </div>
            
            <div class="answer-slots flex justify-center gap-3 max-w-sm mx-auto">
                <div class="answer-slot relative w-24  flex items-center justify-center transition-all duration-300" 
                     data-slot="1">
                    <img src="{{ asset('images/material/cloud.png') }}" alt="ช่องคำตอบ" class="w-full h-full object-contain opacity-30">
                </div>
                
                <div class="answer-slot relative w-24 flex items-center justify-center transition-all duration-300" 
                     data-slot="2">
                    <img src="{{ asset('images/material/cloud.png') }}" alt="ช่องคำตอบ" class="w-full h-full object-contain opacity-30">
                </div>
                
                <div class="answer-slot relative w-24  flex items-center justify-center transition-all duration-300" 
                     data-slot="3">
                    <img src="{{ asset('images/material/cloud.png') }}" alt="ช่องคำตอบ" class="w-full h-full object-contain opacity-30">
                </div>
            </div>
        </div>
    </div>

    <div id="success-modal"
        class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30">
        <div class="modal-content bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <img src="{{ asset('images/material/school_man.png') }}" alt="Happy Student"
                class="w-32 h-auto rounded-full mx-auto mb-4 object-cover">
            <h3 class="text-xl font-bold text-indigo-800">เยี่ยมมาก!</h3>
            <p class="text-lg text-indigo-800 mb-4">คำตอบของคุณถูกต้อง</p>
            <p class="text-indigo-800 text-lg mb-1">เริ่มความท้าทายในเกมถัดไปกันเลย</p>
            <button id="success-btn"
                class="bg-[#929AFF] text-white font-medium text-md py-1 px-6 rounded-lg transition-colors hover:bg-[#7B85FF]">
                เริ่ม
            </button>
        </div>
    </div>

    <div id="failure-modal"
        class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30">
        <div class="modal-content bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <img src="{{ asset('images/material/school_girl_false.png') }}" alt="School Girl Character"
                class="w-32 h-auto mx-auto mb-4 object-cover">
            <h3 class="text-xl font-bold text-indigo-800">ลองอีกครั้ง</h3>
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

    @include('layouts.game.script.2.index')
@endsection