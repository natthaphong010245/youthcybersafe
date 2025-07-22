<div class="bg-[#929AFF] rounded-xl p-3 relative flex items-center">
    <div class="min-w-[80px] h-[80px] bg-white rounded-full flex items-center justify-center overflow-hidden mr-4">
        <img src="{{ asset('images/report_consultation/province/' . $image) }}" 
             alt="{{ $name }}" 
             class="{{ $image_size }} object-contain">
    </div>

    <div class="flex-grow">
        <div class="text-white font-medium text-lg leading-tight mb-1">{{ $name }}</div>

        @if($phone)
        <a href="tel:{{ $phone }}" class="flex items-center text-white text-base">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            {{ $phone }}
        </a>
        @endif
    </div>

    <div class="absolute bottom-3 right-3">
        <a href="{{ $link }}" target="_blank" class="text-[#3E36AE] text-sm inline-flex items-center">
            แผนที่
        </a>
    </div>
</div>