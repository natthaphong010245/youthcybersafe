@extends('layouts.assessment.result')
@section('content')
    <div class="bg-white w-full flex-grow rounded-t-[50px] px-6 pt-8 flex flex-col mt-8 pb-10">
        <div class="text-center mb-2 relative">
            <div class="flex items-center justify-center">
                <div class="relative">
                    <h1 class="text-3xl font-bold text-[#3E36AE] inline-block">แบบคัดกรองพฤติกรรม</h1>
                    <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">ผลประเมินการกระทำ</p>
                </div>
            </div>
        </div>

        <div class="bg-white w-full max-w-2xl mx-auto rounded-t-[50px] px-6 pt-8 flex flex-col mt-2 pb-6">

            <div class="flex flex-col items-center">
                <div class="flex justify-center mb-4">
                    @if ($score == 0)
                        <img src="{{ asset('images/mental_health/normal.png') }}" alt="Normal"
                            class="w-32 h-32 object-contain">
                    @else
                        <img src="{{ asset('images/mental_health/severe.png') }}" alt="Severe"
                            class="w-32 h-32 object-contain">
                    @endif
                </div>

                <div class="mb-8 text-center">
                    <p class="text-xl font-medium text-gray-700">คะแนนของคุณ</p>
                    <p class="text-4xl font-bold text-[#3E36AE] mt-2">{{ $score }} / 36</p>
                </div>

                <div class="w-full max-w-md bg-gray-100 h-4 rounded-full overflow-hidden mb-2">
                    <div class="bg-[#3E36AE] h-full" style="width: {{ $percentage }}%"></div>
                </div>

                <p class="text-center text-gray-600 mb-4">
                    คะแนนของคุณคิดเป็น {{ number_format($percentage, 1) }}%
                </p>

                <div class="border-t border-gray-300 w-full pt-4">
                    <h2 class="text-sm font-bold text-[#3E36AE] mb-2">ผลการประเมิน</h2>
                    @if ($score == 0)
                        <p class="text-green-600 font-medium mb-2 text-center text-xl">ไม่มีพฤติกรรมการกลั่นแกล้ง</p>
                        <p class="text-gray-600 text-lg">คำแนะนำ: ไม่มีพฤติกรรมการกลั่นแกล้ง</p>
                    @else
                        <p class="text-red-600 font-medium mb-2 text-center text-xl">มีพฤติกรรมการกลั่นแกล้ง</p>
                        <p class="text-gray-600 text-lg">คำแนะนำ: มีพฤติกรรมการกลั่นแกล้ง</p>
                    @endif
                </div>

                <div class="flex justify-center mt-4">
                    <a href="/assessment/cyberbullying/person_action/form"
                        class="text-base px-6 py-2 rounded-xl text-white font-medium shadow-md bg-[#929AFF] transition-all duration-300 hover:bg-[#7B84FC]">
                        ดูรายละเอียดเพิ่มเติม
                    </a>
                </div>
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
