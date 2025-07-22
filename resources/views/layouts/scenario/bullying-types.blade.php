
<div id="completionModal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="completion-content modal-content">
        <div class="text-center p-8 pb-4">
            <div class="celebration-icon">🎉</div>
            <h1 class="text-3xl font-bold text-[#3E36AE] mb-4">ยินดีด้วย!</h1>
            <h2 class="text-xl font-semibold text-[#5A63D7] mb-2">นี้คือบทสรุปของไซเบอร์บูลลี่</h2>
            <p class="text-[#6B7280] text-base">ตอนนี้คุณรู้จักรูปแบบของไซเบอร์บูลลี่และวิธีรับมือแล้ว</p>
        </div>

        <div class="px-8 pb-8">
            <h3 class="text-xl font-bold text-[#3E36AE] mb-6 text-center">รูปแบบของไซเบอร์บูลลี่ที่พบบ่อย</h3>
            <div class="scroll-container">
                @php
                    $bullyingTypes = [
                        ['title' => 'การคุกคาม (Harassment)', 'description' => 'การส่งข้อความที่หยาบคาย น่ารังเกียจ หรือดูหมิ่นซ้ำๆ'],
                        ['title' => 'การใส่ร้าย (Denigration/Dissing)', 'description' => 'การเผยแพร่ข้อมูลเท็จหรือข่าวลือเกี่ยวกับผู้อื่นเพื่อทำลายชื่อเสียง'],
                        ['title' => 'การแอบอ้างตัวตน (Impersonation)', 'description' => 'การแฮกบัญชีออนไลน์ของผู้อื่นและใช้ในทางที่เสียหาย'],
                        ['title' => 'การกีดกัน (Exclusion)', 'description' => 'การตั้งใจไม่รวมใครบางคนออกจากกลุ่มเพื่อนหรือกิจกรรมออนไลน์'],
                        ['title' => 'การเผยแพร่ความลับ (Outing)', 'description' => 'การเปิดเผยข้อมูลส่วนตัวหรือความลับของผู้อื่นโดยไม่ได้รับอนุญาต'],
                        ['title' => 'การหลอกลวง (Trickery)', 'description' => 'การใช้อุบายเพื่อให้เหยื่อเปิดเผยข้อมูลส่วนตัวแล้วนำไปเผยแพร่'],
                        ['title' => 'การข่มขู่คุกคาม (Threatening/Intimidation)', 'description' => 'การส่งข้อความที่ขู่ทำร้ายหรือคุกคาม'],
                        ['title' => 'การสร้างกลุ่มเพื่อโจมตี (Flaming)', 'description' => 'การสร้างกลุ่มหรือเพจเพื่อโจมตีหรือประจานบุคคล'],
                        ['title' => 'การตัดต่อภาพหรือวิดีโอ (Photo/Video Manipulation)', 'description' => 'การตัดต่อภาพหรือวิดีโอของผู้อื่นให้เกิดความเสียหาย'],
                        ['title' => 'การเผยแพร่คลิปวิดีโอหรือรูปภาพที่ส่อไปในทางเสียหาย', 'description' => 'การนำคลิปหรือรูปภาพที่ทำให้ผู้อื่นอับอายหรือเสียหายไปเผยแพร่'],
                    ];
                @endphp

                @foreach($bullyingTypes as $index => $type)
                    <div class="bullying-type">
                        <div class="bullying-title">{{ $index + 1 }}. {{ $type['title'] }}</div>
                        <div class="bullying-description">{{ $type['description'] }}</div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 p-6 bg-gradient-to-r from-[#E5C8F6] to-[#D1D5FF] rounded-2xl">
                <h4 class="font-bold text-[#3E36AE] text-lg mb-3 text-center">💡 จำไว้เสมอ</h4>
                <div class="text-[#5A63D7] text-center space-y-2">
                    <p><strong>STOP</strong> - หยุดและไม่ตอบโต้</p>
                    <p><strong>BLOCK</strong> - ปิดกั้นผู้กระทำผิด</p>
                    <p><strong>TELL</strong> - บอกผู้ใหญ่ที่ไว้ใจ</p>
                    <p><strong>BE STRONG</strong> - เข้มแข็งและมั่นใจในตัวเอง</p>
                </div>
            </div>

            <div class="text-center mt-8">
                <button onclick="goToHome()" class="completion-button">กลับสู่หน้าหลัก</button>
            </div>
        </div>
    </div>
</div>