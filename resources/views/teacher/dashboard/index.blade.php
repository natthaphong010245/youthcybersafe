@extends('layouts.teacher-dashboard')

@section('title', 'Teacher Dashboard - Youth Cybersafe')
@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-5">
        <div class="chart-container dashboard-equal-height">
            <h6><strong>Behavioral Report</strong></h6>
            <p class="text-muted small">Overview Summary</p>
            <div class="dashboard-content-wrapper">
                <div class="row h-100">
                    <div class="col-12 h-100">
                        <div class="stat-card stat-card-pink h-100 d-flex flex-column justify-content-center">
                            <div class="stat-icon stat-icon-pink">
                                <i class="fas fa-school"></i>
                            </div>
                            <div class="stat-number">{{ collect($data['overview'])->first() }}</div>
                            <div class="stat-label">{{ collect($data['overview'])->keys()->first() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="chart-container dashboard-equal-height">
            <h6><strong>List Report</strong></h6>
            <p class="text-muted small">Student</p>
            
            <div class="dashboard-content-wrapper">
                <div class="table-responsive dashboard-table-container">
                    <table class="table student-reports-table">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Date</th>
                                <th style="width: 80%;">Messages</th>
                            </tr>
                        </thead>
                        <tbody id="studentReportsTableBody">
                            @php
                                $paginatedReports = collect($data['student_reports'])->forPage($data['current_page'] ?? 1, 6);
                            @endphp
                            @forelse($paginatedReports as $report)
                            <tr>
                                <td>{{ $report['date'] }}</td>
                                <td>{{ Str::limit($report['message'], 60, '.....') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-muted">
                                    ไม่พบข้อมูลการรายงาน
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @php
                    $totalReports = count($data['student_reports']);
                    $perPage = 6;
                    $totalPages = ceil($totalReports / $perPage);
                    $currentPage = $data['current_page'] ?? 1;
                @endphp
                @if($totalPages > 1)
                <div class="dashboard-pagination-wrapper mt-2">
                    <nav aria-label="Student reports pagination">
                        <ul class="pagination dashboard-pagination" id="studentReportsPagination">
                            <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                <a class="page-link page-arrow" href="#" data-page="{{ max(1, $currentPage - 1) }}" onclick="loadStudentReports({{ max(1, $currentPage - 1) }}); return false;">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            
                            @for($i = 1; $i <= $totalPages; $i++)
                                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                    <a class="page-link" href="#" data-page="{{ $i }}" onclick="loadStudentReports({{ $i }}); return false;">{{ $i }}</a>
                                </li>
                            @endfor
                            
                            <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                                <a class="page-link page-arrow" href="#" data-page="{{ min($totalPages, $currentPage + 1) }}" onclick="loadStudentReports({{ min($totalPages, $currentPage + 1) }}); return false;">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6><strong>{{ $data['school_name'] }}</strong></h6>
                    <p class="text-muted small">Report</p>
                </div>
                <div class="year-selector">
                    <select id="yearSelector" class="form-select" style="width: 120px;">
                        @foreach(range(2025, 2035) as $year)
                            <option value="{{ $year }}" {{ $year == $data['current_year'] ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="height: 450px; position: relative;">
                <canvas id="schoolReportChart"></canvas>
                <div id="chart-loading" class="chart-loading-overlay" style="display: none;">
                    <div class="loading-spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">กำลังโหลดข้อมูล...</p>
                    </div>
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-center">
                <div><i class="fas fa-circle" style="color: #4C6FFF;"></i> <small>{{ $data['school_name'] }}</small></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
@include('layouts.dashboard.teacher.index.style')
@endsection

@section('scripts')
@include('layouts.dashboard.teacher.index.script')
@endsection