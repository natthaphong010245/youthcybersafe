{{-- resources/views/dashboard/assessment.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Assessment - Youth Cybersafe')
@section('page-title', 'Assessment')

@section('content')
<!-- Charts Row -->
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
<script>
// Global chart instances
let actionChart, victimChart;

// Function to highlight segments
function highlightSegment(chartId, segmentIndex) {
    const chart = chartId === 'actionChart' ? actionChart : victimChart;
    const chartContainer = chartId === 'actionChart' ? '' : '.victim-chart ';
    
    // Reset all segments to normal
    chart.data.datasets[0].backgroundColor = ['#8593ED', '#4252B8'];
    chart.data.datasets[0].borderWidth = [0, 0];
    
    // Highlight selected segment
    const newColors = ['#8593ED', '#4252B8'];
    const borderWidths = [0, 0];
    
    newColors[segmentIndex] = segmentIndex === 0 ? '#667eea' : '#3a4ba8';
    borderWidths[segmentIndex] = 4;
    
    chart.data.datasets[0].backgroundColor = newColors;
    chart.data.datasets[0].borderWidth = borderWidths;
    chart.data.datasets[0].borderColor = '#ffffff';
    
    chart.update();
    
    // Update legend highlighting
    document.querySelectorAll(`${chartContainer}.legend-item-horizontal`).forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`${chartContainer}[data-segment="${segmentIndex}"]`).classList.add('active');
}

// Function to show tooltip on hover
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
    
    // Create tooltip
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

// Action Experiences Donut Chart
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

// Victim Experiences Donut Chart
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

// Mental Health Vertical Bar Chart (Changed from horizontal to vertical like Image 2)
const mentalCtx = document.getElementById('mentalHealthChart').getContext('2d');
const mentalHealthData = {!! json_encode($data['mental_health_data']) !!};
const labels = Object.keys(mentalHealthData);

// Prepare data for vertical bar chart
const seriousData = [];
const moderateData = [];
const mildData = [];

labels.forEach(level => {
    const data = mentalHealthData[level];
    seriousData.push(data.serious);
    moderateData.push(data.moderate);
    mildData.push(data.mild);
});

new Chart(mentalCtx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'ภาวะซึมเศร้า',
                data: seriousData,
                backgroundColor: '#ff6384',
                borderRadius: 4,
                barThickness: 40
            },
            {
                label: 'ภาวะวิตกกังวล',
                data: moderateData,
                backgroundColor: '#ffcd56',
                borderRadius: 4,
                barThickness: 40
            },
            {
                label: 'ความเครียด',
                data: mildData,
                backgroundColor: '#9966ff',
                borderRadius: 4,
                barThickness: 40
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
                max: 30,
                grid: {
                    display: true,
                    color: '#f0f0f0'
                },
                ticks: {
                    font: {
                        size: 12,
                        weight: '500'
                    },
                    color: '#333333'
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
                    color: '#333333',
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
                        const datasetLabels = ['ภาวะซึมเศร้า', 'ภาวะวิตกกังวล', 'ความเครียด'];
                        const value = context.parsed.y;
                        return `${datasetLabels[context.datasetIndex]}: ${value}`;
                    }
                },
                // Custom tooltip with assessment count display
                position: 'average',
                caretPadding: 10
            }
        },
        animation: {
            duration: 2000,
            easing: 'easeInOutQuart'
        }
    }
});
</script>
@endsection