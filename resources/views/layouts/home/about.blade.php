<button id="infoBtn" class="absolute top-4 right-4 h-8 w-8 flex items-center justify-center rounded-full bg-white text-gray-700 shadow-md hover:shadow-lg transition-shadow">
    <span class="text-lg font-bold">i</span>
</button>
<div id="infoModal" class="modal fixed inset-0 flex items-center justify-center z-50">
    <div class="absolute inset-0 bg-black bg-opacity-30" id="modalOverlay"></div>
    <div class="modal-content bg-white modal-size mx-4 rounded-lg shadow-lg z-50 transform scale-100 transition-transform duration-300">
        
        <div class="flex justify-between items-center pl-4 pr-4 pt-4 relative mt-2 mr-2">
            <button id="closeBtn" class="h-8 w-8 flex items-center justify-center rounded-full text-gray-500 hover:text-gray-700 absolute top-0 right-0 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-2 overflow-y-auto flex flex-col justify-between">
            <h3 class="font-bold text-center mt-auto modal-title text-gray-700">เกี่ยวกับเรา</h3>
        </div>

        <div class="modal-body pl-3 pr-3 pb-3">
            <p class="mb-6 modal-text text-justify" style="text-indent: 2em;">
                ศูนย์บริการสุขภาพแบบครบวงจรภายใต้การกำกับดูแลของสำนักวิชาวิทยาศาสตร์สุขภาพ
                มหาวิทยาลัยแม่ฟ้าหลวงเป็นศูนย์บริการที่เปิดให้บริการแก่นักศึกษาและบุคลากรของมหาวิทยาลัย
                ทุกคนให้ได้รับการดูแลคุณภาพชีวิตและสุขภาพการวิจัยและการให้บริการ ปีงบประมาณ 2568 เตรียมพร้อม
                และพัฒนาสิ่งแวดล้อมในสถานที่ทำงานให้มีความปลอดภัยสะอาดเป็นระเบียบ
                และเพียงพอต่อความต้องการของผู้ใช้บริการอีกทั้งสร้างวัฒนธรรมองค์กรขององค์การให้บริการสุขภาพแบบองค์รวม
                โดยนำหลักธรรมาภิบาลมาบริหารงานอย่างมีคุณธรรม จริยธรรม และคุณภาพอย่างยั่งยืน
            </p>

            <div class="flex justify-center space-x-3 mb-4 border-b border-gray-300 pb-4">
                @php
                $certifications = [
                    ['image' => 'วช2.png', 'alt' => 'Certification 1'],
                    ['image' => 'MFU.png', 'alt' => 'Certification 2'],
                    ['image' => 'สาธาmfu.png', 'alt' => 'Certification 3'],
                    ['image' => 'excellence.png', 'alt' => 'Certification 4'],
                    ['image' => 'ตรากระทรวงสาธารณสุขใหม่.png', 'alt' => 'Certification 5']
                ];
                @endphp
                
                @foreach($certifications as $cert)
                    <img src="/images/{{ $cert['image'] }}" alt="{{ $cert['alt'] }}" class="cert-logo object-contain">
                @endforeach
            </div>

            <div class="mt-2">
                <p class="font-bold text-center section-title text-gray-600">คณะบริหาร</p>

                @php
                $managementTeam = [
                    [
                        'name' => 'อาจารย์ ดร.ฐาปกรณ์ เรือนใจ',
                        'position1' => 'สาขาวิชาสาธารณสุขศาสตร์ และศูนย์ความเป็นเลิศการวิจัยสุขภาพชนชาติพันธุ์',
                        'position2' => 'สำนักวิชาวิทยาศาสตร์สุขภาพ มหาวิทยาลัยแม่ฟ้าหลวง'
                    ],
                    [
                        'name' => 'อาจารย์ ฐิตาพร แก้วบุญชู',
                        'position1' => 'สาขาวิชาสาธารณสุขศาสตร์ สำนักวิชาวิทยาศาสตร์สุขภาพ',
                        'position2' => 'มหาวิทยาลัยแม่ฟ้าหลวง'
                    ],
                    [
                        'name' => 'อาจารย์ ดร.วิลาวัณย์ ไชยอุต',
                        'position1' => 'สาขาวิชากายภาพบำบัด สำนักวิชาการแพทย์บูรณาการ',
                        'position2' => 'มหาวิทยาลัยแม่ฟ้าหลวง'
                    ],
                    [
                        'name' => 'นางสาวฟาติมา ยีหมาด',
                        'position1' => 'สาขาวิชากายภาพบำบัด สำนักวิชาการแพทย์บูรณาการ',
                        'position2' => 'มหาวิทยาลัยแม่ฟ้าหลวง'
                    ],
                    [
                        'name' => 'อาจารย์ ดร.ขวัญตา คีรีมาศทอง',
                        'position1' => 'สำนักวิชาเทคโนโลยีดิจิทัลประยุกต์ และสถาบันนวัตกรรมการเรียนรู้',
                        'position2' => 'มหาวิทยาลัยแม่ฟ้าหลวง'
                    ]
                ];
                @endphp

                @foreach($managementTeam as $member)
                <div class="p-3 text-center">
                    <p class="name-text font-medium">{{ $member['name'] }}</p>
                    <p class="position-text">{{ $member['position1'] }}</p>
                    <p class="position-text">{{ $member['position2'] }}</p>
                </div>
                @endforeach

                <p class="mt-8 font-bold text-center section-title text-gray-600">ที่ปรึกษาโครงการวิจัย</p>

                <div class="p-3 text-center">
                    <p class="name-text font-medium">รองศาสตราจารย์ ดร.ธวัชชัย อภิเดชกุล</p>
                    <p class="position-text">สาขาวิชาสาธารณสุขศาสตร์ และศูนย์ความเป็นเลิศการวิจัยสุขภาพชนชาติพันธุ์</p>
                    <p class="position-text">สำนักวิชาวิทยาศาสตร์สุขภาพ มหาวิทยาลัยแม่ฟ้าหลวง</p>
                </div>
            </div>
        </div>
    </div>
</div>
