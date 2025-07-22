@extends('layouts.main_category')

@php
    $mainUrl = '/main';
@endphp

@section('content')
<div class="card-container space-y-10 px-10 md:px-0">
    <div class="text-center mb-8 relative">
        <div class="flex items-center justify-center">
            <div class="relative">
                <h1 class="text-3xl font-bold text-[#3E36AE] inline-block">รายงาน ขอคำปรึกษา</h1>
                <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">หมวดหมู่</p>
            </div>
        </div>
    </div>

    @php
    $reportTypes = [
        [
            'route' => 'safe_area',
            'title' => 'พื้นที่ปลอดภัย',
            'image' => 'safe_area.png'
        ],
        [
            'route' => 'behavioral_report',
            'title' => 'รายงานพฤติกรรม',
            'image' => 'behavioral_report.png'
        ],
        [
            'route' => 'request_consultation',
            'title' => 'ขอรับการปรึกษา',
            'image' => 'request_advice.png'
        ]
    ];
    @endphp

    @foreach($reportTypes as $index => $type)
    <a href="{{ route($type['route']) }}" class="block relative {{ $index > 0 ? 'mt-8' : '' }}">
        <div class="flex items-center h-24 rounded-[10px] relative bg-[#929AFF] mb-4">
            <div class="absolute left-6 -top-8 z-10">
                <img src="{{ asset('images/report_consultation/' . $type['image']) }}" 
                     alt="{{ $type['title'] }}" 
                     class="w-auto h-28 object-contain">
            </div>
            <div class="flex-1 flex items-center justify-center pr-6 pl-24">
                <div class="font-medium text-white text-xl">{{ $type['title'] }}</div>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endsection