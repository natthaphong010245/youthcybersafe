@extends('layouts.category_game')

@section('content')
    <div class="card-container space-y-6 px-2 sm:px-4 md:px-0">
        <div class="text-center mb-8 relative">
            <div class="flex items-center justify-center">
                <div class="relative">
                    <h1 class="text-3xl font-bold text-[#3E36AE] inline-block mb-1">สื่อการเรียนรู้</h1>
                    <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">ภาษา</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 w-full max-w-sm md:max-w-xl mx-auto">
            @foreach($languages as $langId => $langName)
                @php
                    $isBlue = in_array($langId, [1, 3, 5, 7]);
                    $bgColor = $isBlue ? 'bg-[#929AFF]' : 'bg-[#DBAEF4]';
                @endphp

                <a href="{{ route("main_video_language{$langId}") }}"
                   class="block w-full transform transition-transform mb-3">
                    <div class="py-6 px-8 flex items-center justify-center h-12 rounded-xl {{ $bgColor }} shadow-md hover:shadow-lg transition-all mr-8 ml-8">
                        <div class="font-medium text-white text-xl">{{ $langName }}</div>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
@endsection
