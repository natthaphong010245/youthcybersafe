{{-- resources/views/teacher/dashboard/behavioral-report.blade.php --}}
@extends('layouts.teacher-dashboard')

@section('title', 'Behavioral Report - Youth Cybersafe Teacher')
@section('page-title', 'Behavioral Report')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6><strong>{{ $data['school_name'] }}</strong></h6>
                    <p class="text-muted small">Behavioral</p>
                </div>
                <div class="d-flex align-items-center" style="position: relative;">
                    <button class="btn btn-light border" id="filterToggle" type="button">
                        <i class="fas fa-filter me-2"></i>
                        <span id="filterLabel">ทั้งหมด</span>
                        <i class="fas fa-chevron-down ms-2"></i>
                    </button>
                    <div class="filter-dropdown" id="filterDropdown" style="display: none;">
                        <div class="filter-dropdown-content">
                            <div class="filter-option" data-value="">
                                <span>ทั้งหมด</span>
                            </div>
                            <div class="filter-option" data-value="reviewed">
                                <span>ตรวจสอบแล้ว</span>
                            </div>
                            <div class="filter-option" data-value="pending">
                                <span>รอตรวจสอบ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table behavioral-table">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Date</th>
                            <th style="width: 65%;">Messages</th>
                            <th style="width: 20%;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="reportsTableBody">
                        @forelse($data['reports'] as $report)
                        <tr class="report-row" data-id="{{ $report['id'] }}" style="cursor: pointer;">
                            <td class="text-center">{{ $report['date'] }}</td>
                            <td class="text-center">{{ Str::limit($report['message'], 100, '.....') }}</td>
                            <td class="text-center">
                                @if($report['status'] == 'reviewed')
                                    <span style="color: #006E0B; font-weight: 500;">ตรวจสอบแล้ว</span>
                                @else
                                    <span style="color: #D36300; font-weight: 500;">รอตรวจสอบ</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                ไม่พบข้อมูลการรายงาน
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Google-style Pagination (Right Aligned) -->
            @if($data['pagination']['total_pages'] > 1)
            <div class="d-flex justify-content-end mt-1">
                <div class="pagination-wrapper">
                    <nav aria-label="Page navigation">
                        <ul class="pagination google-pagination" id="pagination">
                            {{-- Previous Arrow --}}
                            <li class="page-item {{ $data['pagination']['current_page'] == 1 ? 'disabled' : '' }}">
                                <a class="page-link page-arrow" href="#" data-page="{{ max(1, $data['pagination']['current_page'] - 1) }}"
                                   {{ $data['pagination']['current_page'] == 1 ? 'tabindex="-1" aria-disabled="true"' : '' }}>
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            
                            @php
                                $current = $data['pagination']['current_page'];
                                $total = $data['pagination']['total_pages'];
                                $start = max(1, $current - 4);
                                $end = min($total, $current + 4);
                                
                                if ($current <= 5) {
                                    $end = min($total, 10);
                                } elseif ($current > $total - 5) {
                                    $start = max(1, $total - 9);
                                }
                            @endphp
                            
                            @if($start > 1)
                                <li class="page-item">
                                    <a class="page-link" href="#" data-page="1">1</a>
                                </li>
                                @if($start > 2)
                                    <li class="page-item disabled">
                                        <span class="page-link dots">...</span>
                                    </li>
                                @endif
                            @endif
                            
                            @for($i = $start; $i <= $end; $i++)
                                <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                    <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor
                            
                            @if($end < $total)
                                @if($end < $total - 1)
                                    <li class="page-item disabled">
                                        <span class="page-link dots">...</span>
                                    </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="#" data-page="{{ $total }}">{{ $total }}</a>
                                </li>
                            @endif
                            
                            {{-- Next Arrow --}}
                            <li class="page-item {{ $data['pagination']['current_page'] == $data['pagination']['total_pages'] ? 'disabled' : '' }}">
                                <a class="page-link page-arrow" href="#" data-page="{{ min($data['pagination']['total_pages'], $data['pagination']['current_page'] + 1) }}"
                                   {{ $data['pagination']['current_page'] == $data['pagination']['total_pages'] ? 'tabindex="-1" aria-disabled="true"' : '' }}>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Report Detail Modal -->
<div class="modal fade" id="reportDetailModal" tabindex="-1" aria-labelledby="reportDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header border-0 pb-0">
                <div class="w-100 d-flex justify-content-end align-items-start">
                    <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times" style="color: #343A81; font-size: 25px; margin-right: 5px"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body" style="padding: 0px 30px 30px;">
                <div class="row">
                    <!-- Left side - Image -->
                    <div class="col-md-6">
                        <div class="image-gallery">
                            <div class="image-container" style="position: relative; height: 400px; background: #f8f9fa; border-radius: 15px; overflow: hidden;">
                                <img id="currentImage" src="" alt="Report Image" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                <div id="noImageMessage" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #666; font-size: 18px; font-weight: 500; display: none;">
                                    <i class="fas fa-image fa-3x mb-3" style="color: #ddd;"></i><br>
                                    ไม่พบรูปภาพ
                                </div>
                                <div class="image-nav-left" style="position: absolute; left: 0; top: 0; width: 50%; height: 100%; cursor: pointer; z-index: 10;"></div>
                                <div class="image-nav-right" style="position: absolute; right: 0; top: 0; width: 50%; height: 100%; cursor: pointer; z-index: 10;"></div>
                                
                                <!-- Image indicators -->
                                <div class="image-indicators" id="imageIndicators" style="position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; padding: 8px 12px; background: rgba(0,0,0,0.3); border-radius: 20px;">
                                    <span class="indicator active" data-index="0"></span>
                                    <span class="indicator" data-index="1"></span>
                                    <span class="indicator" data-index="2"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Audio and Location Controls - Vertical Layout with More Spacing -->
                        <div class="controls-section mt-3 d-flex justify-content-center align-items-center" style="padding: 0 20px; gap: 100px;">
                            <!-- Audio Control -->
                            <div class="audio-control d-flex flex-column align-items-center" style="cursor: pointer;" onclick="toggleAudio()">
                                <div class="audio-icon mb-2">
                                    <i id="audioIcon" class="fas fa-play" style="color: #626DF7; font-size: 24px;"></i>
                                </div>
                                <span id="audioTime" style="color: #343A81; font-size: 14px; font-weight: 500;">รายงานเสียง</span>
                            </div>
                            
                            <!-- Location Control -->
                            <div class="location-control d-flex flex-column align-items-center" style="cursor: pointer;" onclick="openLocation()">
                                <div class="location-icon mb-2">
                                    <i class="fas fa-map-marker-alt" style="color: #626DF7; font-size: 24px;"></i>
                                </div>
                                <span style="color: #343A81; font-size: 14px; font-weight: 500;">ตำแหน่งที่ตั้ง</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right side - Content -->
                    <div class="col-md-6" style="position: relative;">
                        <div class="content-section" style="height: 500px; display: flex; flex-direction: column; justify-content: space-between; margin-right: 30px;">
                            <!-- Message text - Full area like in image 4 -->
                            <div class="message-content" style="flex-grow: 1; overflow-y: auto; padding: 0; line-height: 1.6;">
                                <h6 style="color: #343A81; font-size: 18px; font-weight: 600; margin-bottom: 15px;">ข้อความ</h6>
                                <p id="reportMessage" style="color: #343A81; font-size: 16px; margin: 0; padding: 0; line-height: 1.8; word-wrap: break-word;"></p>
                            </div>
                            
                            <!-- Review Button -->
                            <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-primary" id="reviewBtn" style="background-color: #626DF7; border-color: #626DF7; border-radius: 10px; padding: 10px 30px; font-weight: 500; font-size: 14px;">ตรวจสอบ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal with Dark Transparent Background -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <h6 class="mb-3" style="font-weight: 600; color: #2d3748;">ยืนยันการตรวจสอบ</h6>
                    <p class="text-muted mb-0" style="font-size: 14px;">คุณต้องการยืนยันการตรวจสอบหรือไม่?</p>
                </div>
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 500;">ยกเลิก</button>
                    <button type="button" class="btn btn-primary px-4" id="confirmReviewBtn" style="border-radius: 10px; background: #626DF7; border-color: #626DF7; font-weight: 500;">ตกลง</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.behavioral-table th,
.behavioral-table td {
    text-align: center !important;
    vertical-align: middle !important;
}

.behavioral-table {
    border-collapse: collapse;
    border: 2px solid #ddd;
}

.behavioral-table th {
    font-weight: 600;
    color: #2d3748;
    border: 1px solid #ddd;
    padding: 12px;
    background-color: #f8f9fa;
}

.behavioral-table td {
    border: 1px solid #ddd;
    padding: 12px;
    vertical-align: middle;
}

.behavioral-table tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
}

.behavioral-table tbody tr:nth-child(even) {
    background-color: white;
}

.report-row:hover {
    background-color: #f0f0f0 !important;
}

/* Pagination styles */
.pagination-wrapper {
    display: inline-flex;
    align-items: center;
    font-family: 'Kanit', sans-serif;
}

.google-pagination {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 2px;
}

.google-pagination .page-item {
    margin: 0;
}

.google-pagination .page-link {
    color: #453d9c;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 400;
    background-color: transparent;
    transition: all 0.2s ease-in-out;
    min-width: 34px;
    min-height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
}

.google-pagination .page-item.active .page-link {
    background-color: #626DF7;
    color: white;
    font-weight: 500;
    border-radius: 4px;
    pointer-events: none;
}

.google-pagination .page-item.disabled .page-link {
    color: #d0d0d0 !important;
    background-color: #f8f9fa !important;
    cursor: not-allowed !important;
    opacity: 0.5 !important;
    pointer-events: none;
}

.google-pagination .page-item:not(.disabled) .page-link:hover {
    background-color: #f0f0f0 !important;
    color: #333 !important;
    transform: translateY(-1px);
}

.google-pagination .page-arrow {
    color: #70757a !important;
    font-size: 11px;
    padding: 6px 8px !important;
    min-width: 32px !important;
    min-height: 32px !important;
}

.google-pagination .dots {
    color: #70757a !important;
    cursor: default;
    padding: 6px 4px !important;
}

/* Filter dropdown styles */
#filterToggle {
    background: white;
    border: 2px solid #ddd;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    color: #2d3748;
    transition: all 0.2s ease;
    min-width: 120px;
    position: relative;
}

#filterToggle:hover {
    border-color: #999;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#filterToggle:focus {
    border-color: #626DF7;
    box-shadow: 0 0 0 0.2rem rgba(98, 109, 247, 0.25);
}

.filter-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    z-index: 1050;
    min-width: 160px;
    margin-top: 8px;
}

.filter-dropdown-content {
    background: white;
    border: 2px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    animation: fadeInUp 0.2s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.filter-option {
    padding: 12px 16px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
    font-weight: 500;
    color: #2d3748;
    border-bottom: 1px solid #f0f0f0;
}

.filter-option:last-child {
    border-bottom: none;
}

.filter-option:hover {
    background-color: #f8f9ff;
    color: #626DF7;
}

.filter-option.active {
    background-color: #626DF7;
    color: white;
}

/* Modal styles */
.image-container img {
    transition: transform 0.3s ease;
}

.indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    cursor: pointer;
    transition: all 0.3s ease;
}

.indicator.active {
    background-color: white;
    transform: scale(1.3);
}

.audio-control,
.location-control {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 10px;
    border-radius: 10px;
}

.audio-control:hover,
.location-control:hover {
    background-color: rgba(98, 109, 247, 0.1);
    transform: translateY(-2px);
}

.audio-icon,
.location-icon {
    width: 48px;
    height: 48px;
    background: rgba(98, 109, 247, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.audio-control:hover .audio-icon,
.location-control:hover .location-icon {
    background: rgba(98, 109, 247, 0.2);
    transform: scale(1.05);
}

.audio-icon.playing {
    background: rgba(98, 109, 247, 0.2);
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(98, 109, 247, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(98, 109, 247, 0); }
    100% { box-shadow: 0 0 0 0 rgba(98, 109, 247, 0); }
}

.message-content {
    font-size: 16px;
    line-height: 1.8;
}

.message-content h6 {
    color: #343A81;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 10px;
}

/* Custom modal backdrop for dark transparent effect - Higher Specificity */
.modal.show .modal-backdrop,
.modal-backdrop.show,
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.4) !important;
    backdrop-filter: blur(3px) !important;
    -webkit-backdrop-filter: blur(3px) !important;
    opacity: 1 !important;
}

/* Force backdrop styling for all modals */
body .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.4) !important;
    backdrop-filter: blur(3px) !important;
    -webkit-backdrop-filter: blur(3px) !important;
    opacity: 1 !important;
}

/* Enhanced modal styling */
#confirmationModal .modal-content {
    background: white;
    border-radius: 20px;
    border: none;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
}

#confirmationModal .modal-body {
    padding: 30px;
}

/* Ensure both modals use the same backdrop style */
#reportDetailModal .modal-backdrop,
#confirmationModal .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.4) !important;
    backdrop-filter: blur(3px) !important;
    -webkit-backdrop-filter: blur(3px) !important;
}

/* Specific z-index control for layered modals */
#confirmationModal {
    z-index: 1060 !important;
}

#confirmationModal .modal-backdrop {
    z-index: 1055;
}

/* Custom backdrop for confirmation modal */
.confirmation-custom-backdrop {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background-color: rgba(0, 0, 0, 0.4) !important;
    backdrop-filter: blur(3px) !important;
    -webkit-backdrop-filter: blur(3px) !important;
    z-index: 1055 !important;
    opacity: 1 !important;
}

/* Ensure confirmation modal content is above backdrop */
#confirmationModal .modal-dialog {
    z-index: 1060 !important;
    position: relative !important;
}

.message-content::-webkit-scrollbar {
    width: 4px;
}

.message-content::-webkit-scrollbar-track {
    background: #e9ecef;
    border-radius: 2px;
}

.message-content::-webkit-scrollbar-thumb {
    background: #626DF7;
    border-radius: 2px;
}

.btn-close-custom {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-close-custom:hover {
    transform: scale(1.1);
}

#reviewBtn {
    transition: all 0.3s ease;
    box-shadow: 0 3px 10px rgba(98, 109, 247, 0.3);
}

#reviewBtn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(98, 109, 247, 0.4);
}

#reviewBtn:disabled {
    box-shadow: none;
    transform: none;
}
</style>
@endsection

@section('scripts')
<script>
let currentImageIndex = 0;
let currentImages = [];
let currentReportId = null;
let currentReportStatus = null;
let currentReportLatitude = null;
let currentReportLongitude = null;
let currentAudioUrl = null;
let currentFilter = '{{ $data["current_filter"] ?? "" }}';
let isFilterOpen = false;

// Filter functionality
document.getElementById('filterToggle').addEventListener('click', function(e) {
    e.stopPropagation();
    toggleFilterDropdown();
});

document.addEventListener('click', function(e) {
    if (isFilterOpen && !e.target.closest('.filter-dropdown') && !e.target.closest('#filterToggle')) {
        closeFilterDropdown();
    }
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.filter-option')) {
        const option = e.target.closest('.filter-option');
        const value = option.getAttribute('data-value');
        const text = option.querySelector('span').textContent;
        
        selectFilterOption(value, text);
        closeFilterDropdown();
        loadReports(value, 1);
    }
});

function toggleFilterDropdown() {
    const dropdown = document.getElementById('filterDropdown');
    const button = document.getElementById('filterToggle');
    const chevron = button.querySelector('.fa-chevron-down');
    
    if (isFilterOpen) {
        closeFilterDropdown();
    } else {
        dropdown.style.display = 'block';
        updateFilterOptions();
        chevron.style.transform = 'rotate(180deg)';
        isFilterOpen = true;
    }
}

function closeFilterDropdown() {
    const dropdown = document.getElementById('filterDropdown');
    const chevron = document.getElementById('filterToggle').querySelector('.fa-chevron-down');
    
    dropdown.style.display = 'none';
    chevron.style.transform = 'rotate(0deg)';
    isFilterOpen = false;
}

function selectFilterOption(value, text) {
    currentFilter = value;
    
    const label = document.getElementById('filterLabel');
    label.textContent = text;
    
    updateFilterOptions();
}

function updateFilterOptions() {
    document.querySelectorAll('.filter-option').forEach(option => {
        const value = option.getAttribute('data-value');
        option.classList.toggle('active', value === currentFilter);
    });
}

// Pagination
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('page-link') || e.target.closest('.page-link')) {
        e.preventDefault();
        e.stopPropagation();
        
        const pageLink = e.target.classList.contains('page-link') ? e.target : e.target.closest('.page-link');
        const pageItem = pageLink.closest('.page-item');
        
        if (pageItem.classList.contains('disabled')) {
            return;
        }
        
        const page = pageLink.getAttribute('data-page');
        if (page && parseInt(page) > 0) {
            const currentScrollPosition = window.pageYOffset || document.documentElement.scrollTop;
            loadReports(currentFilter, parseInt(page));
            setTimeout(() => {
                window.scrollTo(0, currentScrollPosition);
            }, 100);
        }
    }
});

// Report row clicks
document.addEventListener('click', function(e) {
    const reportRow = e.target.closest('.report-row');
    if (reportRow) {
        const reportId = reportRow.getAttribute('data-id');
        showReportDetail(reportId);
    }
});

// Modal functionality
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('image-nav-left')) {
        navigateImage(-1);
    } else if (e.target.classList.contains('image-nav-right')) {
        navigateImage(1);
    } else if (e.target.classList.contains('indicator')) {
        const index = parseInt(e.target.getAttribute('data-index'));
        showImage(index);
    }
});

document.getElementById('reviewBtn').addEventListener('click', function() {
    if (this.disabled || currentReportStatus === 'reviewed') {
        return;
    }
    
    if (currentReportStatus === 'pending') {
        showConfirmationModal();
    }
});

document.getElementById('confirmReviewBtn').addEventListener('click', function() {
    confirmReview();
});

// Audio and Location functionality
let audioTimer = null;
let currentAudioTime = 0;
let totalAudioTime = 300;
let isAudioPlaying = false;
let audioElement = null;

function toggleAudio() {
    const audioIcon = document.getElementById('audioIcon');
    const audioTimeEl = document.getElementById('audioTime');
    const audioIconContainer = audioIcon.parentElement;
    const audioControl = audioIconContainer.parentElement;
    
    if (!currentAudioUrl) {
        console.log('No audio URL available - audio control disabled');
        audioControl.style.cursor = 'not-allowed';
        audioControl.style.opacity = '0.5';
        return;
    }
    
    if (!isAudioPlaying) {
        if (!audioElement) {
            audioElement = new Audio(currentAudioUrl);
            
            audioElement.addEventListener('loadedmetadata', function() {
                totalAudioTime = Math.floor(audioElement.duration) || 300;
                console.log('Audio loaded, duration:', totalAudioTime, 'seconds');
            });
            
            audioElement.addEventListener('timeupdate', function() {
                if (isAudioPlaying) {
                    currentAudioTime = Math.floor(audioElement.currentTime);
                }
            });
            
            audioElement.addEventListener('ended', function() {
                console.log('Audio playback ended');
                stopAudio();
                currentAudioTime = 0;
            });
            
            audioElement.addEventListener('error', function(e) {
                console.error('Audio error:', e);
                stopAudio();
                currentAudioTime = 0;
                totalAudioTime = 0;
            });
        }
        
        audioElement.play().then(() => {
            isAudioPlaying = true;
            audioIcon.className = 'fas fa-pause';
            audioIconContainer.classList.add('playing');
            console.log('Audio started playing');
        }).catch(error => {
            console.error('Audio play failed:', error);
            stopAudio();
        });
    } else {
        if (audioElement) {
            audioElement.pause();
        }
        isAudioPlaying = false;
        audioIcon.className = 'fas fa-play';
        audioIconContainer.classList.remove('playing');
        console.log('Audio paused');
    }
}

function stopAudio() {
    const audioIcon = document.getElementById('audioIcon');
    const audioIconContainer = audioIcon.parentElement;
    
    isAudioPlaying = false;
    audioIcon.className = 'fas fa-play';
    audioIconContainer.classList.remove('playing');
    
    if (audioElement) {
        audioElement.pause();
        audioElement.currentTime = 0;
    }
    
    console.log('Audio stopped');
}

function openLocation() {
    if (currentReportLatitude && currentReportLongitude) {
        const googleMapsUrl = `https://www.google.com/maps?q=${currentReportLatitude},${currentReportLongitude}&z=15`;
        window.open(googleMapsUrl, '_blank');
        console.log('Opening Google Maps with coordinates:', currentReportLatitude, currentReportLongitude);
    } else {
        const googleMapsUrl = 'https://www.google.com/maps/search/Thailand';
        window.open(googleMapsUrl, '_blank');
        console.log('Opening Google Maps with default location');
    }
}

function loadReports(status = '', page = 1) {
    const tbody = document.getElementById('reportsTableBody');
    tbody.innerHTML = '<tr><td colspan="3" class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> กำลังโหลด...</td></tr>';
    
    const url = new URL(window.location.href.split('?')[0]);
    
    if (page && page > 1) {
        url.searchParams.set('page', page);
    }
    
    if (status && status !== '') {
        url.searchParams.set('status', status);
    }
    
    console.log('Loading reports with URL:', url.toString());
    
    fetch(url.toString(), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Received data:', data);
        updateReportsTable(data.reports);
        updatePagination(data.pagination);
        currentFilter = data.current_filter || '';
        window.history.replaceState({}, '', url.toString());
    })
    .catch(error => {
        console.error('Error loading reports:', error);
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle"></i> เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>';
    });
}

function updateReportsTable(reports) {
    const tbody = document.getElementById('reportsTableBody');
    tbody.innerHTML = '';
    
    console.log('Updating table with reports:', reports);
    console.log('Number of reports:', reports.length);
    
    if (reports.length === 0) {
        tbody.innerHTML = `
            <tr class="empty-state">
                <td colspan="3" class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    ไม่พบข้อมูลการรายงาน
                </td>
            </tr>
        `;
        return;
    }
    
    reports.forEach((report, index) => {
        const statusText = report.status === 'reviewed' 
            ? '<span style="color: #006E0B; font-weight: 500;">ตรวจสอบแล้ว</span>'
            : '<span style="color: #D36300; font-weight: 500;">รอตรวจสอบ</span>';
            
        const row = `
            <tr class="report-row" data-id="${report.id}" style="cursor: pointer;">
                <td class="text-center">${report.date}</td>
                <td class="text-center">${report.message.length > 100 ? report.message.substring(0, 100) + '.....' : report.message}</td>
                <td class="text-center">${statusText}</td>
            </tr>
        `;
        tbody.innerHTML += row;
        
        console.log(`Report ${index + 1}:`, report.id, report.status, report.date);
    });
}

function updatePagination(pagination) {
    const paginationEl = document.getElementById('pagination');
    if (!paginationEl) return;
    
    const paginationContainer = paginationEl.parentElement.parentElement.parentElement;
    
    paginationEl.innerHTML = '';
    
    if (pagination.total_pages <= 1 || pagination.total === 0) {
        paginationContainer.style.display = 'none';
        return;
    }
    
    paginationContainer.style.display = 'flex';
    
    const current = parseInt(pagination.current_page);
    const total = parseInt(pagination.total_pages);
    
    console.log('Updating pagination - Current:', current, 'Total:', total);
    
    // Previous arrow
    const prevDisabled = current === 1;
    const prevArrowClass = prevDisabled ? 'page-item disabled' : 'page-item';
    const prevArrowAttrs = prevDisabled ? 'tabindex="-1" aria-disabled="true"' : '';
    const prevPage = Math.max(1, current - 1);
    
    paginationEl.innerHTML += `
        <li class="${prevArrowClass}">
            <a class="page-link page-arrow" href="#" data-page="${prevPage}" ${prevArrowAttrs}>
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>
    `;
    
    // Page numbers
    let start = Math.max(1, current - 4);
    let end = Math.min(total, current + 4);
    
    if (current <= 5) {
        end = Math.min(total, 10);
    } else if (current > total - 5) {
        start = Math.max(1, total - 9);
    }
    
    if (start > 1) {
        paginationEl.innerHTML += `
            <li class="page-item">
                <a class="page-link" href="#" data-page="1">1</a>
            </li>
        `;
        if (start > 2) {
            paginationEl.innerHTML += `
                <li class="page-item disabled">
                    <span class="page-link dots">...</span>
                </li>
            `;
        }
    }
    
    for (let i = start; i <= end; i++) {
        const activeClass = i == current ? 'active' : '';
        paginationEl.innerHTML += `
            <li class="page-item ${activeClass}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `;
    }
    
    if (end < total) {
        if (end < total - 1) {
            paginationEl.innerHTML += `
                <li class="page-item disabled">
                    <span class="page-link dots">...</span>
                </li>
            `;
        }
        paginationEl.innerHTML += `
            <li class="page-item">
                <a class="page-link" href="#" data-page="${total}">${total}</a>
            </li>
        `;
    }
    
    // Next arrow
    const nextDisabled = current === total;
    const nextArrowClass = nextDisabled ? 'page-item disabled' : 'page-item';
    const nextArrowAttrs = nextDisabled ? 'tabindex="-1" aria-disabled="true"' : '';
    const nextPage = Math.min(total, current + 1);
    
    paginationEl.innerHTML += `
        <li class="${nextArrowClass}">
            <a class="page-link page-arrow" href="#" data-page="${nextPage}" ${nextArrowAttrs}>
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    `;
}

function showReportDetail(reportId) {
    fetch(`/api/teacher/behavioral-report/detail/${reportId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            currentReportId = reportId;
            currentReportStatus = data.status;
            currentReportLatitude = data.latitude;
            currentReportLongitude = data.longitude;
            currentAudioUrl = data.audio;
            currentImages = data.images || [];
            currentImageIndex = 0;
            
            console.log('Report detail loaded:', {
                id: reportId,
                imagesCount: currentImages.length,
                hasAudio: !!currentAudioUrl,
                images: currentImages
            });
            
            document.getElementById('reportMessage').textContent = data.message;
            showImage(0);
            updateReviewButton();
            initializeAudio();
            updateAudioControls();
            
            const modal = new bootstrap.Modal(document.getElementById('reportDetailModal'));
            
            // Force backdrop styling after modal is shown
            document.getElementById('reportDetailModal').addEventListener('shown.bs.modal', function() {
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.style.backgroundColor = 'rgba(0, 0, 0, 0.4)';
                    backdrop.style.backdropFilter = 'blur(3px)';
                    backdrop.style.webkitBackdropFilter = 'blur(3px)';
                    backdrop.style.opacity = '1';
                }
            }, { once: true });
            
            modal.show();
        })
        .catch(error => {
            console.error('Error loading report detail:', error);
            alert('ไม่สามารถโหลดรายละเอียดรายงานได้');
        });
}

function initializeAudio() {
    stopAudio();
    currentAudioTime = 0;
    totalAudioTime = 0;
    audioElement = null;
    updateAudioControls();
    
    console.log('Audio initialized');
}

function updateAudioControls() {
    const audioIcon = document.getElementById('audioIcon');
    const audioIconContainer = audioIcon.parentElement;
    const audioControl = audioIconContainer.parentElement;
    
    if (!currentAudioUrl) {
        audioControl.style.cursor = 'not-allowed';
        audioControl.style.opacity = '0.5';
        audioIcon.className = 'fas fa-play';
        audioIconContainer.classList.remove('playing');
        console.log('Audio control disabled - no audio file');
    } else {
        audioControl.style.cursor = 'pointer';
        audioControl.style.opacity = '1';
        audioIcon.className = 'fas fa-play';
        audioIconContainer.classList.remove('playing');
        console.log('Audio control enabled - audio file available');
    }
}

function showImage(index) {
    const imageEl = document.getElementById('currentImage');
    const noImageEl = document.getElementById('noImageMessage');
    const indicatorsEl = document.getElementById('imageIndicators');
    
    if (currentImages.length === 0) {
        imageEl.style.display = 'none';
        noImageEl.style.display = 'block';
        indicatorsEl.style.display = 'none';
        return;
    }
    
    imageEl.style.display = 'block';
    noImageEl.style.display = 'none';
    indicatorsEl.style.display = 'flex';
    
    currentImageIndex = index;
    imageEl.src = currentImages[index];
    
    document.querySelectorAll('.indicator').forEach((indicator, i) => {
        indicator.classList.toggle('active', i === index);
        indicator.style.display = i < currentImages.length ? 'block' : 'none';
    });
}

function navigateImage(direction) {
    if (currentImages.length === 0) return;
    
    const newIndex = currentImageIndex + direction;
    if (newIndex >= 0 && newIndex < currentImages.length) {
        showImage(newIndex);
    } else if (newIndex < 0) {
        showImage(currentImages.length - 1);
    } else {
        showImage(0);
    }
}

function updateReviewButton() {
    const reviewBtn = document.getElementById('reviewBtn');
    
    if (currentReportStatus === 'reviewed') {
        reviewBtn.style.backgroundColor = '#B8B8B8';
        reviewBtn.style.borderColor = '#B8B8B8';
        reviewBtn.style.color = '#FFFFFF';
        reviewBtn.style.opacity = '0.6';
        reviewBtn.style.cursor = 'not-allowed';
        reviewBtn.disabled = true;
        reviewBtn.textContent = 'ตรวจสอบแล้ว';
    } else {
        reviewBtn.style.backgroundColor = '#626DF7';
        reviewBtn.style.borderColor = '#626DF7';
        reviewBtn.style.color = '#FFFFFF';
        reviewBtn.style.opacity = '1';
        reviewBtn.style.cursor = 'pointer';
        reviewBtn.disabled = false;
        reviewBtn.textContent = 'ตรวจสอบ';
    }
}

function showConfirmationModal() {
    // Create custom backdrop for confirmation modal
    const existingBackdrop = document.querySelector('.confirmation-custom-backdrop');
    if (existingBackdrop) {
        existingBackdrop.remove();
    }
    
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show confirmation-custom-backdrop';
    backdrop.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.4) !important;
        backdrop-filter: blur(3px) !important;
        -webkit-backdrop-filter: blur(3px) !important;
        z-index: 1055;
        opacity: 1 !important;
    `;
    
    document.body.appendChild(backdrop);
    
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmationModal'), {
        backdrop: false, // We handle backdrop manually
        keyboard: false
    });
    
    // Handle modal hide to remove custom backdrop
    document.getElementById('confirmationModal').addEventListener('hidden.bs.modal', function() {
        const customBackdrop = document.querySelector('.confirmation-custom-backdrop');
        if (customBackdrop) {
            customBackdrop.remove();
        }
    }, { once: true });
    
    confirmModal.show();
}

function confirmReview() {
    fetch(`/api/teacher/behavioral-report/update-status/${currentReportId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: 'reviewed' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentReportStatus = 'reviewed';
            
            // Hide confirmation modal and remove custom backdrop
            bootstrap.Modal.getInstance(document.getElementById('confirmationModal')).hide();
            const customBackdrop = document.querySelector('.confirmation-custom-backdrop');
            if (customBackdrop) {
                customBackdrop.remove();
            }
            
            // Hide main report modal
            bootstrap.Modal.getInstance(document.getElementById('reportDetailModal')).hide();
            
            const currentPage = document.querySelector('.pagination .page-item.active .page-link')?.getAttribute('data-page') || 1;
            loadReports(currentFilter, currentPage);
        } else {
            alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ');
        }
    })
    .catch(error => {
        console.error('Error updating report status:', error);
        alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    if (currentFilter) {
        const activeOption = document.querySelector(`[data-value="${currentFilter}"]`);
        if (activeOption) {
            const text = activeOption.querySelector('span').textContent;
            selectFilterOption(currentFilter, text);
        }
    } else {
        document.getElementById('filterLabel').textContent = 'ทั้งหมด';
    }
    updateFilterOptions();
    
    const reportModal = document.getElementById('reportDetailModal');
    if (reportModal) {
        reportModal.addEventListener('hidden.bs.modal', function() {
            stopAudio();
        });
    }
    
    // Handle cancel button in confirmation modal
    const confirmationModal = document.getElementById('confirmationModal');
    if (confirmationModal) {
        confirmationModal.addEventListener('hidden.bs.modal', function() {
            const customBackdrop = document.querySelector('.confirmation-custom-backdrop');
            if (customBackdrop) {
                customBackdrop.remove();
            }
        });
        
        // Handle cancel button click
        const cancelBtn = confirmationModal.querySelector('[data-bs-dismiss="modal"]');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                const customBackdrop = document.querySelector('.confirmation-custom-backdrop');
                if (customBackdrop) {
                    customBackdrop.remove();
                }
            });
        }
    }
    
    console.log('Teacher behavioral report page initialized');
    console.log('School:', '{{ $data["school_name"] }}');
    console.log('Current filter:', currentFilter);
});
</script>
@endsection