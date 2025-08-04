@extends('layouts.category_game')
{{-- resources/views/report&consultation/behavioral_report/behavioral_report.blade.php --}}
@php
    $mainUrl = '/main';
@endphp

@section('content')
@include('layouts.report&consultation.behavioral_report.behavioral_report')
@include('layouts.report&consultation.behavioral_report.record')
@include('layouts.report&consultation.behavioral_report.photo')
@include('layouts.report&consultation.behavioral_report.position')
@include('layouts.report&consultation.behavioral_report.notification')

<div class="card-container space-y-6 px-4 md:px-10 mr-4 ml-4">
    <div class="text-center mb-6 relative">
        <div class="flex items-center justify-center">
            <div class="relative">
                <h1 class="text-2xl md:text-3xl font-bold text-[#3E36AE] inline-block">การรายงานพฤติกรรม</h1>
                <p class="text-sm md:text-base text-[#3E36AE] absolute -bottom-6 right-0">การรังแก</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('behavioral-report.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-6">
            <label class="block text-lg font-medium text-[#3E36AE] mb-2">การรายงาน</label>
            <div class="dropdown-container">
                <div id="reportToDisplay" class="select-display">
                    <span id="reportToText" class="select-text text-gray-500">กรุณาเลือก</span>
                    <svg class="arrow-icon w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div id="reportToList" class="dropdown-list">
                    <div class="dropdown-item" data-value="teacher">ครู</div>
                    <div class="dropdown-item" data-value="researcher">นักวิจัย</div>
                </div>
                <input type="hidden" name="report_to" id="report_to" required>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-lg font-medium text-[#3E36AE] mb-2">โรงเรียน</label>
            <div class="dropdown-container">
                <div id="schoolDisplay" class="select-display">
                    <span id="schoolText" class="select-text text-gray-500">กรุณาเลือก</span>
                    <svg class="arrow-icon w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div id="schoolList" class="dropdown-list">
                    <div class="dropdown-item" data-value="โรงเรียน1">โรงเรียน1</div>
                    <div class="dropdown-item" data-value="โรงเรียน2">โรงเรียน2</div>
                    <div class="dropdown-item" data-value="โรงเรียน3">โรงเรียน3</div>
                    <div class="dropdown-item" data-value="โรงเรียน4">โรงเรียน4</div>
                </div>
                <input type="hidden" name="school" id="school">
            </div>
        </div>

        <div class="mb-6">
            <label for="message" class="block text-lg font-medium text-[#3E36AE] mb-2">ข้อความ</label>
            <textarea id="message" name="message" rows="4" required
                      class="w-full h-44 px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3E36AE] resize-none text-base"
                      style="font-size: 16px;" placeholder="กรุณาใส่ข้อความของคุณ..."></textarea>
        </div>

        @include('report&consultation.behavioral_report.record')

        @include('report&consultation.behavioral_report.photo')

        @include('report&consultation.behavioral_report.position')

        <div class="flex justify-center gap-4 mt-8 px-4">
            <button type="button" onclick="history.back()" class="flex-1 max-w-36 px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3E36AE] touch-manipulation">
                ยกเลิก
            </button>
            <button type="submit" class="flex-1 max-w-36 px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-[#7F77E0] hover:bg-[#3E36AE] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3E36AE] touch-manipulation">
                ส่ง
            </button>
        </div>
    </form>
</div>

@include('report&consultation.behavioral_report.notification')

@endsection