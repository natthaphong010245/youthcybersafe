@extends('layouts.dashboard')

@section('title', 'Safe Area - Youth Cybersafe')
@section('page-title', 'Safe Area')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-container">
            <h6><strong>Safe Area</strong></h6>
            <p class="text-muted small">Overview Summary</p>
            
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="stat-card stat-card-blue">
                        <div class="stat-icon stat-icon-blue-custom">
                            <i class="fas fa-microphone"></i>
                        </div>
                        <div class="stat-number" id="voice-count">{{ $data['voice_reports'] }}</div>
                        <div class="stat-label">Voice Report</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card stat-card-pink">
                        <div class="stat-icon stat-icon-pink-custom">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="stat-number" id="message-count">{{ $data['message_reports'] }}</div>
                        <div class="stat-label">Message Report</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Voice and Message Report -->
<div class="row">
    <div class="col-12">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6><strong>Voice and Message</strong></h6>
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
                <canvas id="voiceMessageChart"></canvas>
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
                <div class="me-4"><i class="fas fa-circle" style="color: #4C6FFF;"></i> <small>Voice</small></div>
                <div><i class="fas fa-circle" style="color: #A0A0A0;"></i> <small>Message</small></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
@include('layouts.dashboard.safe-area.style')
@endsection

@section('scripts')
@include('layouts.dashboard.safe-area.script')
@endsection