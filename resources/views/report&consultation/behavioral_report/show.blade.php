{{-- resources/views/report&consultation/behavioral_report/show.blade.php --}}
@extends('layouts.category_game')

@php
    $mainUrl = '/main';
@endphp

@section('content')
<div class="card-container space-y-6 px-4 md:px-10 mr-4 ml-4">
    <div class="text-center mb-6">
        <div class="flex items-center justify-center">
            <div class="relative">
                <h1 class="text-2xl md:text-3xl font-bold text-[#3E36AE] inline-block">รายละเอียดรายงานพฤติกรรม</h1>
                <p class="text-sm md:text-base text-[#3E36AE] absolute -bottom-6 right-0">การรังแก</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="space-y-6">
            {{-- ข้อมูลพื้นฐาน --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-lg font-medium text-[#3E36AE] mb-2">รายงานถึง:</label>
                    <p class="text-gray-700">{{ $report->who === 'teacher' ? 'ครู' : 'นักวิจัย' }}</p>
                </div>

                @if($report->school)
                <div>
                    <label class="block text-lg font-medium text-[#3E36AE] mb-2">โรงเรียน:</label>
                    <p class="text-gray-700">{{ $report->school }}</p>
                </div>
                @endif
            </div>

            <div>
                <label class="block text-lg font-medium text-[#3E36AE] mb-2">ข้อความ:</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 leading-relaxed">{{ $report->message }}</p>
                </div>
            </div>

            {{-- แสดงไฟล์เสียง --}}
            @if($report->voice_data)
            <div>
                <label class="block text-lg font-medium text-[#3E36AE] mb-2">บันทึกเสียง:</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <audio controls class="w-full">
                        <source src="{{ route('behavioral-report.voice', $report->id) }}" type="{{ $report->voice_mime_type ?? 'audio/mpeg' }}">
                        เบราว์เซอร์ของคุณไม่รองรับการเล่นเสียง
                    </audio>
                    <p class="text-xs text-gray-500 mt-2">
                        ประเภทไฟล์: {{ $report->voice_mime_type ?? 'audio/mpeg' }}
                    </p>
                </div>
            </div>
            @endif

            {{-- แสดงรูปภาพ --}}
            @if($report->image_data)
            @php
                $images = is_string($report->image_data) ? json_decode($report->image_data, true) : [];
                if (!is_array($images)) $images = [];
            @endphp
            
            @if(count($images) > 0)
            <div>
                <label class="block text-lg font-medium text-[#3E36AE] mb-2">รูปภาพ ({{ count($images) }} รูป):</label>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($images as $image)
                    @if(isset($image['index']))
                    <div class="relative group">
                        <img src="{{ route('behavioral-report.image', [$report->id, $image['index']]) }}" 
                             alt="{{ $image['original_name'] ?? 'รูปภาพ' }}"
                             class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity"
                             onclick="openImageModal('{{ route('behavioral-report.image', [$report->id, $image['index']]) }}', '{{ $image['original_name'] ?? 'รูปภาพ' }}')">
                        
                        {{-- Overlay with image info --}}
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-end">
                            <div class="w-full bg-black bg-opacity-60 text-white p-2 rounded-b-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                <p class="text-xs truncate">{{ $image['original_name'] ?? 'รูปภาพ' }}</p>
                                @if(isset($image['size']))
                                <p class="text-xs text-gray-300">{{ number_format($image['size'] / 1024, 1) }} KB</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif
            @endif

            {{-- แสดงตำแหน่ง --}}
            @if($report->latitude && $report->longitude)
            <div>
                <label class="block text-lg font-medium text-[#3E36AE] mb-2">ตำแหน่งที่ตั้ง:</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 mb-2">
                        Latitude: {{ number_format($report->latitude, 6) }}, 
                        Longitude: {{ number_format($report->longitude, 6) }}
                    </p>
                    <div id="map" class="w-full h-64 rounded-lg"></div>
                </div>
            </div>
            @endif

            {{-- ข้อมูลเพิ่มเติม --}}
            <div class="border-t pt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">วันที่รายงาน:</span>
                        {{ \Carbon\Carbon::parse($report->created_at)->format('d/m/Y H:i:s') }}
                    </div>
                    <div>
                        <span class="font-medium">รหัสรายงาน:</span>
                        #{{ str_pad($report->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ปุ่มกลับ --}}
    <div class="flex justify-center">
        <button type="button" onclick="history.back()" 
                class="px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3E36AE] touch-manipulation">
            กลับ
        </button>
    </div>
</div>

{{-- Modal สำหรับแสดงรูปขนาดใหญ่ --}}
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden">
    <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white z-10 hover:text-gray-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    <div class="w-full h-full flex flex-col items-center justify-center p-4">
        <img id="modalImage" src="" alt="Full size image" class="max-w-full max-h-full object-contain mb-4">
        <p id="modalImageName" class="text-white text-center"></p>
    </div>
</div>

{{-- เพิ่ม Leaflet สำหรับแผนที่ --}}
@if($report->latitude && $report->longitude)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endif

<script>
// ฟังก์ชันเปิด/ปิด modal รูปภาพ
function openImageModal(imageSrc, imageName) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalImageName').textContent = imageName || 'รูปภาพ';
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // ป้องกันการเลื่อน
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto'; // คืนค่าการเลื่อน
}

// ปิด modal เมื่อกดปุ่ม ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// แสดงแผนที่ถ้ามีตำแหน่ง
@if($report->latitude && $report->longitude)
document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('map', {
        center: [{{ $report->latitude }}, {{ $report->longitude }}],
        zoom: 15,
        zoomControl: true,
        scrollWheelZoom: false,
        doubleClickZoom: false,
        dragging: false
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    // เพิ่ม marker
    const redMarkerIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background-color: #ea4335; width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.3);"></div>',
        iconSize: [24, 24],
        iconAnchor: [12, 12]
    });

    L.marker([{{ $report->latitude }}, {{ $report->longitude }}], {
        icon: redMarkerIcon
    }).addTo(map);
});
@endif
</script>

<style>
.custom-marker { 
    display: flex; 
    align-items: center; 
    justify-content: center; 
}
</style>

@endsection