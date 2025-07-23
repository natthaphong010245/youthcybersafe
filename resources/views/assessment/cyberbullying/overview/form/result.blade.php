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

        <div class="relative overflow-hidden rounded-lg mt-8 flex-grow">
            <div class="swiper-container flex transition-transform duration-300 ease-in-out" id="result-carousel">

                <div class="swiper-slide w-full flex-shrink-0 px-2">
                    <div class="bg-white w-full max-w-2xl mx-auto rounded-lg px-4 pt-4 flex flex-col pb-6">
                        <div class="text-center mb-4">
                            <h3 class="text-xl font-bold text-[#3E36AE] mb-2">ประสบการณ์การกระทำ</h3>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="flex justify-center mb-4">
                                @if ($personActionScore == 0)
                                    <div class="text-8xl">😊</div>
                                @else
                                    <div class="text-8xl">😟</div>
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
                                @if ($personActionScore == 0)
                                    <p class="text-green-600 font-medium mb-2 text-center text-xl">
                                        ไม่มีพฤติกรรมการกลั่นแกล้ง</p>
                                    <p class="text-gray-600 text-sm">คำแนะนำ: ไม่มีพฤติกรรมการกลั่นแกล้ง</p>
                                @else
                                    <p class="text-red-600 font-medium mb-2 text-center text-xl">มีพฤติกรรมการกลั่นแกล้ง</p>
                                    <p class="text-gray-600 text-lg">คำแนะนำ: มีพฤติกรรมการกลั่นแกล้ง</p>
                                @endif

                                <div class="flex justify-center mt-4">
                                    <a href="/assessment/cyberbullying/overview/form"
                                        class="text-base px-8 py-2 rounded-xl text-white font-medium shadow-md bg-[#929AFF] transition-all duration-300 hover:bg-[#7B84FC]">
                                        ดูรายละเอียดเพิ่มเติม
                                    </a>
                                </div>
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
                                @if ($victimScore == 0)
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
                                @if ($victimScore == 0)
                                    <p class="text-green-600 font-medium mb-2 text-center text-xl">ไม่เคยถูกกลั่นแกล้ง</p>
                                    <p class="text-gray-600 text-sm">คำแนะนำ: ไม่เคยถูกกลั่นแกล้ง</p>
                                @else
                                    <p class="text-red-600 font-medium mb-2 text-center text-xl">เคยถูกกลั่นแกล้ง</p>
                                    <p class="text-gray-600 text-lg">คำแนะนำ: เคยถูกกลั่นแกล้ง</p>
                                @endif

                                <div class="flex justify-center mt-4">
                                    <a href="/assessment/cyberbullying/overview/form"
                                        class="text-base px-8 py-2 rounded-xl text-white font-medium shadow-md bg-[#929AFF] transition-all duration-300 hover:bg-[#7B84FC]">
                                        ดูรายละเอียดเพิ่มเติม
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-center mb-6">
            <div class="flex space-x-2">
                <button class="dot w-3 h-3 rounded-full bg-[#3E36AE] transition-all duration-300" data-slide="0"></button>
                <button class="dot w-3 h-3 rounded-full bg-gray-300 transition-all duration-300" data-slide="1"></button>
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
