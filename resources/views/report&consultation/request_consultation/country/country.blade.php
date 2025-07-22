@extends('layouts.main_category')

@php
    $mainUrl = '/main';
    
    $consultations = [
        [
            'name' => 'Childline Thailand Foundation',
            'phone' => '1387',
            'image' => '1387.png',
            'link' => 'https://www.facebook.com/childlinethailand/',
            'image_size' => 'w-16 h-16'
        ],
        [
            'name' => 'LOVECARE',
            'phone' => null,
            'image' => 'lovecare.png',
            'link' => 'https://web.facebook.com/lovecarestation/',
            'image_size' => 'w-20 h-20'
        ],
        [
            'name' => 'สายด่วน 1212',
            'phone' => '1212',
            'image' => '1212.png',
            'link' => 'https://web.facebook.com/1212ETDA',
            'image_size' => 'w-20 h-20'
        ],
        [
            'name' => 'สายด่วนสุขภาพจิต 1323',
            'phone' => '1323',
            'image' => '1323.png',
            'link' => 'https://1323alltime.camri.go.th/',
            'image_size' => 'w-20 h-20'
        ]
    ];
@endphp

@section('content')
<div class="card-container px-5 pb-6">
    <div class="text-center mb-12 relative">
        <div class="flex items-center justify-center">
            <div class="relative">
                <h1 class="text-2xl font-bold text-[#3E36AE] inline-block">ขอคำปรึกษาจากหน่วยงาน</h1>
                <p class="text-sm text-[#3E36AE] absolute -bottom-5 right-0">ประเทศไทย</p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        @foreach($consultations as $consultation)
            @include('report&consultation.request_consultation.country.card', $consultation)
        @endforeach
    </div>
</div>
@endsection