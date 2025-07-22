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
                                    <div class="text-8xl">😊</div>
                                @elseif ($stressScore <= 9)
                                    <div class="text-8xl">🙂</div>
                                @elseif($stressScore <= 12)
                                    <div class="text-8xl">😐</div>
                                @elseif($stressScore <= 16)
                                    <div class="text-8xl">😟</div>
                                @else
                                    <div class="text-8xl">😥</div>
                                @endif
                            </div>
                            
                            <div class="mb-6 text-center">
                                <p class="text-lg font-medium text-gray-700">คะแนนของคุณ</p>
                                <p class="text-4xl font-bold text-[#3E36AE] mt-2">{{ $stressScore }} / 21</p>
                            </div>

                            <div class="w-full max-w-md bg-gray-100 h-4 rounded-full overflow-hidden mb-2">
                                <div class="bg-[#3E36AE] h-full transition-all duration-500" style="width: {{ min(($stressScore / 21) * 100, 100) }}%"></div>
                            </div>

                            <p class="text-center text-gray-600 mb-4">
                                คะแนนของคุณคิดเป็น {{ number_format(($stressScore / 21) * 100, 1) }}%
                            </p>

                            <div class="border-t border-gray-300 w-full pt-4">
                                <h2 class="text-lg font-medium text-[#3E36AE] mb-2">ผลการประเมิน</h2>
                                @if ($stressScore <= 7)
                                    <p class="text-green-600 font-medium mb-2 text-center">ระดับปกติ</p>
                                    <p class="text-gray-600 text-sm">ภาวะความเครียดของคุณอยู่ในระดับปกติ ควรรักษาสภาวะที่ดีนี้ไว้</p>
                                @elseif ($stressScore <= 9)
                                    <p class="text-green-600 font-medium mb-2 text-center">ระดับเล็กน้อย</p>
                                    <p class="text-gray-600 text-sm">คุณมีภาวะความเครียดในระดับเล็กน้อย ควรหาเวลาผ่อนคลายและออกกำลังกาย</p>
                                @elseif($stressScore <= 12)
                                    <p class="text-yellow-600 font-medium mb-2 text-center">ระดับปานกลาง</p>
                                    <p class="text-gray-600 text-sm">คุณมีภาวะความเครียดในระดับปานกลาง ควรฝึกการจัดการความเครียดและหาเทคนิคผ่อนคลาย</p>
                                @elseif($stressScore <= 16)
                                    <p class="text-orange-600 font-medium mb-2 text-center">ระดับรุนแรง</p>
                                    <p class="text-gray-600 text-sm">คุณมีภาวะความเครียดในระดับรุนแรง ควรปรึกษาผู้เชี่ยวชาญและหาวิธีจัดการความเครียด</p>
                                @else
                                    <p class="text-red-600 font-medium mb-2 text-center">ระดับรุนแรงมาก</p>
                                    <p class="text-gray-600 text-sm">คุณมีภาวะความเครียดในระดับรุนแรงมาก ควรพบผู้เชี่ยวชาญโดยเร็วเพื่อรับการช่วยเหลือ</p>
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
                                    <div class="text-8xl">😊</div>
                                @elseif ($anxietyScore <= 5)
                                    <div class="text-8xl">🙂</div>
                                @elseif($anxietyScore <= 7)
                                    <div class="text-8xl">😐</div>
                                @elseif($anxietyScore <= 9)
                                    <div class="text-8xl">😟</div>
                                @else
                                    <div class="text-8xl">😥</div>
                                @endif
                            </div>
                            
                            <div class="mb-6 text-center">
                                <p class="text-lg font-medium text-gray-700">คะแนนของคุณ</p>
                                <p class="text-4xl font-bold text-[#3E36AE] mt-2">{{ $anxietyScore }} / 21</p>
                            </div>

                            <div class="w-full max-w-md bg-gray-100 h-4 rounded-full overflow-hidden mb-2">
                                <div class="bg-[#3E36AE] h-full transition-all duration-500" style="width: {{ min(($anxietyScore / 21) * 100, 100) }}%"></div>
                            </div>

                            <p class="text-center text-gray-600 mb-4">
                                คะแนนของคุณคิดเป็น {{ number_format(($anxietyScore / 21) * 100, 1) }}%
                            </p>

                            <div class="border-t border-gray-300 w-full pt-4">
                                <h2 class="text-lg font-medium text-[#3E36AE] mb-2">ผลการประเมิน</h2>
                                @if ($anxietyScore <= 3)
                                    <p class="text-green-600 font-medium mb-2 text-center">ระดับปกติ</p>
                                    <p class="text-gray-600 text-sm">ภาวะวิตกกังวลของคุณอยู่ในระดับปกติ ควรรักษาสภาวะที่ดีนี้ไว้</p>
                                @elseif ($anxietyScore <= 5)
                                    <p class="text-green-600 font-medium mb-2 text-center">ระดับเล็กน้อย</p>
                                    <p class="text-gray-600 text-sm">คุณมีภาวะวิตกกังวลในระดับเล็กน้อย ควรหาเวลาผ่อนคลายและฝึกสติ</p>
                                @elseif($anxietyScore <= 7)
                                    <p class="text-yellow-600 font-medium mb-2 text-center">ระดับปานกลาง</p>
                                    <p class="text-gray-600 text-sm">คุณมีภาวะวิตกกังวลในระดับปานกลาง ควรฝึกการจัดการความวิตกกังวลและเทคนิคการหายใจ</p>
                                @elseif($anxietyScore <= 9)
                                    <p class="text-orange-600 font-medium mb-2 text-center">ระดับรุนแรง</p>
                                    <p class="text-gray-600 text-sm">คุณมีภาวะวิตกกังวลในระดับรุนแรง ควรปรึกษาผู้เชี่ยวชาญเพื่อรับคำแนะนำ</p>
                                @else
                                    <p class="text-red-600 font-medium mb-2 text-center">ระดับรุนแรงมาก</p>
                                    <p class="text-gray-600 text-sm">คุณมีภาวะวิตกกังวลในระดับรุนแรงมาก ควรพบผู้เชี่ยวชาญโดยเร็วเพื่อรับการช่วยเหลือ</p>
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
                                    <div class="text-8xl">😊</div>
                                @elseif ($depressionScore <= 6)
                                    <div class="text-8xl">🙂</div>
                                @elseif($depressionScore <= 10)
                                    <div class="text-8xl">😐</div>
                                @elseif($depressionScore <= 13)
                                    <div class="text-8xl">😟</div>
                                @else
                                    <div class="text-8xl">😥</div>
                                @endif
                            </div>
                            
                            <div class="mb-6 text-center">
                                <p class="text-lg font-medium text-gray-700">คะแนนของคุณ</p>
                                <p class="text-4xl font-bold text-[#3E36AE] mt-2">{{ $depressionScore }} / 21</p>
                            </div>

                            <div class="w-full max-w-md bg-gray-100 h-4 rounded-full overflow-hidden mb-2">
                                <div class="bg-[#3E36AE] h-full transition-all duration-500" style="width: {{ min(($depressionScore / 21) * 100, 100) }}%"></div>
                            </div>

                            <p class="text-center text-gray-600 mb-4">
                                คะแนนของคุณคิดเป็น {{ number_format(($depressionScore / 21) * 100, 1) }}%
                            </p>

                            <div class="border-t border-gray-300 w-full pt-4">
                                <h2 class="text-lg font-medium text-[#3E36AE] mb-2">ผลการประเมิน</h2>
                                @if ($depressionScore <= 4)
                                    <p class="text-green-600 font-medium mb-2 text-center">ระดับปกติ</p>
                                    <p class="text-gray-600 text-sm">ภาวะซึมเศร้าของคุณอยู่ในระดับปกติ ควรรักษาสภาวะที่ดีนี้ไว้</p>
                                @elseif ($depressionScore <= 6)
                                    <p class="text-green-600 font-medium mb-2 text-center">ระดับเล็กน้อย</p>
                                    <p class="text-gray-600 text-sm">คุณมีภาวะซึมเศร้าในระดับเล็กน้อย ควรหากิจกรรมที่ทำให้มีความสุขและออกกำลังกาย</p>
                                @elseif($depressionScore <= 10)
                                    <p class="text-yellow-600 font-medium mb-2 text-center">ระดับปานกลาง</p>
                                    <p class="text-gray-600 text-sm">คุณมีภาวะซึมเศร้าในระดับปานกลาง ควรปรึกษาเพื่อนสนิทหรือคนในครอบครัว</p>
                                @elseif($depressionScore <= 13)
                                    <p class="text-orange-600 font-medium mb-2 text-center">ระดับรุนแรง</p>
                                    <p class="text-gray-600 text-sm">คุณมีภาวะซึมเศร้าในระดับรุนแรง ควรปรึกษาผู้เชี่ยวชาญเพื่อรับความช่วยเหลือ</p>
                                @else
                                    <p class="text-red-600 font-medium mb-2 text-center">ระดับรุนแรงมาก</p>
                                    <p class="text-gray-600 text-sm">คุณมีภาวะซึมเศร้าในระดับรุนแรงมาก ควรพบผู้เชี่ยวชาญโดยเร็วเพื่อรับการช่วยเหลือ</p>
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
            <a href="{{ route('main') }}" class="text-lg px-6 py-2 rounded-xl text-white font-medium shadow-md bg-[#c0c0c0] transition-all duration-300 hover:bg-gray-400">
                หน้าหลัก
            </a>
        </div>
    </div>

    @include('layouts.assessment.script')
@endsection