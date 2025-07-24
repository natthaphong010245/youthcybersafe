@extends('layouts.game.dealing_bullying.index')

@php
    $backUrl = '/category/game';
    $mainUrl = '/main';
@endphp

@section('content')
    @include('game.intro', [
        'title' => 'การรับมือการกลั่นแกล้งบนโลกออนไลน์',
        'gameNumber' => '13',
        'description' => 'วิธีรับมือ CYBERBULLYING'
    ])

    <div id="game-content">
        <div class="container mx-auto px-4 ">
            <div class="text-center mb-12 relative">
                <div class="flex items-center justify-center">
                    <div class="relative">
                        <h1 class="text-3xl font-bold text-[#3E36AE] inline-block mb-2"> CYBERBULLYING</h1>
                        <p class="text-lg text-[#3E36AE] absolute -bottom-6 right-0">วิธีรับมือ</p>
                    </div>
                </div>
            </div>

            <div class="max-w-md mx-auto">
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="action-card" data-action="stop">
                        <div class="action-card-inner">
                            <img src="{{ asset('images/game/13/stop.png') }}" alt="Stop" class="action-image">
                        </div>
                    </div>

                    <div class="action-card" data-action="remove">
                        <div class="action-card-inner">
                            <img src="{{ asset('images/game/13/remove.png') }}" alt="Remove" class="action-image">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="action-card" data-action="be-strong">
                        <div class="action-card-inner">
                            <img src="{{ asset('images/game/13/be_strong.png') }}" alt="Be Strong" class="action-image">
                        </div>
                    </div>

                    <div class="action-card" data-action="block">
                        <div class="action-card-inner">
                            <img src="{{ asset('images/game/13/block.png') }}" alt="Block" class="action-image">
                        </div>
                    </div>
                </div>

                <div class="flex justify-center">
                    <div class="action-card" data-action="tell" style="width: calc(50% - 8px);">
                        <div class="action-card-inner">
                            <img src="{{ asset('images/game/13/tell.png') }}" alt="Tell" class="action-image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="action-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30">
        <div class="bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full mx-4 text-center">
            <div class="text-center mb-12 relative">
                <div class="flex items-center justify-center">
                    <div class="relative">
                        <h1 class="text-3xl font-bold text-[#3E36AE] inline-block mb-2"> CYBERBULLYING</h1>
                        <p class="text-lg text-[#3E36AE] absolute -bottom-6 right-0">วิธีรับมือ</p>
                    </div>
                </div>
            </div>
            <div class="modal-icon-container mb-2 flex justify-center">
                <img id="modal-action-image" src="" alt=""
                    class="modal-action-icon w-42 h-auto object-contain">
            </div>

            <h3 id="modal-action-title" class="text-xl font-bold text-indigo-800 mb-2"></h3>

            <p id="modal-action-description" class="text-indigo-800 text-lg leading-relaxed mb-6"></p>

            <button id="next-btn"
                class="bg-[#929AFF] text-white font-medium text-md py-1 px-6 rounded-lg transition-colors">
                ถัดไป
            </button>
        </div>
    </div>

    <div id="summary-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 p-4">
        <div class="bg-white rounded-2xl shadow-xl p-6 max-w-md w-full text-center max-h-[90vh] overflow-y-auto">
            <div class="text-center mb-8 relative">
                <div class="flex items-center justify-center">
                    <div class="relative">
                        <h1 class="text-2xl font-bold text-[#3E36AE] inline-block mb-2"> CYBERBULLYING</h1>
                        <p class="text-base text-[#3E36AE] absolute -bottom-5 right-0">วิธีรับมือ</p>
                    </div>
                </div>
            </div>
            <div class="text-left mb-6">
                <div class="mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-500 ">STOP</div>
                        <div class="text-lg font-bold text-indigo-800">หยุดการกระทำทุกอย่าง</div>
                    </div>
                    <p class="text-sm text-indigo-800 text-center">นิ่งเฉยไม่ตอบโต้ เพื่อไม่ให้เกิดการกระทำซ้ำ
                        หรือเพิ่มความรุนแรง
                        ใช้ในในกรณีที่เป็นเหตุการณ์ทะเลาะเบาะแว้งในขั้นเริ่มต้นแล้วค่อยไปปรับความเข้าใจกันภายหลัง เช่น
                        โดนแซวเล็กน้อย</p>
                </div>

                <div class="mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-500 ">REMOVE</div>
                        <div class="text-lg font-bold text-indigo-800">ลบภาพที่เป็นการระรานออกทันที</div>
                    </div>
                    <p class="text-sm text-indigo-800 text-center">ลบทุกภาพ ข้อความ วิดีโอ ที่เป็นการกระทำไม่เหมาะสม</p>
                </div>

                <div class="mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-500 ">BE STORNG</div>
                        <dive class="text-lg font-bold text-indigo-800">เข้มแข็ง</dive>
                    </div>
                    <p class="text-sm text-indigo-800 text-center">ลบทุกภาพ ข้อความ วิดีโอ ที่เป็นการกระทำไม่เหมาะสม</p>
                </div>

                <div class="mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-500 ">BLOCK</div>
                        <div class="text-lg font-bold text-indigo-800">ปิดกั้นพวกเขาซะ</div>
                    </div>
                    <p class="text-sm text-indigo-800 text-center">บล็อกผู้ใช้งานที่มีพฤติกรรมไม่เหมาะสม</p>
                </div>

                <div class="mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-500 ">TELL</div>
                        <div class="text-lg font-bold text-indigo-800">บอกบุคคลที่ไว้ใจได้</div>
                    </div>
                    <p class="text-sm text-indigo-800 text-center">บอกผู้ปกครอง ครู หรือคนสนิทให้ทราบเพื่อขอความช่วยเหลือ
                    </p>
                </div>
            </div>

            <p class="text-indigo-800 text-lg mb-1">เริ่มความท้าทายในเกมถัดไปกันเลย</p>

            <button id="start-main-btn"
                class="bg-[#929AFF] text-white font-medium text-md py-1 px-6 rounded-lg transition-colors ">
                เริ่ม
            </button>
        </div>
    </div>

    @include('layouts.game.script.13.index')
@endsection
