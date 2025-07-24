@extends('layouts.category_game')

@php
    $backUrl = '/main/game';
    $mainUrl = '/main';
@endphp


@section('content')
<style>
    :root {
        --primary-color: #3E36AE;
        --purple-bg: #929AFF;
        --border-radius: 20px;
        --card-padding: 25px;
    }

    .game-button {
        transition: all 0.3s ease;
        border: 2px solid #e0e6ed;
        border-radius: var(--border-radius);
        padding: var(--card-padding);
        cursor: pointer;
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 280px;
        position: relative;
        overflow: hidden;
    }

    .game-image-container {
        flex: 1;
        margin: calc(-1 * var(--card-padding)) calc(-1 * var(--card-padding)) 0;
        border-radius: 12px 12px 0 0;
        overflow: hidden;
        position: relative;
    }

    .game-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px 12px 0 0;
    }

    .game-title-section {
        background: var(--purple-bg);
        margin: 0 calc(-1 * var(--card-padding)) calc(-1 * var(--card-padding));
        padding: 15px;
        border-radius: 0 0 var(--border-radius) var(--border-radius);
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .game-title-section h3 {
        color: white;
        margin: 0;
        line-height: 1.3;
        text-align: center;
    }

    .game-button:focus {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }
</style>

<div class="container mx-auto px-4 py-2">
    <div class="text-center mb-14">
        <div class="flex items-center justify-center">
            <div class="relative">
                <h1 class="text-4xl font-bold text-[var(--primary-color)] tracking-[0.2em] mb-2">เกม</h1>
                <p class="text-base text-[var(--primary-color)] absolute -bottom-4 right-0">หมวดหมู่</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 md:gap-8 max-w-4xl mx-auto px-2">
        @php
        $games = [
            ['route' => 'game_1_1', 'image' => 'game1.png', 'title' => 'ตอบคำถาม'],
            ['route' => 'game_2', 'image' => 'game2.png', 'title' => 'เติมคำ'],
            ['route' => 'game_3', 'image' => 'game3.png', 'title' => 'หลัก 2 A'],
            ['route' => 'game_4_1', 'image' => 'game4.png', 'title' => 'จับคู่'],
            ['route' => 'game_5_1', 'image' => 'game5.png', 'title' => 'ระบุตัวเลือก'],
            ['route' => 'game_6', 'image' => 'game6.png', 'title' => 'ระบายความรู้สึก'],
            ['route' => 'game_7', 'image' => 'game7.png', 'title' => 'กล่องสุ่ม'],
            ['route' => 'game_8', 'image' => 'game8.png', 'title' => 'ผลกระทบผู้รังแก'],
            ['route' => 'game_9', 'image' => 'game9.png', 'title' => 'ผลกระทบผู้ถูกรังแก'],
            ['route' => 'game_10', 'image' => 'game10.png', 'title' => 'กฎหมาย CYBERBULLYING'],
            ['route' => 'game_11', 'image' => 'game11.png', 'title' => 'สัญญาณเตือนภัย'],
            ['route' => 'game_12', 'image' => 'game12.png', 'title' => 'รับมือการรังแก'],
            ['route' => 'game_13', 'image' => 'game13.png', 'title' => 'รับมือ CYBERBULLYING'],
            ['route' => 'game_14', 'image' => 'game14.png', 'title' => 'หลัก C.O.N.N.E.C.T']
        ];
        @endphp

        @foreach($games as $index => $game)
        <div class="game-button" tabindex="0" onclick="navigateTo('{{ route($game['route']) }}')">
            <div class="game-image-container">
                <img src="{{ asset('images/game/' . $game['image']) }}" alt="{{ $game['title'] }}">
            </div>
            <div class="game-title-section">
                <h3 class="font-median text-lg">{{ $game['title'] }}</h3>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    function navigateTo(url) {
        const button = event.currentTarget;
        button.style.transform = 'scale(0.95)';
        setTimeout(() => {
            window.location.href = url;
        }, 150);
    }

    document.addEventListener('keydown', function(e) {
        const buttons = [...document.querySelectorAll('.game-button')];
        const current = document.activeElement;
        const currentIndex = buttons.indexOf(current);
        
        let nextIndex = currentIndex;
        
        switch(e.key) {
            case 'ArrowRight': nextIndex = currentIndex % 2 === 0 ? currentIndex + 1 : Math.min(currentIndex + 1, buttons.length - 1); break;
            case 'ArrowLeft': nextIndex = currentIndex % 2 === 1 ? currentIndex - 1 : Math.max(currentIndex - 1, 0); break;
            case 'ArrowDown': nextIndex = Math.min(currentIndex + 2, buttons.length - 1); break;
            case 'ArrowUp': nextIndex = Math.max(currentIndex - 2, 0); break;
            case 'Enter': current.click(); return;
        }
        
        if (nextIndex !== currentIndex && buttons[nextIndex]) {
            e.preventDefault();
            buttons[nextIndex].focus();
        }
    });
</script>
@endsection