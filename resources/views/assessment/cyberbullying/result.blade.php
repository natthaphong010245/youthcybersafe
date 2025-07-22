@extends('layouts.app')

@section('content')
    <div class="bg-white w-full flex-grow rounded-t-[50px] px-6 pt-8 flex flex-col mt-8 pb-10">
        @php
            $config = config("cyberbullying.assessment_types.{$type}");
        @endphp
        
        <div class="text-center mb-2 relative">
            <div class="flex items-center justify-center">
                <div class="relative">
                    <h1 class="text-2xl font-bold text-[#3E36AE] inline-block">{{ $config['title'] }}</h1>
                    <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">{{ $config['subtitle'] }}</p>
                </div>
            </div>
        </div>

        <x-cyberbullying.result-card 
            :score="$score" 
            :percentage="$percentage" 
            :max-score="$maxScore" 
            :type="$type" 
        />
        
        <div class="border-b border-gray-300"></div>
        <div class="flex justify-center mt-6">
            <a href="{{ route('main') }}" class="text-lg px-6 py-2 rounded-xl text-white font-medium shadow-md bg-[#c0c0c0] transition-all duration-300 hover:bg-gray-400">
                หน้าหลัก
            </a>
        </div>
    </div>
@endsection