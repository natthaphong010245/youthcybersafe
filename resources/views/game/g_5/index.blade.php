@extends('layouts.game.bullying.index')

@php
    $backUrl = '/category/game';
    $mainUrl = '/main';
@endphp

@section('content')
    @if(isset($showIntroModal) && $showIntroModal)
        @include('game.intro', [
            'title' => 'ความรู้เกี่ยวกับพฤติกรรมการรังแกกัน',
            'gameNumber' => '5',
            'description' => 'การกลั่นแกล้งแบบดั้งเดิม และคาดโทษในบอร์',
            'descriptionClass' => 'pr-2 pl-2',
            'actionText' => 'เริ่มความก้าวหน้ากันเลย'
        ])
    @endif

    <div class="card-container space-y-6 px-6 md:px-0 mb-6 {{ isset($showIntroModal) && $showIntroModal ? 'game-blur' : '' }}" id="game-content">
        <div class="text-center mb-2">
            <h2 class="text-xl font-bold text-indigo-800">{{ $gameTitle ?? 'การกลั่นแกล้งแบบดั้งเดิม' }}</h2>
            <h2 class="text-lg font-bold text-indigo-800">{{ $gameSubtitle ?? 'TRADITIONAL' }}</h2>
        </div>
        
        <div class="flex justify-center mb-8">
            <div class="rounded-lg overflow-hidden shadow-lg">
                <img src="{{ asset('images/game/5/' . ($scenarioImage ?? 'traditional.png')) }}" 
                     alt="{{ $altText ?? 'Traditional Bullying Scenario' }}" 
                     class="w-full h-64 object-cover">
            </div>
        </div>
        
        <div class="mb-2">
            <h3 class="text-md font-medium text-indigo-700 mb-2">เรียงลำดับเหตุการณ์ให้ถูกต้อง</h3>
            <div class="grid {{ $slotColumns ?? 'grid-cols-3' }} gap-4 mb-6 {{ $slotContainerClass ?? 'max-w-lg mx-auto' }}" id="sequence-slots">
                @for($i = 1; $i <= ($totalSlots ?? 3); $i++)
                <div class="sequence-slot border-2 border-dashed border-indigo-300 rounded-lg {{ $slotClass ?? 'aspect-square' }} flex items-center justify-center relative" data-position="{{ $i }}">
                    <span class="slot-number text-2xl font-bold text-indigo-400">{{ $i }}</span>
                    <div class="slot-content hidden w-full h-full"></div>
                </div>
                @endfor
            </div>
        </div>
        
        <div class="mb-2">
            <h3 class="text-md font-medium text-indigo-700 mb-2">เลือกเหตุการณ์</h3>
            <div class="grid {{ $characterColumns ?? 'grid-cols-3' }} gap-4 {{ $characterContainerClass ?? 'max-w-lg mx-auto' }}" id="character-options">
                @foreach(($characters ?? []) as $character)
                <div class="character-option cursor-pointer {{ $characterClass ?? 'aspect-square' }}" 
                     data-character="{{ $character['key'] }}" 
                     data-correct-position="{{ $character['position'] }}">
                    <div class="bg-indigo-500 text-white rounded-lg p-3 text-center font-medium transition-all hover:bg-indigo-600 h-full flex flex-col justify-center">
                        <div class="text-lg">{{ $character['main'] }}</div>
                        <div class="text-xs">{{ $character['sub'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div id="success-modal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30">
        <div class="modal-content bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4 text-center">
            <img src="{{ asset('images/material/school_girl.png') }}" alt="Happy Student" class="w-32 h-auto mx-auto mb-4">
            <h3 class="text-2xl font-bold text-indigo-800">เยี่ยมมาก!</h3>
            <p class="text-indigo-800 mb-4 text-lg">คุณตอบได้ถูกต้อง</p>
            <p class="text-indigo-800 text-xl mb-2 font-bold">เริ่มความก้าวหน้าในเกมต่อไปกัน</p>
            <button id="success-btn" class="bg-[#929AFF] text-white font-medium py-3 px-8 rounded-xl transition-colors hover:bg-indigo-600">
                เริ่ม
            </button>
        </div>
    </div>

    <div id="failure-modal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-30">
        <div class="modal-content bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4 text-center">
            <img src="{{ asset('images/material/school_girl_false.png') }}" alt="Confused Student" class="w-32 h-auto mx-auto mb-4">
            <h3 class="text-2xl font-bold text-indigo-800">พยายามต่อไป!</h3>
            <p class="text-indigo-800 mb-6 text-lg">ตัวเลือกของคุณยังไม่ถูกต้อง</p>
            <div class="flex gap-8 justify-center">
                <button id="skip-btn" class="bg-gray-400 text-white font-medium py-3 px-6 rounded-xl transition-colors hover:bg-gray-500">
                    ข้าม
                </button>
                <button id="retry-btn" class="bg-[#929AFF] text-white font-medium py-3 px-6 rounded-xl transition-colors hover:bg-indigo-600">
                    อีกครั้ง
                </button>
            </div>
        </div>
    </div>

    @include('layouts.game.script.5.index')

    @php
        $defaultCharacters = ['bully', 'victim', 'bystander'];
        $defaultCorrectSequence = ['bully', 'victim', 'bystander'];
        $defaultNextRoute = route('main');
        $defaultSkipRoute = route('main');
    @endphp
    
    <script>
        window.gameCharacters = @json($availableOptions ?? $defaultCharacters);
        window.gameCorrectSequence = @json($correctSequence ?? $defaultCorrectSequence);
        window.gameNextRoute = @json($nextRoute ?? $defaultNextRoute);
        window.gameSkipRoute = @json($skipRoute ?? $defaultSkipRoute);
        window.gameHasIntroModal = @json($showIntroModal ?? false);
    </script>
@endsection