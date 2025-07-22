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
                    <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">สุขภาพจิต</p>
                </div>
            </div>
        </div>

        <div class="border-b border-gray-200 mb-4"></div>

        <form id="questionnaire-form" method="POST" action="{{ url('/assessment/mental_health/form') }}">
            @csrf

            <div class="text-center mb-4 relative">
                <div class="flex items-center justify-center">
                    <div class="relative">
                        <h1 class="text-xl font-bold text-[#3E36AE] inline-block">ด้านความเครียด</h1>
                    </div>
                </div>
            </div>

            @php
                $stressQuestions = [
                    '1. ฉันรู้สึกยากที่จะสงบจิตใจลงได้',
                    '2. ฉันมีแนวโน้มที่จะตอบสนองเกินเหตุต่อสถานการณ์ต่างๆ',
                    '3. ฉันรู้สึกเสียพลังงานไปมากกว่าการคิดกังวล',
                    '4. ฉันรู้สึกกระวนกระวายใจ',
                    '5. ฉันรู้สึกยากที่จะผ่อนคลายตัวเอง',
                    '6. ฉันรู้สึกทนไม่ได้เวลามีอะไรมาขัดขวางสิ่งที่ฉันกำลังทำอยู่',
                    '7. ฉันรู้สึกค่อนข้างฉุนเฉียวง่าย',
                ];

                $options = [
                    ['value' => 0, 'text' => 'ไม่ตรงกับนักเรียนเลย', 'size' => 'h-5 w-5'],
                    ['value' => 1, 'text' => 'ตรงกับตัวนักเรียนบ้าง หรือเกิดขึ้นเป็นบางครั้ง', 'size' => 'h-6 w-6'],
                    ['value' => 2, 'text' => 'ตรงกับนักเรียนหรือเกิดขึ้นบ่อย', 'size' => 'h-7 w-7'],
                    ['value' => 3, 'text' => 'ตรงกับนักเรียนมาก หรือเกิดขึ้นบ่อยมากที่สุด', 'size' => 'h-8 w-8'],
                ];
            @endphp

            @foreach ($stressQuestions as $index => $question)
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

            <div class="text-center mb-4 relative mt-6">
                <div class="flex items-center justify-center">
                    <div class="relative">
                        <h1 class="text-xl font-bold text-[#3E36AE] inline-block">ภาวะวิตกกังวล</h1>
                    </div>
                </div>
            </div>

            @php
                $anxietyQuestions = [
                    '8. ฉันรู้สึกปากแห้งคอแห้ง',
                    '9. ฉันมีอาการหายใจผิดปกติ (เช่น หายใจเร็วเกินเหตุ หายใจไม่ทันแม้ว่าจะไม่ได้ออกแรง',
                    '10. ฉันรู้สึกว่าร่างกายบางส่วนสั่นผิดปกติ (เช่น มือสั่น)',
                    '11. ฉันรู้สึกกังวลกับเหตุการณ์ ที่อาจทำให้ฉันรู้สึกตื่นกลัวและกระทำบางสิ่งที่น่าอับอาย',
                    '12. ฉันรู้สึกคล้ายจะมีอาการตื่นตระหนก',
                    '13. ฉันมีแนวโน้มที่จะตอบสนองเกินเหตุต่อสถานการณ์ต่างๆฉันรับรู้ถึงการทำงานของหัวใจแม้ในตอนที่ฉันไม่ได้ออกแรง(เช่น รู้สึกว่าหัวใจเต้นเร็วขึ้นหรือเต้นไม่เป็นจังหวะ)',
                    '14. ฉันรู้สึกกลัวโดยไม่มีเหตุผล',
                ];
            @endphp

            @foreach ($anxietyQuestions as $index => $question)
                @php $questionNumber = $index + 8; @endphp
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

            <div class="text-center mb-4 relative mt-6">
                <div class="flex items-center justify-center">
                    <div class="relative">
                        <h1 class="text-xl font-bold text-[#3E36AE] inline-block">ภาวะซึมเศร้า</h1>
                    </div>
                </div>
            </div>

            @php
                $depressionQuestions = [
                    '15. ฉันแทบไม่รู้สึกอะไรดีๆเลย',
                    '16. ฉันพบว่ามันยากที่จะคิดริเริ่มทำสิ่งใดสิ่งหนึ่ง',
                    '17. ฉันรู้สึกไม่มีเป้าหมายในชีวิต',
                    '18. ฉันรู้สึกจิตใจเหงาหงอยเศร้าซึม',
                    '19. ฉันรู้สึกไม่มีความกระตือรือร้นต่อสิ่งใด',
                    '20. ฉันรู้สึกเป็นคนไม่มีคุณค่า',
                    '21. ฉันรู้สึกว่าชีวิตไม่มีความหมาย',
                ];
            @endphp

            @foreach ($depressionQuestions as $index => $question)
                @php $questionNumber = $index + 15; @endphp
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