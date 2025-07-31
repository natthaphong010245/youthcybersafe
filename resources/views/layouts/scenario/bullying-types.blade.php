<!-- Completion Modal (ปรับให้เหมือนภาพ) -->
<div id="completionModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="completion-content bg-white rounded-2xl w-[90%] max-w-md shadow-lg">
        <!-- Logo -->
        <div class="text-center pt-6">
            <img src="/images/logo.png" alt="Logo" class="mx-auto h-[100px]">
        </div>

        <!-- Title -->
        <div class="text-center mt-2 mb-4">
            <h2 class="text-2xl font-bold text-[#3E36AE]">วิธีรับมือ</h2>
        </div>

        <!-- วิธีการรับมือกลั่นแกล้ง -->
        <div class="bg-[#3E36AE] text-white rounded-xl mx-6 px-6 py-6 space-y-5 text-center text-sm font-medium">
            <div>
                <p class="text-lg font-extrabold">STOP</p>
                <p class="text-sm">หยุดการกระทำ นิ่งเฉยไม่ตอบโต้</p>
            </div>
            <div>
                <p class="text-lg font-extrabold">REMOVE</p>
                <p class="text-sm">ลบภาพที่เป็นการระรานออกทันที</p>
            </div>
            <div>
                <p class="text-lg font-extrabold">BE STRONG</p>
                <p class="text-sm">เข้มแข็ง อดทน ไม่ให้คุณค่ากับคนหรือคำพูดที่ทำร้ายเรา</p>
            </div>
            <div>
                <p class="text-lg font-extrabold">BLOCK</p>
                <p class="text-sm">ปิดกั้นพวกเขา</p>
            </div>
            <div>
                <p class="text-lg font-extrabold">TELL</p>
                <p class="text-sm">บอกบุคคลที่ไว้ใจได้</p>
            </div>
        </div>


        <!-- Footer -->
        <div class="text-center mt-6 mb-4">
            <p class="text-indigo-800 text-lg mb-1 mt-6 text-center">สิ้นสุดความท้าทาย</p>
        </div>

        <div class="flex gap-6 justify-center mt-2 pb-6">
           <button onclick="goToMainScenarios()" class="bg-gray-400 text-white font-medium text-md py-1 px-4 rounded-lg transition-colors hover:bg-gray-500">
            ออก
            </button>
            <button onclick="restartFromFirstScenario()" class="bg-[#929AFF] text-white font-medium text-md py-1 px-4 rounded-lg transition-colors hover:bg-[#7B85FF]">
            เริ่มใหม่
            </button>
        </div>
    </div>
</div>
