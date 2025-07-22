@extends('layouts.main_category')

@php
    $mainUrl = '/main';
@endphp

@section('content')
    <div class="bg-white w-full flex-grow rounded-t-[50px] px-6 pt-4 flex flex-col ">
        <div class="text-center mb-4 relative">
            <div class="flex items-center justify-center">
                <div class="relative">
                    <h1 class="text-2xl font-bold text-[#3E36AE] inline-block">เราพร้อมที่จะรับฟังคุณเสมอ</h1>
                </div>
            </div>
        </div>

        <form id="messageForm" action="{{ route('safe-area.message.store') }}" method="POST"
            class="flex flex-col items-center p-2">
            @csrf
            <div class="w-full mb-2">
                <textarea name="message" id="message"
                    class="w-full border border-[#929AFF] rounded-xl px-3 py-3 text-gray-600 focus:outline-none focus:ring-2 focus:ring-[#929AFF] resize-y min-h-[160px] leading-tight"
                    placeholder="YOUTH CYBERSAFE" rows="2" required></textarea>
            </div>

            <div class="flex justify-between w-full p-6">
                <button type="button" onclick="history.back()"
    class="w-[40%] py-2 border border-gray-300 text-gray-500 rounded-lg font-medium text-center text-lg focus:outline-none">ยกเลิก</button>
                <button type="button" id="submitBtn"
                    class="w-[40%] py-2 bg-[#929AFF] text-white rounded-lg font-medium text-lg">ส่ง</button>
            </div>
        </form>
    </div>

    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl w-80 overflow-hidden">
            <div id="modalContent">
                <div class="p-5 flex items-center justify-center">
                        <img src="{{ asset('images/safe_area/message.png') }}" alt="Chat Icon" class="h-20 w-auto">
                </div>
                <div class="px-4 text-center">
                    <div class="text-xl font-median text-[#3E36AE]">ข้อความ</div>
                    <div class="text-lg font-median text-[#3E36AE]">คุณต้องการส่งข้อความ?</div>
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

@include('layouts.report&consultation.safe_area.message.script')
