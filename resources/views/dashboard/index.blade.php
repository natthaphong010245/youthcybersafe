{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Dashboard - Youth Cybersafe')
@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card stat-card-pink">
                        <div class="stat-icon stat-icon-pink">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="stat-number">{{ $data['stats']['assessment'] }}</div>
                        <div class="stat-label">Assessment</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-card-orange">
                        <div class="stat-icon stat-icon-orange">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div class="stat-number">{{ $data['stats']['mental_health'] }}</div>
                        <div class="stat-label">Mental Health</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-card-green">
                        <div class="stat-icon stat-icon-green">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="stat-number">{{ $data['stats']['behavioral_report'] }}</div>
                        <div class="stat-label">Behavioral Report</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-card-purple">
                        <div class="stat-icon stat-icon-purple">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="stat-number">{{ $data['stats']['safe_area'] }}</div>
                        <div class="stat-label">Safe Area</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Action Experiences -->
    <div class="col-md-6">
        <div class="chart-container">
            <h6><strong>Action Experiences</strong></h6>
            <p class="text-muted small">Cyberbullying Behavior</p>
            <div class="donut-chart">
                <canvas id="actionChart"></canvas>
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: bold;">TOTAL</div>
                    <div style="font-size: 1.8rem; font-weight: bold;">{{ $data['action_experiences']['total'] }}</div>
                </div>
            </div>
            <div class="chart-legend-horizontal">
                <div class="legend-item-horizontal" data-segment="0" onclick="highlightSegment('actionChart', 0)">
                    <span><i class="fas fa-circle" style="color: #8593ED;"></i>มีพฤติกรรมการกลั่นแกล้ง</span>
                    <div class="legend-stats">
                        <div>{{ $data['action_experiences']['assessed'] }} Assessments</div>
                        <div>{{ $data['action_experiences']['percentage'] }}%</div>
                    </div>
                </div>
                <div class="legend-item-horizontal" data-segment="1" onclick="highlightSegment('actionChart', 1)">
                    <span><i class="fas fa-circle" style="color: #4252B8;"></i>ไม่มีพฤติกรรมการกลั่นแกล้ง</span>
                    <div class="legend-stats">
                        <div>{{ $data['action_experiences']['total'] - $data['action_experiences']['assessed'] }} Assessments</div>
                        <div>{{ 100 - $data['action_experiences']['percentage'] }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Victim Experiences -->
    <div class="col-md-6">
        <div class="chart-container victim-chart">
            <h6><strong>Victim Experiences</strong></h6>
            <p class="text-muted small">Cyberbullying Behavior</p>
            <div class="donut-chart">
                <canvas id="victimChart"></canvas>
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: bold;">TOTAL</div>
                    <div style="font-size: 1.8rem; font-weight: bold;">{{ $data['victim_experiences']['total'] }}</div>
                </div>
            </div>
            <div class="chart-legend-horizontal">
                <div class="legend-item-horizontal" data-segment="0" onclick="highlightSegment('victimChart', 0)">
                    <span><i class="fas fa-circle" style="color: #8593ED;"></i>มีพฤติกรรมการถูกกลั่นแกล้ง</span>
                    <div class="legend-stats">
                        <div>{{ $data['victim_experiences']['assessed'] }} Assessments</div>
                        <div>{{ $data['victim_experiences']['percentage'] }}%</div>
                    </div>
                </div>
                <div class="legend-item-horizontal" data-segment="1" onclick="highlightSegment('victimChart', 1)">
                    <span><i class="fas fa-circle" style="color: #4252B8;"></i>ไม่มีพฤติกรรมการถูกกลั่นแกล้ง</span>
                    <div class="legend-stats">
                        <div>{{ $data['victim_experiences']['total'] - $data['victim_experiences']['assessed'] }} Assessments</div>
                        <div>{{ 100 - $data['victim_experiences']['percentage'] }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Assessment Mental Health -->
    <div class="col-md-4">
        <div class="chart-container bottom-row-container">
            <h6><strong>Assessment</strong></h6>
            <p class="text-muted small">Mental Health</p>
            <div style="height: 300px; position: relative;">
                <canvas id="mentalHealthChart"></canvas>
            </div>
            <div class="d-flex justify-content-center">
                <div class="me-4"><i class="fas fa-circle" style="color: #ff6384;"></i> <small class="detial-graph">ภาวะซึมเศร้า</small></div>
                <div class="me-4"><i class="fas fa-circle" style="color: #ffcd56;"></i> <small class="detial-graph">ภาวะวิตกกังวล</small></div>
                <div><i class="fas fa-circle" style="color: #9966ff;"></i> <small class="detial-graph">ความเครียด</small></div>
            </div>
        </div>
    </div>

    <!-- Behavioral Report -->
    <div class="col-md-4">
        <div class="chart-container bottom-row-container">
            <h6><strong>Behavioral Report</strong></h6>
            <p class="text-muted small">Schools in Chiang Rai Province</p>
            <div style="height: 300px; position: relative;">
                <canvas id="behavioralChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Safe Area -->
    <div class="col-md-4">
        <div class="chart-container bottom-row-container">
            <h6><strong>Safe Area</strong></h6>
            <p class="text-muted small">Voice and Message</p>
            <div class="safe-area-cards">
                <div class="safe-area-card safe-area-card-blue">
                    <div class="safe-area-icon safe-area-icon-blue">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <div class="stat-number">{{ $data['safe_area']['voice_reports'] }}</div>
                    <div class="stat-label">Voice Report</div>
                </div>
                <div class="safe-area-card safe-area-card-pink">
                    <div class="safe-area-icon safe-area-icon-pink">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stat-number">{{ $data['safe_area']['message_reports'] }}</div>
                    <div class="stat-label">Message Report</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('layouts.dashboard.index.script')
@endsection