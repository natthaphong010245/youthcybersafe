@extends('layouts.dashboard')

@section('title', 'Behavioral Report - Youth Cybersafe')
@section('page-title', 'Behavioral Report')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-container">
            <h6><strong>Behavioral Report</strong></h6>
            <p class="text-muted small">Overview Summary</p>
            <div class="row">
                <div class="col-md-2_4">
                    <div class="stat-card stat-card-blue">
                        <div class="stat-icon stat-icon-blue">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="stat-number">{{ $data['overview']['นักวิจัย'] }}</div>
                        <div class="stat-label">นักวิจัย</div>
                    </div>
                </div>
                <div class="col-md-2_4">
                    <div class="stat-card stat-card-pink">
                        <div class="stat-icon stat-icon-pink">
                            <i class="fas fa-school"></i>
                        </div>
                        <div class="stat-number">{{ $data['overview']['โรงเรียนวาวีวิทยาคม'] }}</div>
                        <div class="stat-label">วารีวิทยาคม</div>
                    </div>
                </div>
                <div class="col-md-2_4">
                    <div class="stat-card stat-card-orange">
                        <div class="stat-icon stat-icon-orange">
                            <i class="fas fa-school"></i>
                        </div>
                        <div class="stat-number">{{ $data['overview']['โรงเรียนสหศาสตร์ศึกษา'] }}</div>
                        <div class="stat-label">สหศาสตร์ศึกษา</div>
                    </div>
                </div>
                <div class="col-md-2_4">
                    <div class="stat-card stat-card-green">
                        <div class="stat-icon stat-icon-green">
                            <i class="fas fa-school"></i>
                        </div>
                        <div class="stat-number">{{ $data['overview']['โรงเรียนราชประชานุเคราะห์ 62'] }}</div>
                        <div class="stat-label">ราชประชานุเคราะห์ 62</div>
                    </div>
                </div>
                <div class="col-md-2_4">
                    <div class="stat-card stat-card-purple">
                        <div class="stat-icon stat-icon-purple">
                            <i class="fas fa-school"></i>
                        </div>
                        <div class="stat-number">{{ $data['overview']['โรงเรียนห้วยไร่สามัคคี'] }}</div>
                        <div class="stat-label">ห้วยไร่สามัคคี</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="chart-container">
            <h6><strong>Schools in Chiang Rai Province</strong></h6>
            <p class="text-muted small">Report</p>
            <div style="height: 450px; position: relative;">
                <canvas id="schoolsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6><strong>Report to Researchers</strong></h6>
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
            
            @if($data['pagination']['total_pages'] > 1)
            <div class="d-flex justify-content-end mt-1">
                <div class="pagination-wrapper">
                    <nav aria-label="Page navigation">
                        <ul class="pagination google-pagination" id="pagination">
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
                                
                                <div class="image-indicators" id="imageIndicators" style="position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; padding: 8px 12px; background: rgba(0,0,0,0.3); border-radius: 20px;">
                                    <span class="indicator active" data-index="0"></span>
                                    <span class="indicator" data-index="1"></span>
                                    <span class="indicator" data-index="2"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="controls-section mt-3 d-flex justify-content-center align-items-center" style="padding: 0 20px; gap: 100px;">
                            <div class="audio-control d-flex flex-column align-items-center" style="cursor: pointer;" onclick="toggleAudio()">
                                <div class="audio-icon mb-2">
                                    <i id="audioIcon" class="fas fa-play" style="color: #626DF7; font-size: 24px;"></i>
                                </div>
                                <span id="audioTime" style="color: #343A81; font-size: 14px; font-weight: 500;">รายงานเสียง</span>
                            </div>
                            
                            <div class="location-control d-flex flex-column align-items-center" style="cursor: pointer;" onclick="openLocation()">
                                <div class="location-icon mb-2">
                                    <i class="fas fa-map-marker-alt" style="color: #626DF7; font-size: 24px;"></i>
                                </div>
                                <span style="color: #343A81; font-size: 14px; font-weight: 500;">ตำแหน่งที่ตั้ง</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6" style="position: relative;">
                        <div class="content-section" style="height: 500px; display: flex; flex-direction: column; justify-content: space-between; margin-right: 30px;">
                            <div class="message-content" style="flex-grow: 1; overflow-y: auto; padding: 0; line-height: 1.6;">
                                <h6 style="color: #343A81; font-size: 18px; font-weight: 600; margin-bottom: 15px;">ข้อความ</h6>
                                <p id="reportMessage" style="color: #343A81; font-size: 16px; margin: 0; padding: 0; line-height: 1.8; word-wrap: break-word;"></p>
                            </div>
                            
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

<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true" data-bs-backdrop="static">
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
@include('layouts.dashboard.behavioral-report.style')
@endsection

@section('scripts')
@include('layouts.dashboard.behavioral-report.script')
@endsection