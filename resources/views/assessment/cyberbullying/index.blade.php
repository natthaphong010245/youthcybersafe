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
    <div class="text-center mb-6 relative">
        <div class="flex items-center justify-center">
            <div class="relative">
                <h1 class="text-3xl font-bold text-[#3E36AE] inline-block">CYBERBULLYING</h1>
                <p class="text-base text-[#3E36AE] absolute -bottom-6 right-0">แบบคัดกรอง</p>
            </div>
        </div>
    </div>

    @php
    $assessments = [
        [
            'type' => 'perpetrator',
            'image' => 'bully.png',
            'title_top' => 'ประสบการณ์',
            'title_bottom' => 'ผู้กระทำ',
            'modal_title' => 'ประสบการณ์การรังแกผู้กระทำ',
            'modal_subtitle' => 'เริ่มความท้าทายกันเลย',
            'route' => route('person_action/form')
        ],
        [
            'type' => 'victim',
            'image' => 'bullied.png',
            'title_top' => 'ประสบการณ์',
            'title_bottom' => 'ผู้ถูกกระทำ',
            'modal_title' => 'ประสบการณ์การรังแกผู้ถูกกระทำ',
            'modal_subtitle' => 'เริ่มความท้าทายกันเลย',
            'route' => route('victim/form')
        ],
        [
            'type' => 'overview',
            'image' => 'overview.png',
            'title_top' => 'ประสบการณ์',
            'title_bottom' => 'ภาพรวม',
            'modal_title' => 'ประสบการณ์การรังแกภาพรวม',
            'modal_subtitle' => 'เริ่มความท้าทายกันเลย',
            'route' => route('overview/form')
        ]
    ];
    @endphp

    @foreach($assessments as $assessment)
    <div class="assessment-card block relative cursor-pointer mt-8" data-type="{{ $assessment['type'] }}" data-route="{{ $assessment['route'] }}" data-title="{{ $assessment['modal_title'] }}" data-subtitle="{{ $assessment['modal_subtitle'] }}">
        <div class="flex items-center h-24 rounded-xl relative bg-[#929AFF] mb-6">
            <div class="absolute left-6 -top-6 z-10">
                <img src="{{ asset('images/assessment/' . $assessment['image']) }}" 
                     alt="{{ $assessment['title_bottom'] }}" 
                     class="w-auto h-28 object-contain">
            </div>
            <div class="flex-1 flex items-center justify-center pr-6 pl-24">
                <div class="flex flex-col text-center">
                    <div class="font-medium text-white text-lg">{{ $assessment['title_top'] }}</div>
                    <div class="font-medium text-white text-2xl">{{ $assessment['title_bottom'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div id="assessment-modal" class="modal-backdrop fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden opacity-0">
    <div id="modal-content" class="modal-content bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4 text-center transform scale-50">
        <div class="mb-4">
            <h3 class="text-lg font-medium text-[#3E36AE] mb-1">แบบประเมินพฤติกรรม</h3>
            <h3 class="text-xl font-bold text-[#3E36AE] mb-4" id="modal-title">ประสบการณ์การรังแกกันเลย</h3>
        </div>

        <img src="{{ asset('images/material/school_girl.png') }}" alt="School Girl Character" class="w-32 h-auto rounded-full mx-auto mb-6 object-cover">

        <div class="mb-2">
            
            <p class="text-xl font-medium text-[#3E36AE]" id="modal-subtitle">เริ่มทำแบบสอบถามกันเลย</p>
        </div>

        <button id="start-assessment-btn" class="bg-[#929AFF] text-white text-lg py-2 px-8 rounded-xl transition-colors hover:bg-[#7B85FF]">
            เริ่ม
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const assessmentCards = document.querySelectorAll('.assessment-card');
    const modal = document.getElementById('assessment-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalSubtitle = document.getElementById('modal-subtitle');
    const startBtn = document.getElementById('start-assessment-btn');
    const modalContent = document.getElementById('modal-content');
    
    let selectedRoute = '';
    let modalShown = false;

    ['pageshow', 'popstate', 'visibilitychange'].forEach(event => {
        window.addEventListener(event, () => {
            if (modalShown || !document.hidden) {
                closeModal();
                modalShown = false;
            }
        });
    });

    assessmentCards.forEach(card => {
        card.addEventListener('click', function() {
            selectedRoute = this.dataset.route;
            modalTitle.textContent = this.dataset.title;
            modalSubtitle.textContent = this.dataset.subtitle;

            modal.classList.remove('hidden');
            modalShown = true;
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                modalContent.classList.remove('scale-50');
                modalContent.classList.add('scale-100');
            }, 10);
        });
    });

    startBtn.addEventListener('click', () => {
        if (selectedRoute) {
            modalShown = true;
            window.location.href = selectedRoute;
        }
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
            selectedRoute = '';
            modalShown = false;
        }, 300);
    }
});
</script>
@endsection