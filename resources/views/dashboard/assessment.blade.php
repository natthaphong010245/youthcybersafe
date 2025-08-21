@extends('layouts.dashboard')

@section('title', 'Assessment - Youth Cybersafe')
@section('page-title', 'Assessment')

@section('content')
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

<!-- Mental Health Assessment -->
<div class="row">
    <div class="col-12">
        <div class="chart-container">
            <h6><strong>Assessment</strong></h6>
            <p class="text-muted small">Mental Health</p>
            <div style="height: 450px; position: relative;">
                <canvas id="mentalHealthChart"></canvas>
            </div>
            <div class="mt-3 d-flex justify-content-center">
                <div class="me-4"><i class="fas fa-circle" style="color: #ff6384;"></i> <small>ภาวะซึมเศร้า</small></div>
                <div class="me-4"><i class="fas fa-circle" style="color: #ffcd56;"></i> <small>ภาวะวิตกกังวล</small></div>
                <div><i class="fas fa-circle" style="color: #9966ff;"></i> <small>ความเครียด</small></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('layouts.dashboard.assessment.script')
@endsection