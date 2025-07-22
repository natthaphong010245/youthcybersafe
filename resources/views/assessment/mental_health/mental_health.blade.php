@extends('layouts.assessment.mental_health.index')

@section('content')

    <div class="flex flex-col items-center justify-center " style="margin-top: 5rem">
        <div class="text-center">
            
            <h2 class="text-3xl font-bold mb-2 text-white">แบบประเมินสุขภาพจิต </h2>
            <p class="text-lg text-white mb-8">ภาวะสุขภาพจิตภาพรวม</p>
        <a href="{{ route('mental_health/form') }}" class="inline-block bg-[#3E36AE] text-white text-xl font-bold py-2 px-10 rounded-lg  transition duration-300 shadow-lg shadow-indigo-900/30 active:bg-[#332d91]">
            เริ่ม
        </a>
        </div>
    </div>
@endsection
