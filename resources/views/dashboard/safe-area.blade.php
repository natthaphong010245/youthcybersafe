{{-- resources/views/dashboard/safe-area.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Safe Area - Youth Cybersafe')
@section('page-title', 'Safe Area')

@section('content')
<!-- Overview Summary - Updated to match Behavioral Report style -->
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
                        <div class="stat-number">{{ $data['voice_reports'] }}</div>
                        <div class="stat-label">Voice Report</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card stat-card-pink">
                        <div class="stat-icon stat-icon-pink-custom">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="stat-number">{{ $data['message_reports'] }}</div>
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
            <h6><strong>Voice and Message</strong></h6>
            <p class="text-muted small">Report</p>
            <div style="height: 450px; position: relative;">
                <canvas id="voiceMessageChart"></canvas>
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
<style>
/* Custom icon colors for Safe Area */
.stat-icon-blue-custom { 
    background: #0999FF !important;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: white;
    font-size: 1.5rem;
}
.stat-icon-pink-custom { 
    background: #FB5959 !important;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: white;
    font-size: 1.5rem;
}
</style>
@endsection

@section('scripts')
<script>
// Voice and Message Line Chart
const voiceMessageCtx = document.getElementById('voiceMessageChart').getContext('2d');
const monthlyData = {!! json_encode($data['monthly_data']) !!};

// Month labels
const monthLabels = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];

new Chart(voiceMessageCtx, {
    type: 'line',
    data: {
        labels: monthLabels,
        datasets: [
            {
                label: 'Voice',
                data: monthlyData.voice,
                borderColor: '#4C6FFF',
                backgroundColor: 'rgba(76, 111, 255, 0.1)',
                borderWidth: 3,
                fill: false,
                tension: 0.4,
                pointBackgroundColor: '#4C6FFF',
                pointBorderColor: '#4C6FFF',
                pointRadius: 5,
                pointHoverRadius: 7
            },
            {
                label: 'Message',
                data: monthlyData.message,
                borderColor: '#A0A0A0',
                backgroundColor: 'rgba(160, 160, 160, 0.1)',
                borderWidth: 3,
                fill: false,
                tension: 0.4,
                pointBackgroundColor: '#A0A0A0',
                pointBorderColor: '#A0A0A0',
                pointRadius: 5,
                pointHoverRadius: 7
            }
        ]
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
                    color: '#666666'
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
                displayColors: true,
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
                        return `${context.dataset.label}: ${value}`;
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
            point: {
                hoverBorderWidth: 3
            }
        }
    }
});
</script>
@endsection