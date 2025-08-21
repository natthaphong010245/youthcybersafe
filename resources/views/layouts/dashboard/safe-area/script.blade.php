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
                            return `${context.dataset.label}: ${value} `;
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