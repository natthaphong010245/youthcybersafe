@extends('layouts.assessment.form')

@php
    $mainUrl = '/main';
@endphp

@section('content')
    <div class="bg-white w-full flex-grow rounded-t-[50px] px-6 pt-8 flex flex-col mt-8 pb-20">
        <div class="text-center mb-10 relative">
            <div class="flex items-center justify-center">
                <div class="relative">
                    <h1 class="text-2xl font-bold text-[#3E36AE] inline-block">แบบคัดกรองพฤติกรรม</h1>
                    <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">ประสบการณ์การรังแกภาพรวม</p>
                </div>
            </div>
        </div>

        <div class="border-b border-gray-200 mb-6"></div>

        <form id="questionnaire-form" method="POST" action="{{ url('/assessment/cyberbullying/overview/form') }}">
            @csrf

            @php
                $questions = [
                    '1.โพสต์ แชร์ หรือ ส่ง สิ่งที่หยาบคายหรือน่าอับอายแก่ผู้อื่นผ่านทางอินเทอร์เน็ต',
                    '2.ส่ง หรือ ส่งต่อ ข้อความที่อาจทำร้ายจิตใจผู้อื่นผ่านทางอินเทอร์เน็ต',
                    '3.โพสต์ แชร์ หรือ ส่ง รูปภาพหรือวีดิโอที่น่าอับอายของผู้อื่น โดยที่ผู้นั้นไม่อยากให้คนอื่นเห็น',
                    '4.โพสต์ แชร์ หรือ ส่ง ความเห็นที่อาจทำร้ายจิตใจผู้อื่นเกี่ยวกับรูปภาพหรือวีดิโอของผู้อื่นทางอินเทอร์เน็ต (เช่น ล้อเลียนเจ้าของภาพ)',
                    '5.กีดกันหรือตัดผู้อื่นออกจากกลุ่มในอินเทอร์เน็ต (เช่น บล็อกออกจากกลุ่มแชท หรือ ตั้งกลุ่มแยกออกมาใหม่)',
                    '6.โพสต์ แชร์ หรือ ส่งต่อ ข้อมูลส่วนตัวของผู้อื่น ที่ผู้นั้นไม่ได้ต้องการให้คนอื่นรู้',
                    '7.ใช้อีเมล์หรือการส่งข้อความเพื่อกระจายข่าวลือหรือนินทาผู้อื่น',
                    '8.ส่งสิ่งที่มีเนื้อหาทางเพศ (เช่น ภาพ วีดีโอ หรือมุขตลก ให้ผู้อื่นในอินเทอร์เน็ต หรือขอสิ่งที่มีเนื้อหาเหล่านั้นจากผู้อื่น เพื่อแกล้งเขา หรือเพื่อให้เขารู้สึกอับอาย)',
                    '9.ปลอมตัวภายในอินเทอร์เน็ตเป็นคนที่คุณรู้จัก (เช่น ตั้งชื่อ username เป็นชื่อเพื่อน)',
                    '10.เคยพบว่ามีผู้อื่น โพสต์ แชร์ หรือส่ง สิ่งที่หยาบคาย หรือ ทำให้คุณอับอายภายในอินเทอร์เน็ต',
                    '11.เคยได้รับข้อความที่ทำให้เสียใจผ่านทางอินเตอร์เน็ต',
                    '12.เคยพบว่ามี รูปภาพหรือวีดีโอ ที่ทำให้คุณอับอายหรือไม่อยากให้คนอื่นเห็น ถูกโพสต์ แชร์ หรือกระจายอยู่ภายในอินเทอร์เน็ต',
                    '13.เคยได้รับความคิดเห็นที่ทำให้คุณเสียใจ เกี่ยวกับรูปภาพหรือวีดีโอของคุณภายในอินเทอร์เน็ต',
                    '14.เคยถูกกีดกันหรือตัดออกจากกลุ่มในอินเทอร์เน็ตโดยเจตนา (เช่น ถูกบล็อกออกจากกลุ่มแชท หรือเพื่อนตั้งห้องแชทใหม่โดยไม่เชิญคุณเข้า)',
                    '15.เคยพบว่ามีการโพสต์ แชร์ หรือส่งต่อ ข้อมูลส่วนตัวที่คุณไม่ได้อยากเปิดเผย',
                    '16.เคยพบว่าถูกนินทาหรือถูกกระจายข่าวลือเกี่ยวกับคุณในอินเทอร์เน็ต',
                    '17.เคยได้รับ หรือเคยถูกขอ สิ่งที่มีเนื้อหาทางเพศ (เช่น ภาพ วีดีโอ หรือมุขตลก) จากใครบางคนในอินเทอร์เน็ต ที่พยายามทำให้คุณอับอาย',
                    '18.เคยพบว่ามีคนอื่นปลอมตัวเป็นคุณในอินเทอร์เน็ต',
                ];

                $options = [
                    ['value' => 0, 'text' => 'ไม่เลย', 'size' => 'h-5 w-5'],
                    ['value' => 1, 'text' => 'เล็กน้อย', 'size' => 'h-6 w-6'],
                    ['value' => 2, 'text' => 'ปานกลาง', 'size' => 'h-7 w-7'],
                    ['value' => 3, 'text' => 'มากพอควร', 'size' => 'h-8 w-8'],
                    ['value' => 4, 'text' => 'มากที่สุด', 'size' => 'h-9 w-9'],
                ];
            @endphp

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <strong>โปรดตรวจสอบข้อมูล!</strong> มีข้อผิดพลาดในการส่งแบบฟอร์ม.
                </div>
            @endif

            @foreach ($questions as $index => $question)
                @php $questionNumber = $index + 1; @endphp
                <div class="mb-4 question-container" id="question-container-{{ $questionNumber }}">
                    <p class="text-gray-400 mb-4 question-title" id="question-title-{{ $questionNumber }}">
                        {{ $question }}</p>
                    <div class="flex justify-between">
                        @foreach ($options as $option)
                            <label class="flex flex-col items-center w-16 option-container cursor-pointer"
                                data-question="{{ $questionNumber }}" data-value="{{ $option['value'] }}">
                                <div class="flex items-center justify-center h-8">
                                    <input type="radio" name="question{{ $questionNumber }}"
                                        value="{{ $option['value'] }}" 
                                        class="{{ $option['size'] }} border-2 border-gray-300 rounded-full bg-white cursor-pointer transition-all duration-200 radio-option"
                                        style="appearance: none; -webkit-appearance: none; -moz-appearance: none;"
                                        data-question="{{ $questionNumber }}"
                                        onchange="this.style.backgroundColor='#3E36AE'; this.style.borderColor='#3E36AE'; this.style.boxShadow='inset 0 0 0 2px white';"
                                        onmouseover="if(!this.checked) this.style.borderColor='#3E36AE';"
                                        onmouseout="if(!this.checked) this.style.borderColor='#d1d5db';">
                                </div>
                                <span class="text-xs text-gray-400 mt-1 option-text text-center">{{ $option['text'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="border-b border-gray-200 mt-6"></div>
                </div>
            @endforeach

            <div class="flex justify-center mt-10 mb-5">
                <button type="submit"
                    class="submit-btn text-lg px-16 py-3 rounded-xl text-white font-medium shadow-md bg-[#929AFF]">
                    ส่งคำตอบ
                </button>
            </div>
        </form>
    </div>
    @include('layouts.assessment.percent')
    @include('layouts.assessment.script')
@endsection