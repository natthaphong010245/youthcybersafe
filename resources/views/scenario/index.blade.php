@extends('layouts.category_game')

@php
    $backUrl = '/main/game';
    $mainUrl = '/main';
@endphp


@section('content')
<div class="text-center mb-12 mt-2 relative">
        <div class="flex items-center justify-center">
            <div class="relative">
                <h1 class="text-3xl font-bold text-[#3E36AE] inline-block">SCENARIO</h1>
                <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">หมวดหมู่</p>
            </div>
        </div>
    </div>
    @include('layouts.scenario.selection')
    @include('layouts.scenario.script.index')
@endsection

