{{-- resources/views/report&consultation/behavioral_report/record.blade.php --}}
<div class="mb-6">
    <label class="block text-lg font-medium text-[#3E36AE] mb-2">บันทึกเสียง</label>
    <div class="flex items-center space-x-3">
        <button type="button" id="recordButton" class="w-12 h-12 bg-[#7F77E0] rounded-xl flex items-center justify-center touch-manipulation">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
            </svg>
        </button>
        <div id="audioPlayer" class="flex-1">
            <audio id="recordedAudio" controls class="w-full h-10">
                <source src="" type="audio/mpeg">
            </audio>
        </div>
    </div>
    <input type="hidden" name="audio_recording" id="audio_recording">
</div>