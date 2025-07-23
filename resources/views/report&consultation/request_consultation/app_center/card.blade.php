{{-- resources/views/report&consultation/request_consultation/app_center/card.blade.php --}}
@if($type === 'modal')
    <button onclick="openPuangunModal()" class="block w-full">
        <div class="bg-[#929AFF] rounded-2xl px-10 pb-4 pt-10 relative flex flex-col items-center {{ $isFirst ? 'mb-8 mt-14' : '' }} min-h-[100px] hover:bg-[#8088FF] transition-colors">
            <div class="absolute -top-14 left-1/2 transform -translate-x-1/2 w-[auto] h-[auto] flex items-center justify-center overflow-hidden">
                <img src="{{ asset($image) }}" alt="{{ $alt }}" class="w-[auto] h-[110px] object-contain">
            </div>
            <div class="text-center mt-4">
                <div class="text-white font-median mb-1 text-2xl tracking-wider">{{ $name }}</div>
            </div>
        </div>
    </button>
@else
    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="block">
        <div class="bg-[#929AFF] rounded-2xl px-10 pb-4 pt-10 relative flex flex-col items-center {{ $isFirst ? 'mb-8 mt-14' : '' }} min-h-[100px] hover:bg-[#8088FF] transition-colors">
            <div class="absolute -top-14 left-1/2 transform -translate-x-1/2 w-[auto] h-[auto] flex items-center justify-center overflow-hidden">
                <img src="{{ asset($image) }}" alt="{{ $alt }}" class="w-[auto] h-[110px] object-contain">
            </div>
            <div class="text-center mt-4">
                <div class="text-white font-median mb-1 text-2xl tracking-wider">{{ $name }}</div>
            </div>
        </div>
    </a>
@endif