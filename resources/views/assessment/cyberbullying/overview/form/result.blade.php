@extends('layouts.assessment.result')
@section('content')
    <div class="bg-white w-full flex-grow rounded-t-[50px] px-6 pt-8 flex flex-col mt-8 pb-10">
        <div class="text-center mb-2 relative">
            <div class="flex items-center justify-center">
                <div class="relative">
                    <h1 class="text-3xl font-bold text-[#3E36AE] inline-block">แบบคัดกรองพฤติกรรม</h1>
                    <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">ผลประเมินภาพรวม</p>
                </div>
            </div>
        </div>

        @if(!empty($riskLevel))
        <div class="mb-6 p-4 rounded-lg {{ $riskLevel['bg_color'] }} border {{ $riskLevel['border_color'] }}">
            <div class="text-center">
                <h3 class="text-lg font-bold {{ $riskLevel['color'] }} mb-1">{{ $riskLevel['text'] }}</h3>
                <p class="text-sm {{ $riskLevel['color'] }}">{{ $riskLevel['description'] }}</p>
            </div>
        </div>
        @endif

        <div class="relative overflow-hidden rounded-lg mt-4 flex-grow">
            <button id="prev-arrow" class="absolute left-2 top-1/3 transform -translate-y-1/2 z-10 w-10 h-10 bg-white bg-opacity-80 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:bg-opacity-100">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            
            <button id="next-arrow" class="absolute right-2 top-1/3 transform -translate-y-1/2 z-10 w-10 h-10 bg-white bg-opacity-80 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:bg-opacity-100">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <div class="swiper-container flex transition-transform duration-300 ease-in-out" id="result-carousel">

                <div class="swiper-slide w-full flex-shrink-0 px-2">
                    <div class="bg-white w-full max-w-2xl mx-auto rounded-lg px-4 pt-4 flex flex-col pb-6">
                        <div class="text-center mb-4">
                            <h3 class="text-xl font-bold text-[#3E36AE] mb-2">ประสบการณ์การกระทำ</h3>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="flex justify-center mb-4">
                                @if (!$hasPersonActionExperience)
                                    <img src="{{ asset('images/mental_health/normal.png') }}" alt="Normal"
                                        class="w-32 h-32 object-contain">
                                @else
                                    <img src="{{ asset('images/mental_health/severe.png') }}" alt="Severe"
                                        class="w-32 h-32 object-contain">
                                @endif
                            </div>

                            <div class="mb-6 text-center">
                                <p class="text-lg font-medium text-gray-700">คะแนนของคุณ</p>
                                <p class="text-4xl font-bold text-[#3E36AE] mt-2">{{ $personActionScore }} / 36</p>
                            </div>

                            <div class="w-full max-w-md bg-gray-100 h-4 rounded-full overflow-hidden mb-2">
                                <div class="bg-[#3E36AE] h-full transition-all duration-500"
                                    style="width: {{ $personActionPercentage }}%"></div>
                            </div>

                            <p class="text-center text-gray-600 mb-4">
                                คะแนนของคุณคิดเป็น {{ number_format($personActionPercentage, 1) }}%
                            </p>

                            <div class="border-t border-gray-300 w-full pt-4">
                                <h2 class="text-sm font-bold text-[#3E36AE] mb-2">ผลการประเมิน</h2>
                                @if (!$hasPersonActionExperience)
                                    <p class="text-green-600 font-medium mb-2 text-center text-xl">
                                        ไม่มีพฤติกรรมการกลั่นแกล้ง</p>
                                    <p class="text-gray-600 text-sm">คำแนะนำ: ไม่มีพฤติกรรมการกลั่นแกล้ง</p>
                                @else
                                    <p class="text-red-600 font-medium mb-2 text-center text-xl">มีพฤติกรรมการกลั่นแกล้ง</p>
                                    <p class="text-gray-600 text-lg">คำแนะนำ: มีพฤติกรรมการกลั่นแกล้ง</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide w-full flex-shrink-0 px-2">
                    <div class="bg-white w-full max-w-2xl mx-auto rounded-lg px-4 pt-4 flex flex-col pb-6">
                        <div class="text-center mb-4">
                            <h3 class="text-xl font-bold text-[#3E36AE] mb-2">ประสบการณ์การถูกกระทำ</h3>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="flex justify-center mb-4">
                                @if (!$hasVictimExperience)
                                    <img src="{{ asset('images/mental_health/normal.png') }}" alt="Normal"
                                        class="w-32 h-32 object-contain">
                                @else
                                    <img src="{{ asset('images/mental_health/severe.png') }}" alt="Severe"
                                        class="w-32 h-32 object-contain">
                                @endif
                            </div>

                            <div class="mb-6 text-center">
                                <p class="text-lg font-medium text-gray-700">คะแนนของคุณ</p>
                                <p class="text-4xl font-bold text-[#3E36AE] mt-2">{{ $victimScore }} / 36</p>
                            </div>

                            <div class="w-full max-w-md bg-gray-100 h-4 rounded-full overflow-hidden mb-2">
                                <div class="bg-[#3E36AE] h-full transition-all duration-500"
                                    style="width: {{ $victimPercentage }}%"></div>
                            </div>

                            <p class="text-center text-gray-600 mb-4">
                                คะแนนของคุณคิดเป็น {{ number_format($victimPercentage, 1) }}%
                            </p>

                            <div class="border-t border-gray-300 w-full pt-4">
                                <h2 class="text-sm font-bold text-[#3E36AE] mb-2">ผลการประเมิน</h2>
                                @if (!$hasVictimExperience)
                                    <p class="text-green-600 font-medium mb-2 text-center text-xl">ไม่เคยถูกกลั่นแกล้ง</p>
                                    <p class="text-gray-600 text-sm">คำแนะนำ: ไม่เคยถูกกลั่นแกล้ง</p>
                                @else
                                    <p class="text-red-600 font-medium mb-2 text-center text-xl">เคยถูกกลั่นแกล้ง</p>
                                    <p class="text-gray-600 text-lg">คำแนะนำ: เคยถูกกลั่นแกล้ง</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(!empty($recommendations))
                <div class="swiper-slide w-full flex-shrink-0 px-2">
                    <div class="bg-white w-full max-w-2xl mx-auto rounded-lg px-4 pt-4 flex flex-col pb-6">
                        <div class="text-center mb-4">
                            <h3 class="text-xl font-bold text-[#3E36AE] mb-2">คำแนะนำ</h3>
                        </div>

                        <div class="space-y-4">
                            @foreach($recommendations as $recommendation)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-[#3E36AE] mb-3">{{ $recommendation['title'] }}</h4>
                                    <ul class="space-y-2">
                                        @foreach($recommendation['items'] as $item)
                                            <li class="flex items-start">
                                                <span class="inline-block w-2 h-2 bg-[#3E36AE] rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                                <span class="text-gray-700 text-sm">{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="flex justify-center mb-6">
            <div class="flex space-x-2">
                <button class="dot w-3 h-3 rounded-full bg-[#3E36AE] transition-all duration-300" data-slide="0"></button>
                <button class="dot w-3 h-3 rounded-full bg-gray-300 transition-all duration-300" data-slide="1"></button>
                @if(!empty($recommendations))
                <button class="dot w-3 h-3 rounded-full bg-gray-300 transition-all duration-300" data-slide="2"></button>
                @endif
            </div>
        </div>

        <div class="border-b border-gray-300"></div>

        <div class="flex justify-center mt-6">
            <a href="{{ route('main') }}"
                class="text-lg px-6 py-2 rounded-xl text-white font-medium shadow-md bg-[#c0c0c0] transition-all duration-300 hover:bg-gray-400">
                หน้าหลัก
            </a>
        </div>
    </div>
    @include('layouts.assessment.script')
@endsection