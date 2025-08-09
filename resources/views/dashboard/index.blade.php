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
<script>
let actionChart, victimChart;

function highlightSegment(chartId, segmentIndex) {
    const chart = chartId === 'actionChart' ? actionChart : victimChart;
    const chartContainer = chartId === 'actionChart' ? '' : '.victim-chart ';
    
    chart.data.datasets[0].backgroundColor = ['#8593ED', '#4252B8'];
    chart.data.datasets[0].borderWidth = [0, 0];
    
    const newColors = ['#8593ED', '#4252B8'];
    const borderWidths = [0, 0];
    
    newColors[segmentIndex] = segmentIndex === 0 ? '#667eea' : '#3a4ba8';
    borderWidths[segmentIndex] = 4;
    
    chart.data.datasets[0].backgroundColor = newColors;
    chart.data.datasets[0].borderWidth = borderWidths;
    chart.data.datasets[0].borderColor = '#ffffff';
    
    chart.update();
    
    document.querySelectorAll(`${chartContainer}.legend-item-horizontal`).forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`${chartContainer}[data-segment="${segmentIndex}"]`).classList.add('active');
}

function showTooltip(chartId, segmentIndex, event) {
    const chart = chartId === 'actionChart' ? actionChart : victimChart;
    const data = chartId === 'actionChart' ? 
        { 
            assessed: {{ $data['action_experiences']['assessed'] }}, 
            total: {{ $data['action_experiences']['total'] }},
            percentage: {{ $data['action_experiences']['percentage'] }}
        } : 
        { 
            assessed: {{ $data['victim_experiences']['assessed'] }}, 
            total: {{ $data['victim_experiences']['total'] }},
            percentage: {{ $data['victim_experiences']['percentage'] }}
        };
    
    const labels = ['มีพฤติกรรมการกลั่นแกล้ง', 'ไม่มีพฤติกรรมการกลั่นแกล้ง'];
    const assessments = [data.assessed, data.total - data.assessed];
    const percentages = [data.percentage, 100 - data.percentage];
    
    let tooltip = document.getElementById('custom-tooltip');
    if (!tooltip) {
        tooltip = document.createElement('div');
        tooltip.id = 'custom-tooltip';
        tooltip.className = 'custom-tooltip';
        document.body.appendChild(tooltip);
    }
    
    tooltip.innerHTML = `
        <div class="tooltip-content">
            <div class="tooltip-title">${percentages[segmentIndex]}%</div>
            <div class="tooltip-subtitle">${assessments[segmentIndex]} Assessments</div>
            <div class="tooltip-label">${labels[segmentIndex]}</div>
        </div>
    `;
    
    tooltip.style.display = 'block';
    tooltip.style.left = event.pageX + 10 + 'px';
    tooltip.style.top = event.pageY - 10 + 'px';
}

function hideTooltip() {
    const tooltip = document.getElementById('custom-tooltip');
    if (tooltip) {
        tooltip.style.display = 'none';
    }
}

const actionCtx = document.getElementById('actionChart').getContext('2d');
actionChart = new Chart(actionCtx, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [{{ $data['action_experiences']['percentage'] }}, {{ 100 - $data['action_experiences']['percentage'] }}],
            backgroundColor: ['#8593ED', '#4252B8'],
            borderWidth: [0, 0],
            cutout: '70%',
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { enabled: false }
        },
        onHover: (event, activeElements) => {
            if (activeElements.length > 0) {
                const dataIndex = activeElements[0].index;
                showTooltip('actionChart', dataIndex, event.native);
            } else {
                hideTooltip();
            }
        },
        onClick: (event, activeElements) => {
            if (activeElements.length > 0) {
                const dataIndex = activeElements[0].index;
                highlightSegment('actionChart', dataIndex);
            }
        }
    }
});

const victimCtx = document.getElementById('victimChart').getContext('2d');
victimChart = new Chart(victimCtx, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [{{ $data['victim_experiences']['percentage'] }}, {{ 100 - $data['victim_experiences']['percentage'] }}],
            backgroundColor: ['#8593ED', '#4252B8'],
            borderWidth: [0, 0],
            cutout: '70%',
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { enabled: false }
        },
        onHover: (event, activeElements) => {
            if (activeElements.length > 0) {
                const dataIndex = activeElements[0].index;
                showTooltip('victimChart', dataIndex, event.native);
            } else {
                hideTooltip();
            }
        },
        onClick: (event, activeElements) => {
            if (activeElements.length > 0) {
                const dataIndex = activeElements[0].index;
                highlightSegment('victimChart', dataIndex);
            }
        }
    }
});

const mentalCtx = document.getElementById('mentalHealthChart').getContext('2d');

const mentalHealthData = {!! json_encode($data['mental_health_data']) !!};
const labels = Object.keys(mentalHealthData);
const seriousPercentages = [];
const moderatePercentages = [];
const mildPercentages = [];
const originalDataStore = {};

labels.forEach(level => {
    const data = mentalHealthData[level];
    const total = data.serious + data.moderate + data.mild;
    
    if (total > 0) {
        seriousPercentages.push((data.serious / total) * 100);
        moderatePercentages.push((data.moderate / total) * 100);
        mildPercentages.push((data.mild / total) * 100);
    } else {
        seriousPercentages.push(0);
        moderatePercentages.push(0);
        mildPercentages.push(0);
    }
    
    originalDataStore[level] = data;
});

new Chart(mentalCtx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'ภาวะซึมเศร้า',
                data: seriousPercentages,
                backgroundColor: '#ff6384',
                borderRadius: 0,
                barThickness: 20
            },
            {
                label: 'ภาวะวิตกกังวล',
                data: moderatePercentages,
                backgroundColor: '#ffcd56',
                borderRadius: 0,
                barThickness: 20
            },
            {
                label: 'ความเครียด',
                data: mildPercentages,
                backgroundColor: '#9966ff',
                borderRadius: 0,
                barThickness: 20
            }
        ]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 10,
                top: 10,
                bottom: 10
            }
        },
        interaction: {
            intersect: false,
            mode: 'point'
        },
        elements: {
            bar: {
                borderWidth: 0,
                borderSkipped: false
            }
        },
        scales: {
            x: { 
                display: false,
                beginAtZero: true,
                max: 100,
                stacked: true,
                grid: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    display: false
                },
                border: {
                    display: false
                }
            },
            y: {
                stacked: true,
                grid: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    font: {
                        size: 11,
                        weight: '500'
                    },
                    color: '#333333',
                    padding: 8
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
                padding: 10,
                titleFont: {
                    size: 12,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 11
                },
                callbacks: {
                    title: function(context) {
                        return context[0].label;
                    },
                    label: function(context) {
                        const level = context.label;
                        const datasetIndex = context.datasetIndex;
                        const originalData = originalDataStore[level];
                        const values = [originalData.serious, originalData.moderate, originalData.mild];
                        const datasetLabels = ['ภาวะซึมเศร้า', 'ภาวะวิตกกังวล', 'ความเครียด'];
                        const percentage = Math.round(context.parsed.x * 10) / 10;
                        
                        return `${datasetLabels[datasetIndex]}: ${values[datasetIndex]}`;
                    },
                    afterLabel: function(context) {
                        const percentage = Math.round(context.parsed.x * 10) / 10;
                        return `${percentage}%`;
                    }
                }
            }
        },
        animation: {
            duration: 1500,
            easing: 'easeInOutQuart'
        }
    }
});

// Updated Behavioral Chart with real data from database
const behavioralCtx = document.getElementById('behavioralChart').getContext('2d');
const behavioralData = {!! json_encode($data['behavioral_schools']) !!};
const behavioralLabels = Object.keys(behavioralData);
const behavioralValues = Object.values(behavioralData);

// Updated colors to include blue for researchers
const behavioralBackgroundColors = ['#4252B8', '#FA5A7E', '#FF957A', '#3CD956', '#BF83FF'];

new Chart(behavioralCtx, {
    type: 'bar',
    data: {
        labels: behavioralLabels,
        datasets: [{
            data: behavioralValues,
            backgroundColor: behavioralBackgroundColors,
            borderRadius: 8,
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 10,
                top: 10,
                bottom: 10
            }
        },
        scales: {
            y: { 
                beginAtZero: true,
                max: Math.max(...behavioralValues) > 0 ? Math.ceil(Math.max(...behavioralValues) * 1.3) : 10,
                grid: {
                    display: true,
                    color: '#f0f0f0'
                },
                ticks: {
                    font: {
                        size: 10,
                        weight: '500'
                    },
                    color: '#666666',
                    stepSize: Math.max(1, Math.ceil(Math.max(...behavioralValues) / 5))
                },
                border: {
                    display: false
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 9,
                        weight: '500'
                    },
                    color: '#666666',
                    maxRotation: 45, // Allow rotation for better fit
                    minRotation: 0,
                    callback: function(value, index) {
                        // Ensure all labels are shown
                        return this.getLabelForValue(value);
                    }
                },
                border: {
                    display: false
                }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                enabled: true,
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: 'white',
                bodyColor: 'white',
                borderColor: 'rgba(255, 255, 255, 0.1)',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: false,
                padding: 8,
                titleFont: {
                    size: 11,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 10
                },
                callbacks: {
                    title: function(context) {
                        return context[0].label;
                    },
                    label: function(context) {
                        const value = context.parsed.y;
                        return `จำนวน: ${value}`;
                    }
                }
            }
        },
        elements: {
            bar: {
                borderRadius: 8
            }
        },
        animation: {
            duration: 1500,
            easing: 'easeInOutQuart'
        }
    }
});

// Log data for debugging
console.log('Behavioral Chart Data:', {
    labels: behavioralLabels,
    values: behavioralValues,
    colors: behavioralBackgroundColors
});
</script>
@endsection