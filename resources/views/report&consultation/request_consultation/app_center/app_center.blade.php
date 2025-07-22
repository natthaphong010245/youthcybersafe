@extends('layouts.main_category')

@php
    $mainUrl = '/main';
    
    $applications = [
        [
            'name' => 'วัยรุ่นอยากรู้',
            'url' => 'https://xn--12c1c0abddw5c6ap6ds2qva.com/',
            'alt' => 'wayrunxyakru',
            'image' => 'images/logo_wayrunxyakru.png'
        ],
        [
            'name' => 'เพื่อนกัน (Puangun)',
            'url' => 'https://puangun.example.com',
            'alt' => 'Puangun',
            'image' => 'images/logo_puangun.png'
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
                'isFirst' => $index === 0
            ])
        @endforeach
    </div>
@endsection
