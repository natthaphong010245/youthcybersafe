@extends('layouts.main_category')

@php
    $backUrl = '/main';
    $mainUrl = '/main';
@endphp

@section('content')

<div class="card-container space-y-10 px-10 md:px-0">
    <div class="text-center mb-12 mt-2 relative">
        <div class="flex items-center justify-center">
            <div class="relative">
                <h1 class="text-3xl font-bold text-[#3E36AE] inline-block">GAME & SCENARIO</h1>
                <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">หมวดหมู่</p>
            </div>
        </div>
    </div>

    @php
    $gameScenarios = [
        [
            'route' => 'game',
            'title' => 'GAME',
            'image' => 'game.png',
            'image_position' => 'left-10',
            'text_position' => 'pr-6 pl-24',
            'text_extra_class' => ''
        ],
        [
            'route' => 'scenario.index',
            'title' => 'SCENARIO',
            'image' => 'scenario.png',
            'image_position' => 'left-3',
            'text_position' => 'pr-6 pl-24',
            'text_extra_class' => 'ml-4'
        ]
    ];
    @endphp

    @foreach($gameScenarios as $index => $item)
    <a href="{{ route($item['route']) }}" class="block relative {{ $index > 0 ? 'mt-16' : '' }}">
        <div class="flex items-center h-24 rounded-2xl relative bg-[#929AFF] mb-12">
            <div class="absolute {{ $item['image_position'] }} -top-8 z-10">
                <img src="{{ asset('images/game/' . $item['image']) }}" 
                     alt="{{ $item['title'] }} Icon" 
                     class="w-auto h-28 object-contain">
            </div>
            <div class="flex-1 flex items-center justify-center {{ $item['text_position'] }}">
                <div class="flex flex-col text-center">
                    <div class="font-medium text-white text-2xl {{ $item['text_extra_class'] }}">{{ $item['title'] }}</div>
                </div>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endsection