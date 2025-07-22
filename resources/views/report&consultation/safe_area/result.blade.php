<div id="success-notification" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4 text-center transform scale-50 transition-transform duration-300">
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-[#3E36AE] mb-2">พื้นที่ปลอดภัย</h3>
            <p class="text-xl text-[#3E36AE]">ขอบคุณที่แชร์ประสบการณ์</p>
        </div>

        <img src="{{ asset('images/material/school_girl.png') }}" alt="School Girl Character" class="w-32 h-auto rounded-full mx-auto mb-6 object-cover">

        <div class="mb-6 space-y-1">
            <p class="text-xl text-[#3E36AE] leading-relaxed">เราพร้อมที่จะอยู่เคียงข้างคุณเสมอ</p>
            <p class="text-xl text-[#3E36AE] leading-relaxed">ข้อมูลของคุณจะถูกเก็บไว้เป็นความลับ</p>
        </div>

        <button id="go-home-btn" class="ml-6 mr-6 bg-[#929AFF] text-white text-lg py-2 px-4 rounded-xl transition-colors hover:bg-[#7B85FF] font-medium">
            หน้าหลัก
        </button>
    </div>
</div>

@include('layouts.report&consultation.safe_area.result')

