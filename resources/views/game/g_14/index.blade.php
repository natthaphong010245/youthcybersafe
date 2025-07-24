@extends('layouts.game.dealing_bullying.index')

@php
    $backUrl = '/category/game';
    $mainUrl = '/main';
@endphp

@section('content')
    @include('game.intro', [
        'title' => 'การรับมือการกลั่นแกล้งบนโลกออนไลน์',
        'gameNumber' => '14',
        'description' => 'การใช้อินเตอร์เน็ตอย่างปลอดภัย ด้วยหลัก C.O.N.N.E.C.T',
        'actionText' => 'เริ่มความท้าทายกันเลย'
    ])

    <div class="bg-white" id="game-content">
        <div class="card-container space-y-6 px-2 pb-0">
            <div class="text-center mb-4">
                <h2 class="text-xl font-bold text-indigo-800">การใช้อินเตอร์เน็ตอย่างปลอดภัย ด้วยหลัก <h2
                        class="text-2xl font-bold text-indigo-800">C.O.N.N.E.C.T</h2>
                </h2>
            </div>

            <div class="grid grid-cols-2 gap-3 max-w-sm mx-auto">
                <div class="letter-card" data-letter="c" data-selected="false">
                    <div class="bg-white rounded-2xl p-6 cursor-pointer transition-all border-2 border-transparent hover:border-indigo-200"
                        style="box-shadow: 0 8px 25px rgba(0,0,0,0.15), 0 4px 10px rgba(0,0,0,0.1);">
                        <img src="{{ asset('images/game/14/c.png') }}" alt="Letter C" class="w-full h-32 object-contain">
                    </div>
                </div>

                <div class="letter-card" data-letter="o" data-selected="false">
                    <div class="bg-white rounded-2xl p-6 cursor-pointer transition-all border-2 border-transparent hover:border-indigo-200"
                        style="box-shadow: 0 8px 25px rgba(0,0,0,0.15), 0 4px 10px rgba(0,0,0,0.1);">
                        <img src="{{ asset('images/game/14/o.png') }}" alt="Letter O" class="w-full h-32 object-contain">
                    </div>
                </div>

                <div class="letter-card" data-letter="n1" data-selected="false">
                    <div class="bg-white rounded-2xl p-6 cursor-pointer transition-all border-2 border-transparent hover:border-indigo-200"
                        style="box-shadow: 0 8px 25px rgba(0,0,0,0.15), 0 4px 10px rgba(0,0,0,0.1);">
                        <img src="{{ asset('images/game/14/n.png') }}" alt="Letter N" class="w-full h-32 object-contain">
                    </div>
                </div>

                <div class="letter-card" data-letter="n2" data-selected="false">
                    <div class="bg-white rounded-2xl p-6 cursor-pointer transition-all border-2 border-transparent hover:border-indigo-200"
                        style="box-shadow: 0 8px 25px rgba(0,0,0,0.15), 0 4px 10px rgba(0,0,0,0.1);">
                        <img src="{{ asset('images/game/14/n.png') }}" alt="Letter N" class="w-full h-32 object-contain">
                    </div>
                </div>

                <div class="letter-card" data-letter="e" data-selected="false">
                    <div class="bg-white rounded-2xl p-6 cursor-pointer transition-all border-2 border-transparent hover:border-indigo-200"
                        style="box-shadow: 0 8px 25px rgba(0,0,0,0.15), 0 4px 10px rgba(0,0,0,0.1);">
                        <img src="{{ asset('images/game/14/e.png') }}" alt="Letter E" class="w-full h-32 object-contain">
                    </div>
                </div>

                <div class="letter-card" data-letter="c2" data-selected="false">
                    <div class="bg-white rounded-2xl p-6 cursor-pointer transition-all border-2 border-transparent hover:border-indigo-200"
                        style="box-shadow: 0 8px 25px rgba(0,0,0,0.15), 0 4px 10px rgba(0,0,0,0.1);">
                        <img src="{{ asset('images/game/14/c.png') }}" alt="Letter C" class="w-full h-32 object-contain">
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-4 mb-0">
                <div class="letter-card" data-letter="t" data-selected="false"
                    style="width: calc((100% - 0.75rem) / 2); max-width: calc((320px - 0.75rem) / 2);">
                    <div class="bg-white rounded-2xl p-6 cursor-pointer transition-all border-2 border-transparent hover:border-indigo-200"
                        style="box-shadow: 0 8px 25px rgba(0,0,0,0.15), 0 4px 10px rgba(0,0,0,0.1);">
                        <img src="{{ asset('images/game/14/t.png') }}" alt="Letter T" class="w-full h-32 object-contain">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="letter-modal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 opacity-0">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-sm w-full mx-4 text-center ">
            <div class="mb-6">
                <div class="mb-4 mt-2">
                    <img id="modal-letter-image" src="" alt="" class="w-32 h-auto mx-auto object-contain">
                </div>

                <h3 id="modal-letter-title" class="text-2xl font-bold text-indigo-800 mb-2"></h3>

                <p id="modal-letter-description" class="text-indigo-800 text-xl"></p>
            </div>

            <button id="continue-btn"
                class="bg-[#929AFF] text-white font-medium text-md py-1 px-6 rounded-lg transition-colors">
                ถัดไป
            </button>
        </div>
    </div>

    <div id="summary-modal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30 opacity-0">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4 text-center">
        <div class="mb-2">
            <h3 class="text-2xl font-bold text-indigo-800 mb-4">การใช้อินเตอร์เน็ตอย่างปลอดภัย</h3>

            <div class="space-y-4 flex flex-col items-center">
                <img src="{{ asset('images/game/14/internet_safely.png') }}" alt="Internet Safety" class="w-11/12 h-auto">
            </div>

            <p class="text-indigo-800 text-lg mb-1 mt-6">สิ้นสุดความท้าทาย</p>
        </div>

        <div class="flex gap-6 justify-center mt-2">
            <button id="exit-btn"
                class="bg-gray-400 text-white font-medium text-md py-1 px-4 rounded-lg transition-colors hover:bg-gray-500">
                ออก
            </button>
            <button id="start-btn"
                class="bg-[#929AFF] text-white font-medium text-md py-1 px-4 rounded-lg transition-colors hover:bg-[#7B85FF]">
                เริ่มต้น
            </button>
        </div>
    </div>
</div>

    @include('layouts.game.script.14.index')
@endsection