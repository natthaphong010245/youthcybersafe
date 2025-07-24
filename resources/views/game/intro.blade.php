<div id="intro-modal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
        <h3 class="text-xl font-bold text-indigo-800 mb-2">{{ $title }}</h3>
        <img src="{{ asset('images/material/school_girl.png') }}" alt="School Girl Character"
            class="w-32 h-auto rounded-full mx-auto mb-4 object-cover">
        <h3 class="text-xl font-bold text-indigo-800 mb-1">เกมที่ {{ $gameNumber }}</h3>
        <p class="text-lg text-indigo-800 mb-4 {{ isset($descriptionClass) ? $descriptionClass : '' }}">{{ $description }}</p>
        <p class="text-indigo-800 text-lg mb-1 font-medium">{{ $actionText ?? 'เริ่มความท้าทายกันเลย' }}</p>
        <button id="start-game-btn" class="bg-[#929AFF] text-white text-base py-1 px-6 rounded-lg transition-colors {{ isset($buttonClass) ? $buttonClass : '' }}">
            เริ่ม
        </button>
    </div>
</div>