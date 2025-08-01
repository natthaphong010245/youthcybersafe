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
                <div class="col-md-3">
                    <div class="stat-card stat-card-pink">
                        <div class="stat-icon stat-icon-pink">
                            <i class="fas fa-school"></i>
                        </div>
                        <div class="stat-number">{{ $data['overview']['โรงเรียนวาวีวิทยาคม'] }}</div>
                        <div class="stat-label">วารีวิทยาคม</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-card-orange">
                        <div class="stat-icon stat-icon-orange">
                            <i class="fas fa-school"></i>
                        </div>
                        <div class="stat-number">{{ $data['overview']['โรงเรียนสหศาสตร์ศึกษา'] }}</div>
                        <div class="stat-label">สหศาสตร์ศึกษา</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card stat-card-green">
                        <div class="stat-icon stat-icon-green">
                            <i class="fas fa-school"></i>
                        </div>
                        <div class="stat-number">{{ $data['overview']['โรงเรียนราชประชานุเคราะห์ 62'] }}</div>
                        <div class="stat-label">ราชประชานุเคราะห์ 62</div>
                    </div>
                </div>
                <div class="col-md-3">
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

<div class="row">
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
@endsection

@section('scripts')
<script>
const schoolsCtx = document.getElementById('schoolsChart').getContext('2d');
const schoolsData = {!! json_encode($data['schools_data']) !!};
const labels = Object.keys(schoolsData);
const values = Object.values(schoolsData);

const backgroundColors = ['#FA5A7E', '#FF957A', '#3CD956', '#BF83FF', '#4252B8'];

new Chart(schoolsCtx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            data: values,
            backgroundColor: backgroundColors,
            borderRadius: 8,
            borderSkipped: false,
            barThickness: 60
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 20,
                right: 20,
                top: 20,
                bottom: 20
            }
        },
        interaction: {
            intersect: false,
            mode: 'index'
        },
        scales: {
            y: { 
                beginAtZero: true,
                max: 40,
                grid: {
                    display: true,
                    color: '#f0f0f0',
                    lineWidth: 1
                },
                ticks: {
                    font: {
                        size: 12,
                        weight: '500'
                    },
                    color: '#666666',
                    stepSize: 5
                },
                border: {
                    display: false
                }
            },
            x: {
                grid: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    font: {
                        size: 12,
                        weight: '500'
                    },
                    color: '#666666',
                    maxRotation: 0
                },
                border: {
                    display: false
                }
            }
        },
        plugins: {
            legend: { 
                display: false 
            },
            tooltip: {
                enabled: true,
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: 'white',
                bodyColor: 'white',
                borderColor: 'rgba(255, 255, 255, 0.1)',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: false,
                padding: 12,
                titleFont: {
                    size: 14,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 12
                },
                callbacks: {
                    title: function(context) {
                        return context[0].label;
                    },
                    label: function(context) {
                        const value = context.parsed.y;
                        return `จำนวน: ${value}`;
                    }
                },
                position: 'average',
                caretPadding: 10
            }
        },
        animation: {
            duration: 2000,
            easing: 'easeInOutQuart'
        },
        elements: {
            bar: {
                borderRadius: 8
            }
        }
    }
});
</script>
@endsection