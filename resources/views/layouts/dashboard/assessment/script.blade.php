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

const seriousData = [];
const moderateData = [];
const mildData = [];

labels.forEach(level => {
    const data = mentalHealthData[level];
    seriousData.push(data.serious);
    moderateData.push(data.moderate);
    mildData.push(data.mild);
});

const allValues = [...seriousData, ...moderateData, ...mildData];
const maxValue = Math.max(...allValues);
const dynamicMax = maxValue > 0 ? Math.ceil(maxValue * 1.3) : 10;

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
                max: dynamicMax,
                grid: {
                    display: true,
                    color: '#f0f0f0'
                },
                ticks: {
                    font: {
                        size: 12,
                        weight: '500'
                    },
                    color: '#333333',
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
                        return `${datasetLabels[context.datasetIndex]}: ${value} `;
                    }
                },
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

console.log('Mental Health Chart Info:');
console.log('Max value in data:', maxValue);
console.log('Dynamic max used:', dynamicMax);
console.log('Step size:', Math.max(1, Math.ceil(dynamicMax / 10)));
console.log('Data distribution:', {
    serious: seriousData,
    moderate: moderateData,
    mild: mildData
});
</script>