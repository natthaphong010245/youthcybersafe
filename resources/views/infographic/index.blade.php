@extends('layouts.main_category')

@section('content')
    <div class="px-4 md:px-0">
        <div class="text-center mb-12 relative">
            <div class="flex items-center justify-center">
                <div class="relative">
                    <h1 class="text-3xl font-bold text-[#3E36AE] inline-block mb-1">สาระน่ารู้</h1>
                    <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">หมวดหมู่</p>
                </div>
            </div>
        </div>

        <div class="max-w-2xl mx-auto">
            <div class="border-t border-[#BDC3FF]"></div>
            
            @foreach($topics as $topicId => $topic)
                @if($topic['available'])
                    <a href="{{ route('infographic.show', $topicId) }}" 
                       class="flex items-center gap-4 cursor-pointer hover:bg-gray-50 transition p-4 rounded-lg">
                        <div class="w-20 h-20 flex-shrink-0 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            @php
                                $thumbnailPath = "images/infographic/{$topicId}/1.png";
                            @endphp
                            
                            @if(file_exists(public_path($thumbnailPath)))
                                <img src="{{ asset($thumbnailPath) }}" 
                                     alt="{{ $topic['title'] }}" 
                                     class="w-full h-full object-cover" />
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#929AFF] to-[#DBAEF4] flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1 flex items-center justify-center">
                            <h3 class="font-medium text-[#3a3a3a] text-lg leading-snug text-center">
                                {{ $topic['title'] }}
                            </h3>
                        </div>
                    </a>
                @else
                    <div class="flex items-center gap-4 p-4 rounded-lg opacity-50 cursor-not-allowed">
                        <div class="w-20 h-20 flex-shrink-0 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            @php
                                $thumbnailPath = "images/infographic/{$topicId}/1.png";
                            @endphp
                            
                            @if(file_exists(public_path($thumbnailPath)))
                                <img src="{{ asset($thumbnailPath) }}" 
                                     alt="{{ $topic['title'] }}" 
                                     class="w-full h-full object-cover grayscale" />
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1 flex items-center justify-center">
                            <h3 class="font-medium text-gray-400 text-lg leading-snug text-center">
                                {{ $topic['title'] }}
                            </h3>
                        </div>
                    </div>
                @endif
                
                <div class="border-b border-[#BDC3FF]"></div>
            @endforeach
        </div>

        @if(session('error'))
            <div class="fixed bottom-4 left-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50">
                {{ session('error') }}
            </div>
        @endif
    </div>

@endsection