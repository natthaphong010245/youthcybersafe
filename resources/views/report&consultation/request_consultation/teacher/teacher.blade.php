@extends('layouts.main_category')

@php
    $mainUrl = '/main';
@endphp

@section('content')
    <div class="card-container space-y-10 px-10 md:px-0">
        <div class="text-center mb-4 relative">
            <div class="flex items-center justify-center">
                <div class="relative">
                    <h1 class="text-2xl font-bold text-[#3E36AE] inline-block">ขอคำปรึกษาจากคุณครู</h1>
                    <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">เชียงราย</p>
                </div>
            </div>
        </div>

        @php
            $schools = [
                [
                    'name' => 'โรงเรียน',
                    'location' => 'วาวีวิทยาคม',
                    'url' => 'https://web.facebook.com/waweenews/?locale=th_TH&_rdc=1&_rdr#',
                    'image' => 'report_consultation/school/วาวีวิทยาคม.png',
                ],
                [
                    'name' => 'โรงเรียนสหศาสตร์ศึกษา',
                    'location' => 'สหศาสตร์ศึกษา',
                    'url' => 'https://web.facebook.com/sahasartsuksa.cri/?locale=th_TH&_rdc=1&_rdr#',
                    'image' => 'report_consultation/school/สหศาสตร์ศึกษา.png',
                ],
                [
                    'name' => 'โรงเรียน',
                    'location' => 'ราชประชานุเคราะห์ 62',
                    'url' => 'https://web.facebook.com/RPG62CR/?_rdc=1&_rdr#',
                    'image' => 'report_consultation/school/ราชประชานุเคราะห์62.png',
                ],
                [
                    'name' => 'โรงเรียน',
                    'location' => 'ห้วยไร่สามัคคี',
                    'url' => 'https://web.facebook.com/Huairaisamakkee/?locale=th_TH&_rdc=1&_rdr#',
                    'image' => 'report_consultation/school/ห้วยไร่สามัคคี.png',
                ],
            ];
        @endphp

        @foreach ($schools as $school)
            <a href="{{ $school['url'] }}" target="_blank" class="block relative">
                <div class="py-3 px-6 flex items-center justify-between h-20 rounded-[10px] bg-[#929AFF]">
                    <div class="flex flex-col">
                        <div class="font-median text-white text-lg">{{ $school['name'] }}</div>
                        <div class="font-median text-white text-xl ml-3">{{ $school['location'] }}</div>
                    </div>
                    <div class="flex items-center justify-center">
                        <img src="{{ asset('images/' . $school['image']) }}" alt="{{ $school['name'] }}"
                            class="w-16 h-16 object-contain">
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endsection