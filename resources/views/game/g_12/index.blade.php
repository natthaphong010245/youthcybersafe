@extends('layouts.game.dealing_bullying.index')

@php
    $backUrl = '/category/game';
    $mainUrl = '/main';
@endphp

@section('content')
    @include('game.intro', [
        'title' => 'การรับมือการกลั่นแกล้งบนโลกออนไลน์',
        'gameNumber' => '12',
        'description' => 'เวลาเจอปัญหานักเรียนจะรับมือการรังแกอย่างไร?',
        'actionText' => 'เริ่มความท้าทายกันเลย'
    ])

    <div class="card-container space-y-6 px-6 md:px-0" id="game-content">
        <div class="text-center mb-4 pl-6 pr-6">
            <h2 class="text-xl font-bold text-indigo-800">เวลาเจอปัญหานักเรียนจะรับมือการรังแกอย่างไร?</h2>
        </div>

        <div class="grid grid-cols-2 gap-4 w-full max-w-4xl mx-auto px-4">
            <div class="animal-card bg-white rounded-xl cursor-pointer transition-all border-2 border-transparent hover:border-indigo-200 flex flex-col items-center justify-center p-4"
                style="box-shadow: 0 0 20px rgba(0,0,0,0.1);" data-animal="tiger">
                <div class="flex-1 w-full flex items-center justify-center">
                    <img src="{{ asset('images/game/12/tiger.png') }}" alt="Tiger"
                        class="w-full h-full object-contain max-h-40">
                </div>
                <div class="mt-3 text-center">
                    <p class="text-indigo-800 font-medium text-lg tracking-wider">เสือ</p>
                </div>
            </div>

            <div class="animal-card bg-white rounded-xl cursor-pointer transition-all border-2 border-transparent hover:border-indigo-200 flex flex-col items-center justify-center p-4"
                style="box-shadow: 0 0 15px rgba(0,0,0,0.1);" data-animal="fox">
                <div class="flex-1 w-full flex items-center justify-center">
                    <img src="{{ asset('images/game/12/fox.png') }}" alt="Fox"
                        class="w-full h-full object-contain max-h-40">
                </div>
                <div class="mt-3 text-center">
                    <p class="text-indigo-800 font-medium text-lg tracking-wider">จิ้งจอก</p>
                </div>
            </div>

            <div class="animal-card bg-white rounded-xl cursor-pointer transition-all border-2 border-transparent hover:border-indigo-200 flex flex-col items-center justify-center p-4"
                style="box-shadow: 0 0 15px rgba(0,0,0,0.1);" data-animal="butterfly">
                <div class="flex-1 w-full flex items-center justify-center">
                    <img src="{{ asset('images/game/12/butterfly.png') }}" alt="Butterfly"
                        class="w-full h-full object-contain max-h-40">
                </div>
                <div class="mt-3 text-center">
                    <p class="text-indigo-800 font-medium text-lg tracking-wider">ผีเสื้อ</p>
                </div>
            </div>

            <div class="animal-card bg-white rounded-xl cursor-pointer transition-all border-2 border-transparent hover:border-indigo-200 flex flex-col items-center justify-center p-2"
                style="box-shadow: 0 0 15px rgba(0,0,0,0.1);" data-animal="rabbit">
                <div class="flex-1 w-full flex items-center justify-center">
                    <img src="{{ asset('images/game/12/rabbit.png') }}" alt="Rabbit"
                        class="w-full h-full object-contain max-h-40">
                </div>
                <div class="mt-3 text-center">
                    <p class="text-indigo-800 font-medium text-lg tracking-wider">กระต่าย</p>
                </div>
            </div>
        </div>
    </div>

    <div id="animal-modal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 opacity-0">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <div class="mb-6">
                <div class="mb-4">
                    <img id="modal-animal-image" src="" alt="" class="w-40 h-auto mx-auto object-contain">
                </div>

                <h3 id="modal-animal-name" class="text-2xl font-bold text-indigo-800"></h3>

                <p id="modal-animal-description" class="text-indigo-800 text-lg leading-relaxed"
                    style="font-size: 1.125rem !important;"></p>
            </div>

            <div class="text-indigo-800 text-lg font-md mb-2">เริ่มความท้าทายในเกมถัดไปกันเลย</div>

            <button id="continue-btn"
                class="bg-[#929AFF] text-white font-medium text-md py-1 px-6 rounded-lg transition-colors">
                ถัดไป
            </button>
        </div>
    </div>

    @include('layouts.game.script.12.index')
@endsection