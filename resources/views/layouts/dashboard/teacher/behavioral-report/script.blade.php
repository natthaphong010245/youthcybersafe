<script>
let currentImageIndex = 0;
let currentImages = [];
let currentReportId = null;
let currentReportStatus = null;
let currentReportLatitude = null;
let currentReportLongitude = null;
let currentAudioUrl = null;
let currentFilter = '{{ $data["current_filter"] ?? "" }}';
let isFilterOpen = false;

document.getElementById('filterToggle').addEventListener('click', function(e) {
    e.stopPropagation();
    toggleFilterDropdown();
});

document.addEventListener('click', function(e) {
    if (isFilterOpen && !e.target.closest('.filter-dropdown') && !e.target.closest('#filterToggle')) {
        closeFilterDropdown();
    }
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.filter-option')) {
        const option = e.target.closest('.filter-option');
        const value = option.getAttribute('data-value');
        const text = option.querySelector('span').textContent;
        
        selectFilterOption(value, text);
        closeFilterDropdown();
        loadReports(value, 1);
    }
});

function toggleFilterDropdown() {
    const dropdown = document.getElementById('filterDropdown');
    const button = document.getElementById('filterToggle');
    const chevron = button.querySelector('.fa-chevron-down');
    
    if (isFilterOpen) {
        closeFilterDropdown();
    } else {
        dropdown.style.display = 'block';
        updateFilterOptions();
        chevron.style.transform = 'rotate(180deg)';
        isFilterOpen = true;
    }
}

function closeFilterDropdown() {
    const dropdown = document.getElementById('filterDropdown');
    const chevron = document.getElementById('filterToggle').querySelector('.fa-chevron-down');
    
    dropdown.style.display = 'none';
    chevron.style.transform = 'rotate(0deg)';
    isFilterOpen = false;
}

function selectFilterOption(value, text) {
    currentFilter = value;
    
    const label = document.getElementById('filterLabel');
    label.textContent = text;
    
    updateFilterOptions();
}

function updateFilterOptions() {
    document.querySelectorAll('.filter-option').forEach(option => {
        const value = option.getAttribute('data-value');
        option.classList.toggle('active', value === currentFilter);
    });
}
 
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('page-link') || e.target.closest('.page-link')) {
        e.preventDefault();
        e.stopPropagation();
        
        const pageLink = e.target.classList.contains('page-link') ? e.target : e.target.closest('.page-link');
        const pageItem = pageLink.closest('.page-item');
        
        if (pageItem.classList.contains('disabled')) {
            return;
        }
        
        const page = pageLink.getAttribute('data-page');
        if (page && parseInt(page) > 0) {
            const currentScrollPosition = window.pageYOffset || document.documentElement.scrollTop;
            loadReports(currentFilter, parseInt(page));
            setTimeout(() => {
                window.scrollTo(0, currentScrollPosition);
            }, 100);
        }
    }
});

document.addEventListener('click', function(e) {
    const reportRow = e.target.closest('.report-row');
    if (reportRow) {
        const reportId = reportRow.getAttribute('data-id');
        showReportDetail(reportId);
    }
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('image-nav-left')) {
        navigateImage(-1);
    } else if (e.target.classList.contains('image-nav-right')) {
        navigateImage(1);
    } else if (e.target.classList.contains('indicator')) {
        const index = parseInt(e.target.getAttribute('data-index'));
        showImage(index);
    }
});

document.getElementById('reviewBtn').addEventListener('click', function() {
    if (this.disabled || currentReportStatus === 'reviewed') {
        return;
    }
    
    if (currentReportStatus === 'pending') {
        showConfirmationModal();
    }
});

document.getElementById('confirmReviewBtn').addEventListener('click', function() {
    confirmReview();
});

let audioTimer = null;
let currentAudioTime = 0;
let totalAudioTime = 300;
let isAudioPlaying = false;
let audioElement = null;

function toggleAudio() {
    const audioIcon = document.getElementById('audioIcon');
    const audioTimeEl = document.getElementById('audioTime');
    const audioIconContainer = audioIcon.parentElement;
    const audioControl = audioIconContainer.parentElement;
    
    if (!currentAudioUrl) {
        console.log('No audio URL available - audio control disabled');
        audioControl.style.cursor = 'not-allowed';
        audioControl.style.opacity = '0.5';
        return;
    }
    
    if (!isAudioPlaying) {
        if (!audioElement) {
            audioElement = new Audio(currentAudioUrl);
            
            audioElement.addEventListener('loadedmetadata', function() {
                totalAudioTime = Math.floor(audioElement.duration) || 300;
                console.log('Audio loaded, duration:', totalAudioTime, 'seconds');
            });
            
            audioElement.addEventListener('timeupdate', function() {
                if (isAudioPlaying) {
                    currentAudioTime = Math.floor(audioElement.currentTime);
                }
            });
            
            audioElement.addEventListener('ended', function() {
                console.log('Audio playback ended');
                stopAudio();
                currentAudioTime = 0;
            });
            
            audioElement.addEventListener('error', function(e) {
                console.error('Audio error:', e);
                stopAudio();
                currentAudioTime = 0;
                totalAudioTime = 0;
            });
        }
        
        audioElement.play().then(() => {
            isAudioPlaying = true;
            audioIcon.className = 'fas fa-pause';
            audioIconContainer.classList.add('playing');
            console.log('Audio started playing');
        }).catch(error => {
            console.error('Audio play failed:', error);
            stopAudio();
        });
    } else {
        if (audioElement) {
            audioElement.pause();
        }
        isAudioPlaying = false;
        audioIcon.className = 'fas fa-play';
        audioIconContainer.classList.remove('playing');
        console.log('Audio paused');
    }
}

function stopAudio() {
    const audioIcon = document.getElementById('audioIcon');
    const audioIconContainer = audioIcon.parentElement;
    
    isAudioPlaying = false;
    audioIcon.className = 'fas fa-play';
    audioIconContainer.classList.remove('playing');
    
    if (audioElement) {
        audioElement.pause();
        audioElement.currentTime = 0;
    }
    
    console.log('Audio stopped');
}

function openLocation() {
    if (currentReportLatitude && currentReportLongitude) {
        const googleMapsUrl = `https://www.google.com/maps?q=${currentReportLatitude},${currentReportLongitude}&z=15`;
        window.open(googleMapsUrl, '_blank');
        console.log('Opening Google Maps with coordinates:', currentReportLatitude, currentReportLongitude);
    } else {
        const googleMapsUrl = 'https://www.google.com/maps/search/Thailand';
        window.open(googleMapsUrl, '_blank');
        console.log('Opening Google Maps with default location');
    }
}

function loadReports(status = '', page = 1) {
    const tbody = document.getElementById('reportsTableBody');
    tbody.innerHTML = '<tr><td colspan="3" class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> กำลังโหลด...</td></tr>';
    
    const url = new URL(window.location.href.split('?')[0]);
    
    if (page && page > 1) {
        url.searchParams.set('page', page);
    }
    
    if (status && status !== '') {
        url.searchParams.set('status', status);
    }
    
    console.log('Loading reports with URL:', url.toString());
    
    fetch(url.toString(), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Received data:', data);
        updateReportsTable(data.reports);
        updatePagination(data.pagination);
        currentFilter = data.current_filter || '';
        window.history.replaceState({}, '', url.toString());
    })
    .catch(error => {
        console.error('Error loading reports:', error);
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle"></i> เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>';
    });
}

function updateReportsTable(reports) {
    const tbody = document.getElementById('reportsTableBody');
    tbody.innerHTML = '';
    
    console.log('Updating table with reports:', reports);
    console.log('Number of reports:', reports.length);
    
    if (reports.length === 0) {
        tbody.innerHTML = `
            <tr class="empty-state">
                <td colspan="3" class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    ไม่พบข้อมูลการรายงาน
                </td>
            </tr>
        `;
        return;
    }
    
    reports.forEach((report, index) => {
        const statusText = report.status === 'reviewed' 
            ? '<span style="color: #006E0B; font-weight: 500;">ตรวจสอบแล้ว</span>'
            : '<span style="color: #D36300; font-weight: 500;">รอตรวจสอบ</span>';
            
        const row = `
            <tr class="report-row" data-id="${report.id}" style="cursor: pointer;">
                <td class="text-center">${report.date}</td>
                <td class="text-center">${report.message.length > 100 ? report.message.substring(0, 100) + '.....' : report.message}</td>
                <td class="text-center">${statusText}</td>
            </tr>
        `;
        tbody.innerHTML += row;
        
        console.log(`Report ${index + 1}:`, report.id, report.status, report.date);
    });
}

function updatePagination(pagination) {
    const paginationEl = document.getElementById('pagination');
    if (!paginationEl) return;
    
    const paginationContainer = paginationEl.parentElement.parentElement.parentElement;
    
    paginationEl.innerHTML = '';
    
    if (pagination.total_pages <= 1 || pagination.total === 0) {
        paginationContainer.style.display = 'none';
        return;
    }
    
    paginationContainer.style.display = 'flex';
    
    const current = parseInt(pagination.current_page);
    const total = parseInt(pagination.total_pages);
    
    console.log('Updating pagination - Current:', current, 'Total:', total);
    
    const prevDisabled = current === 1;
    const prevArrowClass = prevDisabled ? 'page-item disabled' : 'page-item';
    const prevArrowAttrs = prevDisabled ? 'tabindex="-1" aria-disabled="true"' : '';
    const prevPage = Math.max(1, current - 1);
    
    paginationEl.innerHTML += `
        <li class="${prevArrowClass}">
            <a class="page-link page-arrow" href="#" data-page="${prevPage}" ${prevArrowAttrs}>
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>
    `;
    
    let start = Math.max(1, current - 4);
    let end = Math.min(total, current + 4);
    
    if (current <= 5) {
        end = Math.min(total, 10);
    } else if (current > total - 5) {
        start = Math.max(1, total - 9);
    }
    
    if (start > 1) {
        paginationEl.innerHTML += `
            <li class="page-item">
                <a class="page-link" href="#" data-page="1">1</a>
            </li>
        `;
        if (start > 2) {
            paginationEl.innerHTML += `
                <li class="page-item disabled">
                    <span class="page-link dots">...</span>
                </li>
            `;
        }
    }
    
    for (let i = start; i <= end; i++) {
        const activeClass = i == current ? 'active' : '';
        paginationEl.innerHTML += `
            <li class="page-item ${activeClass}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `;
    }
    
    if (end < total) {
        if (end < total - 1) {
            paginationEl.innerHTML += `
                <li class="page-item disabled">
                    <span class="page-link dots">...</span>
                </li>
            `;
        }
        paginationEl.innerHTML += `
            <li class="page-item">
                <a class="page-link" href="#" data-page="${total}">${total}</a>
            </li>
        `;
    }
    
    // Next arrow
    const nextDisabled = current === total;
    const nextArrowClass = nextDisabled ? 'page-item disabled' : 'page-item';
    const nextArrowAttrs = nextDisabled ? 'tabindex="-1" aria-disabled="true"' : '';
    const nextPage = Math.min(total, current + 1);
    
    paginationEl.innerHTML += `
        <li class="${nextArrowClass}">
            <a class="page-link page-arrow" href="#" data-page="${nextPage}" ${nextArrowAttrs}>
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    `;
}

function showReportDetail(reportId) {
    fetch(`/api/teacher/behavioral-report/detail/${reportId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            currentReportId = reportId;
            currentReportStatus = data.status;
            currentReportLatitude = data.latitude;
            currentReportLongitude = data.longitude;
            currentAudioUrl = data.audio;
            currentImages = data.images || [];
            currentImageIndex = 0;
            
            console.log('Report detail loaded:', {
                id: reportId,
                imagesCount: currentImages.length,
                hasAudio: !!currentAudioUrl,
                images: currentImages
            });
            
            document.getElementById('reportMessage').textContent = data.message;
            showImage(0);
            updateReviewButton();
            initializeAudio();
            updateAudioControls();
            
            const modal = new bootstrap.Modal(document.getElementById('reportDetailModal'));
            
            // Force backdrop styling after modal is shown
            document.getElementById('reportDetailModal').addEventListener('shown.bs.modal', function() {
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.style.backgroundColor = 'rgba(0, 0, 0, 0.4)';
                    backdrop.style.backdropFilter = 'blur(3px)';
                    backdrop.style.webkitBackdropFilter = 'blur(3px)';
                    backdrop.style.opacity = '1';
                }
            }, { once: true });
            
            modal.show();
        })
        .catch(error => {
            console.error('Error loading report detail:', error);
            alert('ไม่สามารถโหลดรายละเอียดรายงานได้');
        });
}

function initializeAudio() {
    stopAudio();
    currentAudioTime = 0;
    totalAudioTime = 0;
    audioElement = null;
    updateAudioControls();
    
    console.log('Audio initialized');
}

function updateAudioControls() {
    const audioIcon = document.getElementById('audioIcon');
    const audioIconContainer = audioIcon.parentElement;
    const audioControl = audioIconContainer.parentElement;
    
    if (!currentAudioUrl) {
        audioControl.style.cursor = 'not-allowed';
        audioControl.style.opacity = '0.5';
        audioIcon.className = 'fas fa-play';
        audioIconContainer.classList.remove('playing');
        console.log('Audio control disabled - no audio file');
    } else {
        audioControl.style.cursor = 'pointer';
        audioControl.style.opacity = '1';
        audioIcon.className = 'fas fa-play';
        audioIconContainer.classList.remove('playing');
        console.log('Audio control enabled - audio file available');
    }
}

function showImage(index) {
    const imageEl = document.getElementById('currentImage');
    const noImageEl = document.getElementById('noImageMessage');
    const indicatorsEl = document.getElementById('imageIndicators');
    
    if (currentImages.length === 0) {
        imageEl.style.display = 'none';
        noImageEl.style.display = 'block';
        indicatorsEl.style.display = 'none';
        return;
    }
    
    imageEl.style.display = 'block';
    noImageEl.style.display = 'none';
    indicatorsEl.style.display = 'flex';
    
    currentImageIndex = index;
    imageEl.src = currentImages[index];
    
    document.querySelectorAll('.indicator').forEach((indicator, i) => {
        indicator.classList.toggle('active', i === index);
        indicator.style.display = i < currentImages.length ? 'block' : 'none';
    });
}

function navigateImage(direction) {
    if (currentImages.length === 0) return;
    
    const newIndex = currentImageIndex + direction;
    if (newIndex >= 0 && newIndex < currentImages.length) {
        showImage(newIndex);
    } else if (newIndex < 0) {
        showImage(currentImages.length - 1);
    } else {
        showImage(0);
    }
}

function updateReviewButton() {
    const reviewBtn = document.getElementById('reviewBtn');
    
    if (currentReportStatus === 'reviewed') {
        reviewBtn.style.backgroundColor = '#B8B8B8';
        reviewBtn.style.borderColor = '#B8B8B8';
        reviewBtn.style.color = '#FFFFFF';
        reviewBtn.style.opacity = '0.6';
        reviewBtn.style.cursor = 'not-allowed';
        reviewBtn.disabled = true;
        reviewBtn.textContent = 'ตรวจสอบแล้ว';
    } else {
        reviewBtn.style.backgroundColor = '#626DF7';
        reviewBtn.style.borderColor = '#626DF7';
        reviewBtn.style.color = '#FFFFFF';
        reviewBtn.style.opacity = '1';
        reviewBtn.style.cursor = 'pointer';
        reviewBtn.disabled = false;
        reviewBtn.textContent = 'ตรวจสอบ';
    }
}

function showConfirmationModal() {
    const existingBackdrop = document.querySelector('.confirmation-custom-backdrop');
    if (existingBackdrop) {
        existingBackdrop.remove();
    }
    
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show confirmation-custom-backdrop';
    backdrop.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.4) !important;
        backdrop-filter: blur(3px) !important;
        -webkit-backdrop-filter: blur(3px) !important;
        z-index: 1055;
        opacity: 1 !important;
    `;
    
    document.body.appendChild(backdrop);
    
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmationModal'), {
        backdrop: false,
        keyboard: false
    });
    
    document.getElementById('confirmationModal').addEventListener('hidden.bs.modal', function() {
        const customBackdrop = document.querySelector('.confirmation-custom-backdrop');
        if (customBackdrop) {
            customBackdrop.remove();
        }
    }, { once: true });
    
    confirmModal.show();
}

function confirmReview() {
    fetch(`/api/teacher/behavioral-report/update-status/${currentReportId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: 'reviewed' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentReportStatus = 'reviewed';
            
            bootstrap.Modal.getInstance(document.getElementById('confirmationModal')).hide();
            const customBackdrop = document.querySelector('.confirmation-custom-backdrop');
            if (customBackdrop) {
                customBackdrop.remove();
            }
            
            bootstrap.Modal.getInstance(document.getElementById('reportDetailModal')).hide();
            
            const currentPage = document.querySelector('.pagination .page-item.active .page-link')?.getAttribute('data-page') || 1;
            loadReports(currentFilter, currentPage);
        } else {
            alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ');
        }
    })
    .catch(error => {
        console.error('Error updating report status:', error);
        alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    if (currentFilter) {
        const activeOption = document.querySelector(`[data-value="${currentFilter}"]`);
        if (activeOption) {
            const text = activeOption.querySelector('span').textContent;
            selectFilterOption(currentFilter, text);
        }
    } else {
        document.getElementById('filterLabel').textContent = 'ทั้งหมด';
    }
    updateFilterOptions();
    
    const reportModal = document.getElementById('reportDetailModal');
    if (reportModal) {
        reportModal.addEventListener('hidden.bs.modal', function() {
            stopAudio();
        });
    }
    
    const confirmationModal = document.getElementById('confirmationModal');
    if (confirmationModal) {
        confirmationModal.addEventListener('hidden.bs.modal', function() {
            const customBackdrop = document.querySelector('.confirmation-custom-backdrop');
            if (customBackdrop) {
                customBackdrop.remove();
            }
        });
        
        const cancelBtn = confirmationModal.querySelector('[data-bs-dismiss="modal"]');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                const customBackdrop = document.querySelector('.confirmation-custom-backdrop');
                if (customBackdrop) {
                    customBackdrop.remove();
                }
            });
        }
    }
    
    console.log('Teacher behavioral report page initialized');
    console.log('School:', '{{ $data["school_name"] }}');
    console.log('Current filter:', currentFilter);
});
</script>