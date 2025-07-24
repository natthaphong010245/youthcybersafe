@extends('layouts.main_category')

@php
    $mainUrl = '/main';
@endphp

@section('content')
    <div class="content-container bg-white w-full flex-grow rounded-t-[50px] px-6 pt-4 flex flex-col">
        <div class="text-center mb-5 relative">
            <div class="flex items-center justify-center">
                <div class="relative">
                    <h1 class="text-2xl font-bold text-[#3E36AE] inline-block">เราพร้อมที่จะรับฟังคุณเสมอ</h1>
                </div>
            </div>
            <p id="recordText" class="text-base text-[#747474] mt-10">แตะเพื่อบันทึกข้อความเสียง</p>
        </div>

        <div class="flex flex-col items-center">
            <div id="timer" class="text-[#e80000c6] text-2xl font-bold mb-3 hidden">00:00</div>

            <div class="flex justify-center items-center w-full">
                <button id="deleteButton"
                    class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-6 hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>

                <div class="relative w-32 h-32">
                    <svg id="progressCircle" class="absolute top-1/2 left-1/2 w-40 h-40 -mt-20 -ml-20 hidden"
                        viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="47" fill="none" stroke="#ffffff" stroke-width="6" />
                        <circle id="progressArc" cx="50" cy="50" r="47" fill="none" stroke="#3E36AE"
                            stroke-width="6" stroke-dasharray="295.31" stroke-dashoffset="295.31"
                            transform="rotate(-90 50 50)" />
                    </svg>

                    <div id="waveContainer" class="absolute top-0 left-0 w-full h-full hidden">
                        <div id="waveCircle"
                            class="absolute top-0 left-0 w-full h-full rounded-full bg-[#929AFF] opacity-30"></div>
                    </div>

                    <div id="recordButton"
                        class="w-32 h-32 rounded-full bg-[#3E36AE] border-4 border-gray-200 flex items-center justify-center cursor-pointer relative z-10">

                        <div id="recordState" class="w-6 h-6 rounded-full bg-red-500"></div>

                        <div id="stopState" class="w-8 h-8 rounded-lg bg-white hidden"></div>

                        <div id="playState" class="hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-white" viewBox="0 0 24 24"
                                fill="#ffffff">
                                <path d="M8 5.14v14l11-7-11-7z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <button id="sendButton"
                    class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-6 hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 " fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" transform="rotate(90, 12, 12)" />
                    </svg>
                </button>
            </div>

            <div class="text-[#3E36AE] text-base mt-12 w-full text-center pl-1 pr-1">
                <p>บางครั้งแค่ได้เล่าออกมา ก็ช่วยให้ใจเราสบายขึ้นนะ เพราะน้องไม่ได้ผ่านเรื่องเหล่านั้นมาคนเดียว</p>
            </div>
        </div>
    </div>

    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl w-80 overflow-hidden">
            <div id="modalContent">
                <div class="p-5 flex items-center justify-center">
                        <img src="{{ asset('images/safe_area/microphone.png') }}" alt="Chat Icon" class="h-20 w-auto">
                </div>
                <div class="px-4 text-center">
                    <div class="text-xl font-median text-[#3E36AE]">ข้อความเสียง</div>
                    <div class="text-lg font-median text-[#3E36AE]">คุณต้องการส่งข้อความเสียง?</div>
                </div>
                <div class="flex flex-col p-4 space-y-2 mb-2">
                    <button id="confirmSend"
                        class="w-full py-2 bg-[#929AFF] rounded-lg text-white font-medium">ตกลง</button>
                    <button id="cancelSend"
                        class="w-full py-2 bg-white border border-gray-300 rounded-lg text-gray-500 font-medium">ยกเลิก</button>
                </div>
            </div>

            <div id="loadingContent" class="p-8 flex flex-col items-center justify-center hidden">
                <div class="flex items-center justify-center mt-8 mb-8">
                    <svg class="animate-spin h-16 w-16 text-[#929AFF]" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @include('report&consultation.safe_area.result')

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@include('layouts.report&consultation.safe_area.voice.script')
