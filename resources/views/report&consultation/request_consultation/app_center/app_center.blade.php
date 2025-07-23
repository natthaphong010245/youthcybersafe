@extends('layouts.main_category')

@php
    $mainUrl = '/main';
    
    $applications = [
        [
            'name' => 'วัยรุ่นอยากรู้',
            'url' => 'https://xn--12c1c0abddw5c6ap6ds2qva.com/',
            'alt' => 'wayrunxyakru',
            'image' => 'images/logo_wayrunxyakru.png',
            'type' => 'external'
        ],
        [
            'name' => 'เพื่อนกัน (Puangun)',
            'url' => '#',
            'alt' => 'Puangun',
            'image' => 'images/logo_puangun.png',
            'type' => 'modal'
        ]
    ];
@endphp

@section('content')
    <div class="card-container space-y-6 px-8 pb-6">
        <div class="text-center mb-12 relative">
            <div class="flex items-center justify-center">
                <div class="relative">
                    <h1 class="text-2xl font-bold text-[#3E36AE] inline-block tracking-wider">APPLICATION</h1>
                    <p class="text-sm text-[#3E36AE] absolute -bottom-5 right-0">พัฒนาจากศูนย์</p>
                </div>
            </div>
        </div>

        @foreach($applications as $index => $app)
            @if($index > 0)
                <br><br>
            @endif
            
            @include('report&consultation.request_consultation.app_center.card', [
                'name' => $app['name'],
                'url' => $app['url'],
                'alt' => $app['alt'],
                'image' => $app['image'],
                'type' => $app['type'],
                'isFirst' => $index === 0
            ])
        @endforeach
    </div>

    <div id="puangun-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-3xl mx-4 max-w-sm w-full relative">
        <button onclick="closePuangunModal()" class="absolute top-4 right-4 w-6 h-6 flex items-center justify-center">
            <svg class="w-8 h-8 text-[#a5a5a5]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="text-center mt-8 mb-8">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo_puangun.png') }}" alt="Puangun Logo" class="w-24 h-24 object-contain">
            </div>

            <!-- App Store Button -->
            <a href="https://apps.apple.com/th/app/%E0%B9%80%E0%B8%9E-%E0%B8%AD%E0%B8%99%E0%B8%81-%E0%B8%99-puangun/id6464116153?l=th" 
               target="_blank" 
               rel="noopener noreferrer"
               class="block bg-[#5348eb] text-white rounded-2xl p-3 mb-6 transition-colors ml-12 mr-12 border-[3px] border-[#3E36AE]">
                <div class="flex items-center justify-center space-x-8">
                    <svg class="w-14 h-14 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                    </svg>
                    <div class="text-left flex flex-col justify-center">
                        <div class="text-sm opacity-90 leading-tight mb-1">DOWNLOAD ON THE</div>
                        <div class="text-xl font-semibold leading-tight">APP STORE</div>
                    </div>
                </div>
            </a>

            <!-- Google Play Button -->
            <a href="https://play.google.com/store/apps/details?id=th.ac.mfu.puangun&pcampaignid=web_share" 
               target="_blank" 
               rel="noopener noreferrer"
               class="block bg-[#5348eb] text-white rounded-2xl p-3 transition-colors mb-4 ml-12 mr-12 border-[3px] border-[#3E36AE]">
                <div class="flex items-center justify-center space-x-8">
                    <svg class="w-14 h-14 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.53,12.9 20.18,13.18L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/>
                    </svg>
                    <div class="text-left flex flex-col justify-center">
                        <div class="text-sm opacity-90 leading-tight mb-1">ANDROID APP ON</div>
                        <div class="text-xl font-semibold leading-tight">GOOGLE PLAY</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

    <script>
        function openPuangunModal() {
            document.getElementById('puangun-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePuangunModal() {
            document.getElementById('puangun-modal').classList.add('hidden');
            document.body.style.overflow = 'auto'; 
        }

        document.getElementById('puangun-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePuangunModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePuangunModal();
            }
        });
    </script>
@endsection