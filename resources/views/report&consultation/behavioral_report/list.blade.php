{{-- resources/views/report&consultation/behavioral_report/list.blade.php --}}
@extends('layouts.category_game')

@php
    $mainUrl = '/main';
@endphp

@section('content')
<div class="card-container space-y-6 px-4 md:px-10 mr-4 ml-4">
    <div class="text-center mb-6">
        <div class="flex items-center justify-center">
            <div class="relative">
                <h1 class="text-2xl md:text-3xl font-bold text-[#3E36AE] inline-block">รายการรายงานพฤติกรรม</h1>
                <p class="text-sm md:text-base text-[#3E36AE] absolute -bottom-6 right-0">การรังแก</p>
            </div>
        </div>
    </div>

    {{-- สถิติรวม --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-[#3E36AE]">{{ $reports->total() }}</div>
                <div class="text-sm text-gray-600">รายงานทั้งหมด</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $reports->where('has_voice', 1)->count() }}</div>
                <div class="text-sm text-gray-600">มีไฟล์เสียง</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $reports->where('has_images', 1)->count() }}</div>
                <div class="text-sm text-gray-600">มีรูปภาพ</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $reports->whereNotNull('latitude')->count() }}</div>
                <div class="text-sm text-gray-600">มีตำแหน่ง</div>
            </div>
        </div>
    </div>

    {{-- รายการรายงาน --}}
    @if($reports->count() > 0)
    <div class="space-y-4">
        @foreach($reports as $report)
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <h3 class="text-lg font-semibold text-[#3E36AE] mr-3">
                            รายงาน #{{ str_pad($report->id, 6, '0', STR_PAD_LEFT) }}
                        </h3>
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $report->who === 'teacher' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            {{ $report->who === 'teacher' ? 'ครู' : 'นักวิจัย' }}
                        </span>
                        @if($report->school)
                        <span class="ml-2 px-3 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                            {{ $report->school }}
                        </span>
                        @endif
                    </div>
                    
                    <p class="text-gray-700 mb-3 leading-relaxed">
                        {{ Str::limit($report->message, 200) }}
                    </p>
                    
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-3">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($report->created_at)->format('d/m/Y H:i') }}
                        </span>
                        
                        @if($report->has_voice)
                        <span class="flex items-center text-green-600">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd"/>
                            </svg>
                            มีเสียง
                        </span>
                        @endif
                        
                        @if($report->has_images)
                        <span class="flex items-center text-blue-600">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                            มีรูปภาพ
                        </span>
                        @endif

                        @if($report->latitude && $report->longitude)
                        <span class="flex items-center text-purple-600">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            มีตำแหน่ง
                        </span>
                        @endif
                    </div>
                </div>
                
                <div class="ml-4 flex flex-col gap-2">
                    <a href="{{ route('behavioral-report.show', $report->id) }}" 
                       class="bg-[#7F77E0] text-white px-4 py-2 rounded-lg hover:bg-[#3E36AE] transition-colors text-center text-sm font-medium">
                        ดูรายละเอียด
                    </a>
                    @if($report->has_voice)
                    <button onclick="playAudio({{ $report->id }})"
                            class="bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 transition-colors text-center text-sm font-medium">
                        เล่นเสียง
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $reports->links() }}
    </div>

    @else
    {{-- ไม่มีรายงาน --}}
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">ยังไม่มีรายงานพฤติกรรม</h3>
        <p class="text-gray-500 mb-6">เมื่อมีผู้ส่งรายงานพฤติกรรม จะแสดงรายการที่นี่</p>
        <a href="{{ route('behavioral_report') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-[#7F77E0] hover:bg-[#3E36AE] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3E36AE]">
            สร้างรายงานใหม่
        </a>
    </div>
    @endif

    {{-- ปุ่มกลับ --}}
    <div class="flex justify-center mt-8">
        <button type="button" onclick="history.back()" 
                class="px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3E36AE] touch-manipulation">
            กลับ
        </button>
    </div>
</div>

{{-- Modal สำหรับเล่นเสียง --}}
<div id="audioModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-[#3E36AE]">เล่นไฟล์เสียง</h3>
            <button onclick="closeAudioModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <audio id="modalAudio" controls class="w-full">
            <source src="" type="audio/mpeg">
            เบราว์เซอร์ของคุณไม่รองรับการเล่นเสียง
        </audio>
    </div>
</div>

<script>
// ฟังก์ชันเล่นเสียง
function playAudio(reportId) {
    const modal = document.getElementById('audioModal');
    const audio = document.getElementById('modalAudio');
    const source = audio.querySelector('source');
    
    source.src = `/behavioral-report/${reportId}/voice`;
    audio.load();
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAudioModal() {
    const modal = document.getElementById('audioModal');
    const audio = document.getElementById('modalAudio');
    
    audio.pause();
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// ปิด modal เมื่อกดปุ่ม ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAudioModal();
    }
});

// ปิด modal เมื่อคลิกด้านนอก
document.getElementById('audioModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAudioModal();
    }
});
</script>

@endsection