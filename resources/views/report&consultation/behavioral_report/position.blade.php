<div class="mb-6">
    <label class="block text-lg font-medium text-[#3E36AE] mb-2">ตำแหน่งที่ตั้ง</label>
    <div id="mapContainer" class="w-full h-48 rounded-lg overflow-hidden relative">
        <div id="map" class="w-full h-full"></div>
        <div id="mapLoading" class="absolute inset-0 bg-gray-100 flex flex-col items-center justify-center">
            <svg class="animate-spin h-8 w-8 text-[#3E36AE] mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-sm text-gray-600 text-center px-4">กำลังโหลดแผนที่...</p>
            <p class="text-xs text-gray-500 text-center px-4 mt-1">แตะบนแผนที่เพื่อระบุตำแหน่ง</p>
        </div>
        <div id="locationPrompt" class="absolute inset-0 bg-white bg-opacity-95 flex flex-col items-center justify-center hidden">
            <svg class="h-12 w-12 text-[#3E36AE] mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-sm font-medium text-[#3E36AE] text-center px-4 mb-2">แตะบนแผนที่เพื่อระบุตำแหน่ง</p>
            <p class="text-xs text-gray-500 text-center px-4">จำเป็นต้องระบุตำแหน่งก่อนส่งรายงาน</p>
        </div>
    </div>
    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">
</div>