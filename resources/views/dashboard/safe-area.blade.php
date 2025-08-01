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
<style>
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

.year-selector select {
    border: 2px solid #e3e6f0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
    font-weight: 500;
    color: #5a5c69;
    background-color: white;
    transition: all 0.3s ease;
}

.year-selector select:focus {
    border-color: #4C6FFF;
    box-shadow: 0 0 0 0.2rem rgba(76, 111, 255, 0.25);
    outline: none;
}

.year-selector select:hover {
    border-color: #4C6FFF;
}

.chart-loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    border-radius: 8px;
}

.loading-spinner {
    text-align: center;
}

.loading-spinner p {
    color: #6c757d;
    margin: 0;
    font-size: 14px;
}

.stat-number {
    transition: all 0.3s ease;
}

.stat-card:hover .stat-number {
    transform: scale(1.05);
}
</style>
@endsection

@section('scripts')
<script>
let voiceMessageChart;
let currentYear = {{ $data['current_year'] }};

const initialMonthlyData = {!! json_encode($data['monthly_data']) !!};
const monthLabels = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];

function initializeChart(monthlyData) {
    const voiceMessageCtx = document.getElementById('voiceMessageChart').getContext('2d');
    
    if (voiceMessageChart) {
        voiceMessageChart.destroy();
    }
    
    const maxVoice = Math.max(...monthlyData.voice);
    const maxMessage = Math.max(...monthlyData.message);
    const maxValue = Math.max(maxVoice, maxMessage);
    const dynamicMax = Math.max(5, Math.ceil(maxValue * 1.2));
    
    voiceMessageChart = new Chart(voiceMessageCtx, {
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
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#4C6FFF',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 2
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
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#A0A0A0',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 2
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
                    max: dynamicMax,
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
                        stepSize: Math.max(1, Math.ceil(dynamicMax / 10)),
                        callback: function(value) {
                            return Number.isInteger(value) ? value : '';
                        }
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
                            return `${context[0].label} ${currentYear}`;
                        },
                        label: function(context) {
                            const value = context.parsed.y;
                            return `${context.dataset.label}: ${value} รายการ`;
                        }
                    },
                    position: 'average',
                    caretPadding: 10
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            },
            elements: {
                point: {
                    hoverBorderWidth: 3
                }
            }
        }
    });
}

async function loadDataForYear(year) {
    try {
        showLoading(true);
        
        const response = await fetch(`/api/safe-area/data-by-year/${year}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.error) {
            throw new Error(data.error);
        }
        
        updateChart(data.monthly_data);
        
        currentYear = year;
        
        console.log(`Successfully loaded data for year ${year}`);
        
    } catch (error) {
        console.error('Error loading data for year:', year, error);
        
        const errorMsg = error.message || 'เกิดข้อผิดพลาดในการโหลดข้อมูล';
        alert(`ไม่สามารถโหลดข้อมูลปี ${year} ได้: ${errorMsg}`);
        
        const currentYearOption = document.querySelector(`#yearSelector option[value="${currentYear}"]`);
        if (currentYearOption) {
            document.getElementById('yearSelector').value = currentYear;
        }
        
    } finally {
        showLoading(false);
    }
}

function updateChart(monthlyData) {
    if (voiceMessageChart) {
        const maxVoice = Math.max(...monthlyData.voice);
        const maxMessage = Math.max(...monthlyData.message);
        const maxValue = Math.max(maxVoice, maxMessage);
        const dynamicMax = Math.max(5, Math.ceil(maxValue * 1.2));
        
        voiceMessageChart.data.datasets[0].data = monthlyData.voice;
        voiceMessageChart.data.datasets[1].data = monthlyData.message;
        
        voiceMessageChart.options.scales.y.max = dynamicMax;
        voiceMessageChart.options.scales.y.ticks.stepSize = Math.max(1, Math.ceil(dynamicMax / 10));
        
        voiceMessageChart.update('active');
    }
}

function showLoading(show) {
    const loadingOverlay = document.getElementById('chart-loading');
    if (loadingOverlay) {
        loadingOverlay.style.display = show ? 'flex' : 'none';
    }
}

document.getElementById('yearSelector').addEventListener('change', function() {
    const selectedYear = parseInt(this.value);
    if (selectedYear && selectedYear !== currentYear) {
        loadDataForYear(selectedYear);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    initializeChart(initialMonthlyData);
    
    console.log('Safe Area dashboard initialized');
    console.log('Initial data:', initialMonthlyData);
});

window.addEventListener('resize', function() {
    if (voiceMessageChart) {
        voiceMessageChart.resize();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const voiceCount = {{ $data['voice_reports'] }};
    const messageCount = {{ $data['message_reports'] }};
    
    animateNumber('voice-count', voiceCount);
    animateNumber('message-count', messageCount);
});

function animateNumber(elementId, targetNumber, duration = 1000) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const startNumber = 0;
    const startTime = performance.now();
    
    function updateNumber(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const easedProgress = 1 - Math.pow(1 - progress, 3);
        
        const currentNumber = Math.round(startNumber + (targetNumber - startNumber) * easedProgress);
        element.textContent = currentNumber;
        
        if (progress < 1) {
            requestAnimationFrame(updateNumber);
        }
    }
    
    requestAnimationFrame(updateNumber);
}
</script>
@endsection