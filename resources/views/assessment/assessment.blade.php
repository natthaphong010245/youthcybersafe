@extends('layouts.main_category')

@php
    $mainUrl = '/main';
@endphp

@section('content')
<style>
    .assessment-card { transition: transform 0.2s ease-in-out; }
    .assessment-card:hover { transform: translateY(-2px); }
    .assessment-card:active { transform: translateY(0); }
    .modal-backdrop { transition: opacity 0.3s ease-in-out; }
    .modal-content { transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
</style>

<div class="card-container space-y-10 px-10 md:px-0">
    <div class="text-center mb-8 relative">
        <div class="flex items-center justify-center">
            <div class="relative">
                <h1 class="text-3xl font-bold text-[#3E36AE] inline-block">แบบคัดกรอง</h1>
                <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">หมวดหมู่</p>
            </div>
        </div>
    </div>

    @php
    $assessments = [
        [
            'type' => 'link',
            'route' => 'cyberbullying',
            'image' => 'cyberbullying.png',
            'image_position' => 'left-2',
            'title_top' => 'แบบคัดกรอง',
            'title_bottom' => 'CYBERBULLYING'
        ],
        [
            'type' => 'modal',
            'id' => 'mental-health-card',
            'image' => 'mental_health.png',
            'image_position' => 'left-5',
            'title_top' => 'แบบคัดกรอง',
            'title_bottom' => 'สุขภาพทางจิต'
        ]
    ];
    @endphp

    @foreach($assessments as $index => $assessment)
    @if($assessment['type'] === 'link')
        <a href="{{ route($assessment['route']) }}" class="assessment-card block relative">
    @else
        <div id="{{ $assessment['id'] }}" class="assessment-card block relative cursor-pointer">
    @endif
            <div class="flex items-center h-24 rounded-[10px] relative bg-[#929AFF]">
                <div class="absolute {{ $assessment['image_position'] }} -top-8 z-10">
                    <img src="{{ asset('images/assessment/' . $assessment['image']) }}" 
                         alt="{{ $assessment['title_bottom'] }}" 
                         class="w-auto h-28 object-contain">
                </div>
                <div class="flex-1 flex items-center justify-center pr-6 pl-24">
                    <div class="flex flex-col text-center">
                        <div class="font-medium text-white text-lg mb-1">{{ $assessment['title_top'] }}</div>
                        <div class="font-medium text-white text-xl">{{ $assessment['title_bottom'] }}</div>
                    </div>
                </div>
            </div>
    @if($assessment['type'] === 'link')
        </a>
    @else
        </div>
    @endif
    
    @if($index === 0)
        <div class="mb-6"></div>
    @endif
    @endforeach
</div>

<div id="mental-health-modal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden opacity-0">
    <div id="modal-content" class="modal-content bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4 text-center transform scale-50">
        <div class="mb-4">
            <h3 class="text-lg font-medium text-[#3E36AE] mb-1">แบบประเมินพฤติกรรม</h3>
            <h3 class="text-xl font-bold text-[#3E36AE] mb-4">ประเมินพฤติกรรมสุขภาพจิต</h3>
        </div>

        <img src="{{ asset('images/material/school_girl.png') }}" alt="School Girl Character" class="w-32 h-auto rounded-full mx-auto mb-6 object-cover">

        <div class="mb-2">
            
            <p class="text-xl font-medium text-[#3E36AE]">เริ่มทำแบบสอบถามกันเลย</p>
        </div>

        <button id="start-assessment-btn" class="bg-[#929AFF] text-white text-lg py-1 px-6 rounded-xl transition-colors hover:bg-[#7B85FF]">
            เริ่ม
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mentalHealthCard = document.getElementById('mental-health-card');
    const modal = document.getElementById('mental-health-modal');
    const startAssessmentBtn = document.getElementById('start-assessment-btn');
    const modalContent = document.getElementById('modal-content');
    
    let modalShown = false;

    ['pageshow', 'popstate', 'visibilitychange'].forEach(event => {
        window.addEventListener(event, () => {
            if (modalShown || !document.hidden) {
                closeModal();
                modalShown = false;
            }
        });
    });

    mentalHealthCard.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modalShown = true;
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            modalContent.classList.remove('scale-50');
            modalContent.classList.add('scale-100');
        }, 10);
    });

    startAssessmentBtn.addEventListener('click', () => {
        modalShown = true;
        window.location.href = '{{ route("mental_health/form") }}';
    });

    modal.addEventListener('click', (e) => e.target === modal && closeModal());
    document.addEventListener('keydown', (e) => e.key === 'Escape' && !modal.classList.contains('hidden') && closeModal());

    function closeModal() {
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-50');
        setTimeout(() => {
            modal.classList.add('hidden');
            modalShown = false;
        }, 300);
    }
});
</script>
@endsection