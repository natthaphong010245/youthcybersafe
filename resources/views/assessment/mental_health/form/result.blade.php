@extends('layouts.assessment.result')
@section('content')
    <div class="bg-white w-full flex-grow rounded-t-[50px] px-6 pt-8 flex flex-col mt-8 pb-10">
        <div class="text-center mb-2 relative">
            <div class="flex items-center justify-center">
                <div class="relative">
                    <h1 class="text-2xl font-bold text-[#3E36AE] inline-block">แบบคัดกรองสุขภาพจิต</h1>
                    <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">ผลการประเมิน</p>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-lg mt-8 flex-grow">
            <button id="prev-arrow"
                class="absolute left-2 top-1/3 transform -translate-y-1/2 z-10 w-10 h-10 bg-white bg-opacity-80 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:bg-opacity-100">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <button id="next-arrow"
                class="absolute right-2 top-1/3 transform -translate-y-1/2 z-10 w-10 h-10 bg-white bg-opacity-80 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:bg-opacity-100">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <div class="swiper-container flex transition-transform duration-300 ease-in-out" id="result-carousel">

                <!-- 1: ด้านความเครียด -->
                <div class="swiper-slide w-full flex-shrink-0 px-2">
                    <div class="bg-white w-full max-w-2xl mx-auto rounded-lg px-4 pt-4 flex flex-col pb-6">
                        <div class="text-center mb-4">
                            <h3 class="text-xl font-bold text-[#3E36AE] mb-2">ด้านความเครียด</h3>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="flex justify-center mb-4">
                                @if ($stressScore <= 7)
                                    <img src="{{ asset('images/mental_health/normal.png') }}" alt="Normal"
                                        class="w-32 h-32 object-contain">
                                @elseif ($stressScore <= 9)
                                    <img src="{{ asset('images/mental_health/mild.png') }}" alt="Mild"
                                        class="w-32 h-32 object-contain">
                                @elseif($stressScore <= 12)
                                    <img src="{{ asset('images/mental_health/moderate.png') }}" alt="Moderate"
                                        class="w-32 h-32 object-contain">
                                @elseif($stressScore <= 16)
                                    <img src="{{ asset('images/mental_health/severe.png') }}" alt="Severe"
                                        class="w-32 h-32 object-contain">
                                @else
                                    <img src="{{ asset('images/mental_health/very_severe.png') }}" alt="Very Severe"
                                        class="w-32 h-32 object-contain">
                                @endif
                            </div>

                            <div class="mb-6 text-center">
                                <p class="text-lg font-medium text-gray-700">คะแนนของคุณ</p>
                                <p class="text-4xl font-bold text-[#3E36AE] mt-2">{{ $stressScore }} / 21</p>
                            </div>

                            <div class="w-full max-w-md bg-gray-100 h-4 rounded-full overflow-hidden mb-2">
                                <div class="bg-[#3E36AE] h-full transition-all duration-500"
                                    style="width: {{ min(($stressScore / 21) * 100, 100) }}%"></div>
                            </div>

                            <p class="text-center text-gray-600 mb-4">
                                คะแนนของคุณคิดเป็น {{ number_format(($stressScore / 21) * 100, 1) }}%
                            </p>

                            <div class="border-t border-gray-300 w-full pt-4">
                                <h2 class="text-lg font-medium text-[#3E36AE] mb-2">ผลการประเมิน</h2>
                                @if ($stressScore <= 7)
                                    <p class="text-green-600 font-medium mb-2 text-center">ระดับปกติ</p>
                                    <p class="text-gray-600 text-sm">ไม่มีความเสี่ยงที่จะเป็นภาวะเครียดนะ
                                        รับมือกับความเครียดในชีวิตประจำวันได้ดีเลย ดูแลตนเองดีมาก ๆ แล้วล่ะ
                                        ดูแลแบบนี้ต่อไปนะ</p>
                                @elseif ($stressScore <= 9)
                                    <p class="text-green-600 font-medium mb-2 text-center">ระดับเล็กน้อย</p>
                                    <p class="text-gray-600 text-sm">เริ่มเสี่ยงมีภาวะเครียดเล็กน้อยถึงปานกลาง
                                        ลองฝึกดูแลตนเองด้วยการผ่อนคลายให้มากขึ้นทั้งทางกายและทางใจ วันนี้ชมตัวเองหรือยังนะ?
                                        ถ้ายัง...ลองบอกตัวเองดูมั้ย ว่าเก่งมากแค่ไหนแล้ววันนี้...
                                        แล้วถ้าอยากลองพูดคุยกับผู้เชี่ยวชาญทางด้านจิตใจก็ทำได้นะ
                                        ก่อนไปอย่าลืมดูคะแนนอีกสองด้านด้วยล่ะว่าสูงมั้ย</p>
                                @elseif($stressScore <= 12)
                                    <p class="text-yellow-600 font-medium mb-2 text-center">ระดับปานกลาง</p>
                                    <p class="text-gray-600 text-sm">เริ่มเสี่ยงมีภาวะเครียดเล็กน้อยถึงปานกลาง
                                        ลองฝึกดูแลตนเองด้วยการผ่อนคลายให้มากขึ้นทั้งทางกายและทางใจ วันนี้ชมตัวเองหรือยังนะ?
                                        ถ้ายัง...ลองบอกตัวเองดูมั้ย ว่าเก่งมากแค่ไหนแล้ววันนี้...
                                        แล้วถ้าอยากลองพูดคุยกับผู้เชี่ยวชาญทางด้านจิตใจก็ทำได้นะ
                                        ก่อนไปอย่าลืมดูคะแนนอีกสองด้านด้วยล่ะว่าสูงมั้ย</p>
                                @elseif($stressScore <= 16)
                                    <p class="text-orange-600 font-medium mb-2 text-center">ระดับรุนแรง</p>
                                    <p class="text-gray-600 text-sm">เสี่ยงมีภาวะเครียดสูงถึงสูง
                                        มาก หากพบว่าไม่สามารถใช้ชีวิตประจำวันได้อย่างปกติแบบเดิม ไม่อยากไปโรงเรียน
                                        ไม่อยากคุยกับใคร ไม่อยากทำการบ้าน
                                        ควรพบผู้เชี่ยวชาญทางด้านจิตใจเพื่อรับการพูดคุยบำบัด หรือทานยาได้นะ
                                        อย่าลืมดูล่ะว่าคะแนนอีกสองด้านสูงเหมือนกันหรือเปล่า
                                    </p>
                                @else
                                    <p class="text-red-600 font-medium mb-2 text-center">ระดับรุนแรงมาก</p>
                                    <p class="text-gray-600 text-sm">เสี่ยงมีภาวะเครียดสูงถึงสูง
                                        มาก หากพบว่าไม่สามารถใช้ชีวิตประจำวันได้อย่างปกติแบบเดิม ไม่อยากไปโรงเรียน
                                        ไม่อยากคุยกับใคร ไม่อยากทำการบ้าน
                                        ควรพบผู้เชี่ยวชาญทางด้านจิตใจเพื่อรับการพูดคุยบำบัด หรือทานยาได้นะ
                                        อย่าลืมดูล่ะว่าคะแนนอีกสองด้านสูงเหมือนกันหรือเปล่า
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2: ภาวะวิตกกังวล -->
                <div class="swiper-slide w-full flex-shrink-0 px-2">
                    <div class="bg-white w-full max-w-2xl mx-auto rounded-lg px-4 pt-4 flex flex-col pb-6">
                        <div class="text-center mb-4">
                            <h3 class="text-xl font-bold text-[#3E36AE] mb-2">ภาวะวิตกกังวล</h3>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="flex justify-center mb-4">
                                @if ($anxietyScore <= 3)
                                    <img src="{{ asset('images/mental_health/normal.png') }}" alt="Normal"
                                        class="w-32 h-32 object-contain">
                                @elseif ($anxietyScore <= 5)
                                    <img src="{{ asset('images/mental_health/mild.png') }}" alt="Mild"
                                        class="w-32 h-32 object-contain">
                                @elseif($anxietyScore <= 7)
                                    <img src="{{ asset('images/mental_health/moderate.png') }}" alt="Moderate"
                                        class="w-32 h-32 object-contain">
                                @elseif($anxietyScore <= 9)
                                    <img src="{{ asset('images/mental_health/severe.png') }}" alt="Severe"
                                        class="w-32 h-32 object-contain">
                                @else
                                    <img src="{{ asset('images/mental_health/very_severe.png') }}" alt="Very Severe"
                                        class="w-32 h-32 object-contain">
                                @endif
                            </div>

                            <div class="mb-6 text-center">
                                <p class="text-lg font-medium text-gray-700">คะแนนของคุณ</p>
                                <p class="text-4xl font-bold text-[#3E36AE] mt-2">{{ $anxietyScore }} / 21</p>
                            </div>

                            <div class="w-full max-w-md bg-gray-100 h-4 rounded-full overflow-hidden mb-2">
                                <div class="bg-[#3E36AE] h-full transition-all duration-500"
                                    style="width: {{ min(($anxietyScore / 21) * 100, 100) }}%"></div>
                            </div>

                            <p class="text-center text-gray-600 mb-4">
                                คะแนนของคุณคิดเป็น {{ number_format(($anxietyScore / 21) * 100, 1) }}%
                            </p>

                            <div class="border-t border-gray-300 w-full pt-4">
                                <h2 class="text-lg font-medium text-[#3E36AE] mb-2">ผลการประเมิน</h2>
                                @if ($anxietyScore <= 3)
                                    <p class="text-green-600 font-medium mb-2 text-center">ระดับปกติ</p>
                                    <p class="text-gray-600 text-sm">ไม่มีความเสี่ยงที่จะเป็นภาวะวิตกกังวลนะ ดูแลตนเองดีมาก ๆ แล้วล่ะ ดูแลแบบนี้ต่อไปนะ</p>
                                @elseif ($anxietyScore <= 5)
                                    <p class="text-green-600 font-medium mb-2 text-center">ระดับเล็กน้อย</p>
                                    <p class="text-gray-600 text-sm">เริ่มเสี่ยงมีภาวะวิตกกังวล อาจจะลองดูแลตนเองให้มากขึ้น ฝึกการหายใจ 4-7-8 ก็ช่วยได้นะในเรื่องความกังวล หรือเทคนิค 5-4-3-2-1 เพื่อช่วยดึงตัวเองออกจากความกังวล อย่าลืมนอนหลับพักผ่อนและดื่มน้ำเยอะ ๆ ล่ะ ถ้าหากต้องการพูดคุยสามารถปรึกษาผู้เชี่ยวชาญทางด้านจิตใจได้เช่นกัน อีกอย่างคือพิจารณาคะแนนอีกสองด้านด้วยนะว่าสูงหรือไม่</p>
                                @elseif($anxietyScore <= 7)
                                    <p class="text-yellow-600 font-medium mb-2 text-center">ระดับปานกลาง</p>
                                    <p class="text-gray-600 text-sm">เริ่มเสี่ยงมีภาวะวิตกกังวล อาจจะลองดูแลตนเองให้มากขึ้น ฝึกการหายใจ 4-7-8 ก็ช่วยได้นะในเรื่องความกังวล หรือเทคนิค 5-4-3-2-1 เพื่อช่วยดึงตัวเองออกจากความกังวล อย่าลืมนอนหลับพักผ่อนและดื่มน้ำเยอะ ๆ ล่ะ ถ้าหากต้องการพูดคุยสามารถปรึกษาผู้เชี่ยวชาญทางด้านจิตใจได้เช่นกัน อีกอย่างคือพิจารณาคะแนนอีกสองด้านด้วยนะว่าสูงหรือไม่</p>
                                @elseif($anxietyScore <= 9)
                                    <p class="text-orange-600 font-medium mb-2 text-center">ระดับรุนแรง</p>
                                    <p class="text-gray-600 text-sm">เสี่ยงมีภาวะวิตกกังวลสูงถึงสูงมาก หากพบว่าไม่สามารถใช้ชีวิตประจำวันแบบเดิมได้ ไม่อยากพูดคุยกับใคร ไม่อยากไปโรงเรียนเนื่องจากมีความกลัวหรือความกังวลบางอย่าง
ควรพบผู้เชี่ยวชาญทางด้านจิตใจเพื่อรับการพูดคุยบำบัด หรือทานยาได้นะ อย่าลืมดูล่ะว่าคะแนนอีกสองด้านสูงเหมือนกันหรือเปล่า
</p>
                                @else
                                    <p class="text-red-600 font-medium mb-2 text-center">ระดับรุนแรงมาก</p>
                                    <p class="text-gray-600 text-sm">เสี่ยงมีภาวะวิตกกังวลสูงถึงสูงมาก หากพบว่าไม่สามารถใช้ชีวิตประจำวันแบบเดิมได้ ไม่อยากพูดคุยกับใคร ไม่อยากไปโรงเรียนเนื่องจากมีความกลัวหรือความกังวลบางอย่าง
ควรพบผู้เชี่ยวชาญทางด้านจิตใจเพื่อรับการพูดคุยบำบัด หรือทานยาได้นะ อย่าลืมดูล่ะว่าคะแนนอีกสองด้านสูงเหมือนกันหรือเปล่า
</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3: ภาวะซึมเศร้า -->
                <div class="swiper-slide w-full flex-shrink-0 px-2">
                    <div class="bg-white w-full max-w-2xl mx-auto rounded-lg px-4 pt-4 flex flex-col pb-6">
                        <div class="text-center mb-4">
                            <h3 class="text-xl font-bold text-[#3E36AE] mb-2">ภาวะซึมเศร้า</h3>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="flex justify-center mb-4">
                                @if ($depressionScore <= 4)
                                    <img src="{{ asset('images/mental_health/normal.png') }}" alt="Normal"
                                        class="w-32 h-32 object-contain">
                                @elseif ($depressionScore <= 6)
                                    <img src="{{ asset('images/mental_health/mild.png') }}" alt="Mild"
                                        class="w-32 h-32 object-contain">
                                @elseif($depressionScore <= 10)
                                    <img src="{{ asset('images/mental_health/moderate.png') }}" alt="Moderate"
                                        class="w-32 h-32 object-contain">
                                @elseif($depressionScore <= 13)
                                    <img src="{{ asset('images/mental_health/severe.png') }}" alt="Severe"
                                        class="w-32 h-32 object-contain">
                                @else
                                    <img src="{{ asset('images/mental_health/very_severe.png') }}" alt="Very Severe"
                                        class="w-32 h-32 object-contain">
                                @endif
                            </div>

                            <div class="mb-6 text-center">
                                <p class="text-lg font-medium text-gray-700">คะแนนของคุณ</p>
                                <p class="text-4xl font-bold text-[#3E36AE] mt-2">{{ $depressionScore }} / 21</p>
                            </div>

                            <div class="w-full max-w-md bg-gray-100 h-4 rounded-full overflow-hidden mb-2">
                                <div class="bg-[#3E36AE] h-full transition-all duration-500"
                                    style="width: {{ min(($depressionScore / 21) * 100, 100) }}%"></div>
                            </div>

                            <p class="text-center text-gray-600 mb-4">
                                คะแนนของคุณคิดเป็น {{ number_format(($depressionScore / 21) * 100, 1) }}%
                            </p>

                            <div class="border-t border-gray-300 w-full pt-4">
                                <h2 class="text-lg font-medium text-[#3E36AE] mb-2">ผลการประเมิน</h2>
                                @if ($depressionScore <= 4)
                                    <p class="text-green-600 font-medium mb-2 text-center">ระดับปกติ</p>
                                    <p class="text-gray-600 text-sm">ไม่มีความเสี่ยงที่จะเป็นภาวะซึมเศร้านะ ดูแลตนเองดีมาก ๆ แล้วล่ะ ดูแลแบบนี้ต่อไปนะ</p>
                                @elseif ($depressionScore <= 6)
                                    <p class="text-green-600 font-medium mb-2 text-center">ระดับเล็กน้อย</p>
                                    <p class="text-gray-600 text-sm">เริ่มมีความเสี่ยงเล็กน้อยถึงปานกลางที่จะมีภาวะซึมเศร้า อาจจะลองพิจารณาการดูแลตนเองอย่างใส่ใจมากขึ้น ทั้งการทานอาหาร การนอน การออกกำลังกายอย่างสม่ำเสมอ หากต้องการการพูดคุยที่เป็นประโยชน์สามารถปรึกษาผู้เชี่ยวชาญทางด้านจิตใจได้ อย่าลืมดูคะแนนอีกสองด้านล่ะว่าได้เท่าไร</p>
                                @elseif($depressionScore <= 10)
                                    <p class="text-yellow-600 font-medium mb-2 text-center">ระดับปานกลาง</p>
                                    <p class="text-gray-600 text-sm">เริ่มมีความเสี่ยงเล็กน้อยถึงปานกลางที่จะมีภาวะซึมเศร้า อาจจะลองพิจารณาการดูแลตนเองอย่างใส่ใจมากขึ้น ทั้งการทานอาหาร การนอน การออกกำลังกายอย่างสม่ำเสมอ หากต้องการการพูดคุยที่เป็นประโยชน์สามารถปรึกษาผู้เชี่ยวชาญทางด้านจิตใจได้ อย่าลืมดูคะแนนอีกสองด้านล่ะว่าได้เท่าไร</p>
                                @elseif($depressionScore <= 13)
                                    <p class="text-orange-600 font-medium mb-2 text-center">ระดับรุนแรง</p>
                                    <p class="text-gray-600 text-sm">เสี่ยงมีภาวะซึมเศร้าสูงถึงสูงมาก หากพบว่าไม่สามารถใช้ชีวิตประจำวันได้อย่างปกติ การนอนหลับ และการทานอาหารเปลี่ยนไป ไม่อยากพบใคร ไม่อยากไปโรงเรียนเนื่องจากไม่มีแรงจูงใจ ควรพบผู้เชี่ยวชาญทางด้านจิตใจเพื่อรับการพูดคุยบำบัด หรือทานยาได้นะ อย่าลืมดูล่ะว่าคะแนนอีกสองด้านสูงเหมือนกันหรือเปล่า</p>
                                @else
                                    <p class="text-red-600 font-medium mb-2 text-center">ระดับรุนแรงมาก</p>
                                    <p class="text-gray-600 text-sm">เสี่ยงมีภาวะซึมเศร้าสูงถึงสูงมาก หากพบว่าไม่สามารถใช้ชีวิตประจำวันได้อย่างปกติ การนอนหลับ และการทานอาหารเปลี่ยนไป ไม่อยากพบใคร ไม่อยากไปโรงเรียนเนื่องจากไม่มีแรงจูงใจ ควรพบผู้เชี่ยวชาญทางด้านจิตใจเพื่อรับการพูดคุยบำบัด หรือทานยาได้นะ อย่าลืมดูล่ะว่าคะแนนอีกสองด้านสูงเหมือนกันหรือเปล่า</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-center mb-6">
            <div class="flex space-x-2">
                <button class="dot w-3 h-3 rounded-full bg-[#3E36AE] transition-all duration-300" data-slide="0"></button>
                <button class="dot w-3 h-3 rounded-full bg-gray-300 transition-all duration-300" data-slide="1"></button>
                <button class="dot w-3 h-3 rounded-full bg-gray-300 transition-all duration-300" data-slide="2"></button>
            </div>
        </div>

        <div class="border-b border-gray-300"></div>

        <div class="flex justify-center mt-6">
            <a href="{{ route('main') }}"
                class="text-lg px-6 py-2 rounded-xl text-white font-medium shadow-md bg-[#c0c0c0] transition-all duration-300 hover:bg-gray-400">
                หน้าหลัก
            </a>
        </div>
    </div>

    @include('layouts.assessment.script')
@endsection
