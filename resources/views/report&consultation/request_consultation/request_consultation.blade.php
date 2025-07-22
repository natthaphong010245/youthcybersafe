@extends('layouts.main_category')

@php
    $mainUrl = '/main';
@endphp

@section('content')
<div class="card-container space-y-10 px-10 md:px-0">
    <div class="text-center mb-4 relative">
        <div class="flex items-center justify-center">
            <div class="relative">
                <h1 class="text-3xl font-bold text-[#3E36AE] inline-block">ขอรับคำปรึกษา</h1>
                <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">ประเภท</p>
            </div>
        </div>
    </div>

    @php
    $consultationTypes = [
        [
            'route' => 'https://line.me/R/ti/p/@404ufabt?ts=07151747&oat_content=url',
            'title_top' => 'คำปรึกษาจาก',
            'title_bottom' => 'นักวิจัย',
            'image' => 'researcher.png',
            'is_external' => true
        ],
        [
            'route' => 'teacher_report',
            'title_top' => 'คำปรึกษาจาก',
            'title_bottom' => 'คุณครู',
            'image' => 'teacher.png',
            'is_external' => false
        ],
        [
            'route' => 'province_report',
            'title_top' => 'คำปรึกษาจากหน่วยงาน',
            'title_bottom' => 'จังหวัดเชียงราย',
            'image' => 'province.png',
            'is_external' => false
        ],
        [
            'route' => 'country_report',
            'title_top' => 'คำปรึกษาจากหน่วยงาน',
            'title_bottom' => 'ประเทศไทย',
            'image' => 'country.png',
            'is_external' => false
        ],
        [
            'route' => 'app_center_report',
            'title_top' => 'APPLICATION',
            'title_bottom' => 'พัฒนาจากศูนย์',
            'image' => 'application.png',
            'is_external' => false
        ]
    ];
    @endphp

    @foreach($consultationTypes as $type)
        <a href="{{ $type['is_external'] ? $type['route'] : route($type['route']) }}" 
           class="block relative"
           {{ $type['is_external'] ? 'target="_blank"' : '' }}>
            <div class="py-3 px-6 flex items-center justify-between h-24 rounded-[10px] bg-[#929AFF]">
                <div class="flex flex-col">
                    <div class="font-median text-white text-lg">{{ $type['title_top'] }}</div>
                    <div class="font-median text-white text-2xl">{{ $type['title_bottom'] }}</div>
                </div>
                <div class="flex items-center justify-center">
                    <img src="{{ asset('images/report_consultation/' . $type['image']) }}" 
                         alt="{{ $type['title_bottom'] }}" 
                         class="w-20 h-20 object-contain">
                </div>
            </div>
        </a>
    @endforeach
</div>
@endsection