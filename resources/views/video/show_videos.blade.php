@extends('layouts.main_category')

@section('content')
    <div class="card-container px-4 md:px-0">
        <div class="text-center mb-10 relative">
            <div class="flex items-center justify-center">
                <div class="relative">
                    <h1 class="text-3xl font-bold text-[#3E36AE] inline-block mb-1">สื่อการเรียนรู้</h1>
                    <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">ภาษา{{ $languageName }}</p>
                </div>
            </div>
        </div>

        <div class="max-w-2xl mx-auto">
            @foreach($videos as $index => $video)
                @if($index === 0)
                    <div class="border-t border-[#BDC3FF]"></div>
                @endif
                
                <div onclick="openYouTubeVideo('{{ $video['youtube_id'] }}')" 
                     class="flex items-center gap-4 cursor-pointer hover:bg-gray-50 transition p-3">
                    <img src="{{ $video['thumbnail'] }}" 
                         alt="{{ $video['title'] }}" 
                         class="w-28 h-28 object-cover rounded-xl flex-shrink-0" />
                    <div class="flex-1">
                        <h3 class="font-medium text-[#3a3a3a] text-lg leading-snug text-center">
                            {{ $video['title'] }}
                        </h3>
                    </div>
                </div>
                
                <div class="border-b border-[#BDC3FF]"></div>
            @endforeach
        </div>
    </div>

    <script>
        function openYouTubeVideo(youtubeId) {
            const youtubeUrl = `https://www.youtube.com/watch?v=${youtubeId}`;
            
            window.open(youtubeUrl, '_blank');
        }
    </script>
@endsection