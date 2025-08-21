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

const behavioralCtx = document.getElementById('behavioralChart').getContext('2d');
const behavioralData = {!! json_encode($data['behavioral_schools']) !!};
const behavioralLabels = Object.keys(behavioralData);
const behavioralValues = Object.values(behavioralData);

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
                    maxRotation: 45, 
                    minRotation: 0,
                    callback: function(value, index) {
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

console.log('Behavioral Chart Data:', {
    labels: behavioralLabels,
    values: behavioralValues,
    colors: behavioralBackgroundColors
});
</script>