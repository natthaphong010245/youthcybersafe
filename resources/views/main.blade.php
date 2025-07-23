@extends('layouts.index')

@section('content')
@php
    $backUrl = '/home';
@endphp

@include('layouts.nav.main')

<div class="flex flex-col items-center justify-between min-h-screen">
    @include('layouts.logo')

    <div class="bg-white w-full flex-grow rounded-t-[50px] px-6 pt-8 flex flex-col mt-10">
        @php
        $menuItems = [
            [
                'route' => 'main_video',
                'title' => 'CYBERBULLYING',
                'subtitle' => 'สื่อการเรียนรู้',
                'image' => 'video.jpg',
                'alt' => 'Video',
                'type' => 'normal'
            ],
            [
                'route' => 'main_info', 
                'title' => 'CYBERBULLYING',
                'subtitle' => 'สาระน่ารู้',
                'image' => 'info.jpg',
                'alt' => 'Infographic',
                'type' => 'normal'
            ],
            [
                'route' => 'main_game',
                'title' => 'CYBERBULLYING',
                'subtitle' => 'เกม & สถานการณ์',
                'image' => 'game.jpg',
                'alt' => 'Game',
                'type' => 'normal'
            ],
            [
                'route' => 'report&consultation',
                'title' => 'CYBERBULLYING',
                'subtitle' => 'รายงาน & ขอคำปรึกษา',
                'image' => 'เเบบสอบถาม.jpg',
                'alt' => 'สุขภาพจิต',
                'type' => 'normal'
            ],
            [
                'route' => 'assessment',
                'title' => 'CYBERBULLYING',
                'subtitle' => 'แบบคัดกรอง',
                'image' => 'เเบบคัดกรอง.jpg',
                'alt' => 'แบบคัดกรอง',
                'type' => 'normal'
            ],
            [
                'route' => 'faq',
                'title' => '',
                'subtitle' => '',
                'image' => 'FAQ.jpg',
                'alt' => 'FAQ',
                'type' => 'faq'
            ]
        ];
        @endphp

        @foreach($menuItems as $index => $item)
        <a href="{{ $item['route'] === 'main_video' || $item['route'] === 'main_info' ? route($item['route']) : route($item['route']) }}" 
           class="relative block {{ $index === 0 ? 'mt-4' : 'mt-6' }} {{ $index === count($menuItems) - 1 ? 'mb-10' : '' }} rounded-2xl overflow-hidden shadow-lg border border-[#929AFF]">
            
            @if($item['type'] === 'faq')
                <div class="relative bg-white rounded-2xl overflow-hidden h-32 flex items-center justify-center">
                    <img src="images/{{ $item['image'] }}" alt="{{ $item['alt'] }}" class="w-full h-full object-cover rounded-xl">
                </div>
            @else
                <div class="relative bg-white rounded-2xl overflow-hidden h-32 flex items-center">
                    <div class="absolute inset-0 z-10 flex flex-col justify-center px-6">
                        <div class="text-2xl font-bold text-[#3E36AE] mb-1">{{ $item['title'] }}</div>
                        <div class="text-lg text-[#3E36AE] ml-3">{{ $item['subtitle'] }}</div>
                    </div>
                    <div class="absolute right-0 top-0 h-full w-40 flex items-center justify-center">
                        <img src="images/{{ $item['image'] }}" alt="{{ $item['alt'] }}" class="w-full h-full object-cover {{ $index === 0 ? 'rounded-r-2xl' : 'rounded-r-xl' }}">
                    </div>
                </div>
            @endif
        </a>
        @endforeach
    </div>
</div>
@endsection