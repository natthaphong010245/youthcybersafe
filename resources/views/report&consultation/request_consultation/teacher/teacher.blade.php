@extends('layouts.main_category')

@php
    $mainUrl = '/main';
@endphp

@section('content')
<div class="card-container space-y-10 px-10 md:px-0">
    <div class="text-center mb-4 relative">
        <div class="flex items-center justify-center">
            <div class="relative">
                <h1 class="text-2xl font-bold text-[#3E36AE] inline-block">ขอคำปรึกษาจากคุณครู</h1>
                <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">โรงเรียน</p>
            </div>
        </div>
    </div>

    @php
    $schools = [
        [
            'name' => 'โรงเรียน 1',
            'location' => 'จังหวัดเชียงราย',
            'route' => 'cyberbullying',
            'image' => 'teen-1.png'
        ],
        [
            'name' => 'โรงเรียน 2',
            'location' => 'จังหวัดเชียงราย',
            'route' => 'cyberbullying',
            'image' => 'teen-1.png'
        ],
        [
            'name' => 'โรงเรียน 3',
            'location' => 'จังหวัดเชียงราย',
            'route' => 'cyberbullying',
            'image' => 'teen-1.png'
        ]
    ];
    @endphp

    @foreach($schools as $school)
    <a href="{{ route($school['route']) }}" class="block relative">
        <div class="py-3 px-6 flex items-center justify-between h-20 rounded-[10px] bg-[#929AFF]">
            <div class="flex flex-col">
                <div class="font-median text-white text-lg">{{ $school['name'] }}</div>
                <div class="font-median text-white text-xl">{{ $school['location'] }}</div>
            </div>
            <div class="flex items-center justify-center">
                <img src="{{ asset('images/' . $school['image']) }}" 
                     alt="{{ $school['name'] }}" 
                     class="w-16 h-16 object-contain">
            </div>
        </div>
    </a>
    @endforeach
</div>
@endsection