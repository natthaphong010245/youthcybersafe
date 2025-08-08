@extends('layouts.main_category')

@php
    $mainUrl = '/main';
@endphp

@section('content')
    <div class="card-container space-y-10 px-10 md:px-0">
        <div class="text-center mb-8 relative">
            <div class="flex items-center justify-center">
                <div class="relative mt-2">
                    <h1 class="text-3xl font-bold text-[#3E36AE] inline-block">พื้นที่ปลอดภัย</h1>
                </div>
            </div>
        </div>

        <div class="space-y-20 px-2">
            @php
                $shareOptions = [
                    [
                        'route' => 'safe_area/voice',
                        'icon' => 'microphone.png',
                        'title' => 'แชร์ประสบการณ์ด้วย',
                        'subtitle' => 'เสียง',
                    ],
                    [
                        'route' => 'safe_area/message',
                        'icon' => 'message.png',
                        'title' => 'แชร์ประสบการณ์ด้วย',
                        'subtitle' => 'ข้อความ',
                    ],
                ];
            @endphp

            @foreach ($shareOptions as $option)
                <button onclick="window.location.href='{{ route($option['route']) }}'"
                    class="bg-[#929AFF] w-full h-24 py-2 rounded-xl text-white flex items-center justify-center space-x-8">
                    <img src="{{ asset('images/safe_area/' . $option['icon']) }}" alt="{{ $option['subtitle'] }} Icon"
                        class="h-20 w-auto object-contain">
                    <div class="text-left">
                        <div class="font-median text-lg">{{ $option['title'] }}</div>
                        <div class="text-2xl font-median ml-4">{{ $option['subtitle'] }}</div>
                    </div>
                </button>
            @endforeach
        </div>
    </div>
@endsection
