<script>
let schoolReportChart;
let studentReportsData = {!! json_encode($data['student_reports']) !!};
let currentStudentPage = {{ $data['current_page'] ?? 1 }};
let currentYear = {{ $data['current_year'] }};

const monthLabels = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
const initialSchoolReportData = {!! json_encode($data['school_report_data']) !!};
const schoolName = "{{ $data['school_name'] }}";

function initializeChart(schoolReportData = initialSchoolReportData) {
    const schoolReportCtx = document.getElementById('schoolReportChart').getContext('2d');
    
    if (schoolReportChart) {
        schoolReportChart.destroy();
    }
    
    const maxValue = Math.max(...schoolReportData);
    const dynamicMax = Math.max(5, Math.ceil(maxValue * 1.2));
    
    schoolReportChart = new Chart(schoolReportCtx, {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [
                {
                    label: schoolName,
                    data: schoolReportData,
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
                            return `${schoolName}: ${value}`;
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

document.getElementById('yearSelector').addEventListener('change', function() {
    const selectedYear = parseInt(this.value);
    if (selectedYear && selectedYear !== currentYear) {
        loadDataForYear(selectedYear);
    }
});

async function loadDataForYear(year) {
    try {
        showLoading(true);
        
        const response = await fetch(`/api/teacher/school-data-by-year/${year}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.error) {
            throw new Error(data.error);
        }
        
        initializeChart(data.monthly_data);
        currentYear = year;
        
        console.log(`Successfully loaded data for year ${year}`);
        
    } catch (error) {
        console.error('Error loading data for year:', year, error);
        
        const errorMsg = error.message || 'เกิดข้อผิดพลาดในการโหลดข้อมูล';
        alert(`ไม่สามารถโหลดข้อมูลปี ${year} ได้: ${errorMsg}`);
        
        document.getElementById('yearSelector').value = currentYear;
        
    } finally {
        showLoading(false);
    }
}

function showLoading(show) {
    const loadingOverlay = document.getElementById('chart-loading');
    if (loadingOverlay) {
        loadingOverlay.style.display = show ? 'flex' : 'none';
    }
}

function loadStudentReports(page) {
    currentStudentPage = page;
    
    const tbody = document.getElementById('studentReportsTableBody');
    tbody.innerHTML = '<tr><td colspan="2" class="dashboard-loading"><i class="fas fa-spinner fa-spin"></i> กำลังโหลด...</td></tr>';
    
    setTimeout(() => {
        updateStudentReportsTable(page);
        updateStudentReportsPagination(page);
    }, 300);
}

function updateStudentReportsTable(page) {
    const tbody = document.getElementById('studentReportsTableBody');
    const perPage = 6;
    const startIndex = (page - 1) * perPage;
    const endIndex = startIndex + perPage;
    const paginatedData = studentReportsData.slice(startIndex, endIndex);
    
    tbody.innerHTML = '';
    
    if (paginatedData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="2" class="text-center py-4 text-muted">
                    ไม่พบข้อมูลรายงาน
                </td>
            </tr>
        `;
        return;
    }
    
    paginatedData.forEach(report => {
        const row = `
            <tr>
                <td>${report.date}</td>
                <td>${report.message.length > 60 ? report.message.substring(0, 60) + '.....' : report.message}</td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function updateStudentReportsPagination(currentPage) {
    const totalReports = studentReportsData.length;
    const perPage = 6;
    const totalPages = Math.ceil(totalReports / perPage);
    
    if (totalPages <= 1) {
        return;
    }
    
    const pagination = document.getElementById('studentReportsPagination');
    pagination.innerHTML = '';
    
    const prevDisabled = currentPage === 1;
    const prevClass = prevDisabled ? 'page-item disabled' : 'page-item';
    pagination.innerHTML += `
        <li class="${prevClass}">
            <a class="page-link page-arrow" href="#" onclick="loadStudentReports(${Math.max(1, currentPage - 1)}); return false;">
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>
    `;
    
    for (let i = 1; i <= totalPages; i++) {
        const activeClass = i === currentPage ? 'active' : '';
        pagination.innerHTML += `
            <li class="page-item ${activeClass}">
                <a class="page-link" href="#" onclick="loadStudentReports(${i}); return false;">${i}</a>
            </li>
        `;
    }
    
    const nextDisabled = currentPage === totalPages;
    const nextClass = nextDisabled ? 'page-item disabled' : 'page-item';
    pagination.innerHTML += `
        <li class="${nextClass}">
            <a class="page-link page-arrow" href="#" onclick="loadStudentReports(${Math.min(totalPages, currentPage + 1)}); return false;">
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    `;
}

document.addEventListener('DOMContentLoaded', function() {
    initializeChart();
    
    const totalReports = studentReportsData.length;
    const totalPages = Math.ceil(totalReports / 6);
    if (totalPages > 1) {
        updateStudentReportsPagination(currentStudentPage);
    }
    
    console.log('Teacher dashboard initialized with real data');
    console.log('School:', schoolName);
    console.log('Current year:', currentYear);
});

window.addEventListener('resize', function() {
    if (schoolReportChart) {
        schoolReportChart.resize();
    }
});
</script>