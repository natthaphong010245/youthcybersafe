<div class="mb-6">
    <label class="block text-lg font-medium text-[#3E36AE] mb-2">รูปภาพ</label>
    <div id="imagePreviewContainer" class="flex flex-wrap gap-2 mb-3">
        <div id="imagePreview" class="flex flex-wrap gap-2"></div>
        <div id="uploadMoreContainer" class="flex flex-col items-center justify-center w-20 h-20 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer touch-manipulation">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="text-xs text-gray-500 mt-1">อัพรูปเพิ่ม</span>
        </div>
    </div>
    <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="hidden">
</div>

<div id="imagePreviewModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden">
    <button id="closeImagePreviewModal" class="absolute top-4 right-4 text-white z-10 touch-manipulation">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    <div class="w-full h-full flex items-center justify-center p-4">
        <img id="fullSizeImage" src="" alt="Full size image" class="max-w-full max-h-full object-contain">
    </div>
</div>