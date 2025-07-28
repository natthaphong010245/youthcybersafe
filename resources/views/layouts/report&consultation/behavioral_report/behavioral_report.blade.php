<style>
    .dropdown-container {
        position: relative;
        width: 100%;
    }
    
    .select-display {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        background: white;
        cursor: pointer;
        min-height: 48px;
        transition: border-color 0.2s ease;
        font-size: 16px;
    }
    
    .select-display:hover {
        border-color: #9ca3af;
    }
    
    .select-display:focus {
        outline: none;
        border-color: #3E36AE;
        box-shadow: 0 0 0 3px rgba(62, 54, 174, 0.1);
    }
    
    .select-display.disabled {
        background-color: #f9fafb;
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    .select-text {
        font-size: 16px;
        line-height: 1.5;
    }
    
    .dropdown-list {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        z-index: 50;
        max-height: 200px;
        overflow-y: auto;
        margin-top: 2px;
    }
    
    .dropdown-list.show {
        display: block;
    }
    
    .dropdown-item {
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
        cursor: pointer;
        transition: background-color 0.2s ease;
        font-size: 16px;
        line-height: 1.5;
    }
    
    .dropdown-item:hover,
    .dropdown-item.selected {
        background-color: #f3f4f6;
    }
    
    .dropdown-item:last-child {
        border-bottom: none;
    }
    
    .arrow-icon {
        transition: transform 0.2s ease;
        flex-shrink: 0;
    }
    
    .arrow-icon.rotate {
        transform: rotate(180deg);
    }
    
    @media (max-width: 768px) {
        .mobile-select {
            font-size: 16px !important;
            height: 44px;
        }
        
        .select-display {
            min-height: 44px;
            font-size: 16px;
        }
        
        .select-text {
            font-size: 16px;
        }
        
        .dropdown-item {
            font-size: 16px;
            padding: 14px 16px;
        }
    }
    
    .select-display.error {
        border-color: #ef4444;
        border-width: 2px;
    }
    
    .field-error {
        color: #ef4444 !important;
    }
    
    .dropdown-item:focus {
        outline: none;
        background-color: #e5e7eb;
    }
    
    .dropdown-list {
        animation: dropdownFadeIn 0.15s ease-out;
    }
    
    @keyframes dropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Styles for photo preview */
    .image-preview-container {
        position: relative;
        display: inline-block;
    }
    
    .image-preview-container img {
        transition: opacity 0.2s ease;
    }
    
    .image-preview-container:hover img {
        opacity: 0.8;
    }
    
    .image-remove-btn {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        transition: background-color 0.2s ease;
        z-index: 10;
    }
    
    .image-remove-btn:hover {
        background: #dc2626;
    }

    /* Styles for voice recording */
    .voice-recording-container {
        position: relative;
    }
    
    .recording-indicator {
        display: none;
        align-items: center;
        margin-top: 8px;
        color: #ef4444;
        font-size: 14px;
    }
    
    .recording-indicator.active {
        display: flex;
    }
    
    .recording-dot {
        width: 8px;
        height: 8px;
        background: #ef4444;
        border-radius: 50%;
        margin-right: 8px;
        animation: pulse 1s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    /* Loading state */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    
    .loading-overlay.active {
        display: flex;
    }
    
    .loading-spinner {
        background: white;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        text-align: center;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing components');
    
    initCustomSelects();
    initFormValidation();
    
    if (typeof initVoiceRecording === 'function') {
        initVoiceRecording();
    }
    if (typeof initImagePreview === 'function') {
        initImagePreview();
    }
    if (typeof initMap === 'function') {
        initMap();
    }
    if (typeof initSuccessAlert === 'function') {
        initSuccessAlert();
    }
});

function initCustomSelects() {
    console.log('Initializing custom selects');
    
    const reportToDisplay = document.getElementById('reportToDisplay');
    const reportToList = document.getElementById('reportToList');
    const reportToText = document.getElementById('reportToText');
    const reportToInput = document.getElementById('report_to');
    
    const schoolDisplay = document.getElementById('schoolDisplay');
    const schoolList = document.getElementById('schoolList');
    const schoolText = document.getElementById('schoolText');
    const schoolInput = document.getElementById('school');

    if (!reportToDisplay || !reportToList || !reportToText || !reportToInput) {
        console.error('Report To elements not found');
        return;
    }
    
    if (!schoolDisplay || !schoolList || !schoolText || !schoolInput) {
        console.error('School elements not found');
        return;
    }

    console.log('All elements found, setting up event listeners');

    reportToDisplay.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (!this.classList.contains('disabled')) {
            console.log('Report To dropdown clicked');
            toggleDropdown(reportToList, this.querySelector('.arrow-icon'));
            closeDropdown(schoolList, schoolDisplay.querySelector('.arrow-icon'));
        }
    });

    reportToList.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (e.target.classList.contains('dropdown-item')) {
            const value = e.target.getAttribute('data-value');
            const text = e.target.textContent;
            
            console.log('Report To selected:', value, text);
            
            reportToInput.value = value;
            reportToText.textContent = text;
            reportToText.classList.remove('text-gray-500');
            reportToText.classList.add('text-gray-900');
            
            clearFieldError('reportTo');
            
            closeDropdown(reportToList, reportToDisplay.querySelector('.arrow-icon'));
            
            if (value === 'researcher') {
                schoolDisplay.classList.add('disabled');
                schoolInput.value = '';
                schoolText.textContent = 'กรุณาเลือก';
                schoolText.classList.remove('text-gray-900');
                schoolText.classList.add('text-gray-500');
                clearFieldError('school');
                console.log('School disabled for researcher');
            } else {
                schoolDisplay.classList.remove('disabled');
                console.log('School enabled for teacher');
            }
        }
    });

    schoolDisplay.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (!this.classList.contains('disabled')) {
            console.log('School dropdown clicked');
            toggleDropdown(schoolList, this.querySelector('.arrow-icon'));
            closeDropdown(reportToList, reportToDisplay.querySelector('.arrow-icon'));
        }
    });

    schoolList.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (e.target.classList.contains('dropdown-item')) {
            const value = e.target.getAttribute('data-value');
            const text = e.target.textContent;
            
            console.log('School selected:', value, text);
            
            schoolInput.value = value;
            schoolText.textContent = text;
            schoolText.classList.remove('text-gray-500');
            schoolText.classList.add('text-gray-900');
            
            clearFieldError('school');
            
            closeDropdown(schoolList, schoolDisplay.querySelector('.arrow-icon'));
        }
    });

    document.addEventListener('click', function(e) {
        if (!reportToDisplay.contains(e.target) && !reportToList.contains(e.target)) {
            closeDropdown(reportToList, reportToDisplay.querySelector('.arrow-icon'));
        }
        if (!schoolDisplay.contains(e.target) && !schoolList.contains(e.target)) {
            closeDropdown(schoolList, schoolDisplay.querySelector('.arrow-icon'));
        }
    });

    reportToDisplay.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.click();
        }
    });

    schoolDisplay.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.click();
        }
    });

    function toggleDropdown(list, arrow) {
        const isShowing = list.classList.contains('show');
        console.log('Toggling dropdown, currently showing:', isShowing);
        
        list.classList.toggle('show');
        arrow.classList.toggle('rotate');
        
        if (list.classList.contains('show')) {
            const items = list.querySelectorAll('.dropdown-item');
            items.forEach((item, index) => {
                item.setAttribute('tabindex', '0');
                if (index === 0) item.focus();
            });
        }
    }

    function closeDropdown(list, arrow) {
        list.classList.remove('show');
        arrow.classList.remove('rotate');
        
        const items = list.querySelectorAll('.dropdown-item');
        items.forEach(item => {
            item.removeAttribute('tabindex');
        });
    }
}

function initFormValidation() {
    console.log('Initializing form validation');
    
    const form = document.querySelector('form');
    if (!form) {
        console.error('Form not found');
        return;
    }

    form.addEventListener('submit', function(event) {
        console.log('Form submitted, validating...');
        
        const reportTo = document.getElementById('report_to').value;
        const school = document.getElementById('school').value;
        const message = document.getElementById('message').value.trim();
        const latitude = document.getElementById('latitude')?.value;
        const longitude = document.getElementById('longitude')?.value;
        
        let hasError = false;
        
        resetErrorStates();
        
        // ตรวจสอบการรายงาน
        if (!reportTo) {
            event.preventDefault();
            showFieldError('reportTo', 'การรายงาน');
            hasError = true;
            console.log('Validation error: Report To is required');
        }
        
        // ตรวจสอบโรงเรียน (ถ้าเลือกครู)
        if (reportTo && reportTo !== 'researcher' && !school) {
            event.preventDefault();
            showFieldError('school', 'โรงเรียน');
            hasError = true;
            console.log('Validation error: School is required');
        }
        
        // ตรวจสอบข้อความ
        if (!message) {
            event.preventDefault();
            const messageField = document.getElementById('message');
            messageField.classList.add('border-red-500', 'border-2');
            
            // เพิ่มข้อความแจ้งเตือน
            let errorMsg = messageField.parentNode.querySelector('.error-message');
            if (!errorMsg) {
                errorMsg = document.createElement('p');
                errorMsg.className = 'error-message text-red-500 text-sm mt-1';
                errorMsg.textContent = 'กรุณากรอกข้อความ';
                messageField.parentNode.appendChild(errorMsg);
            }
            
            hasError = true;
            console.log('Validation error: Message is required');
        }
        
        // ตรวจสอบตำแหน่ง
        if (latitude !== undefined && longitude !== undefined && (!latitude || !longitude)) {
            event.preventDefault();
            
            const locationPrompt = document.getElementById('locationPrompt');
            if (locationPrompt) {
                locationPrompt.classList.remove('hidden');
            }
            
            const mapContainer = document.getElementById('mapContainer');
            if (mapContainer) {
                mapContainer.scrollIntoView({ behavior: 'smooth' });
                mapContainer.classList.add('ring', 'ring-red-500', 'ring-2');
                
                setTimeout(() => {
                    locationPrompt?.classList.add('hidden');
                    mapContainer.classList.remove('ring', 'ring-red-500', 'ring-2');
                }, 3000);
            }
            hasError = true;
            console.log('Validation error: Location is required');
        }
        
        // ตรวจสอบไฟล์รูปภาพ
        if (typeof validateImageFiles === 'function' && !validateImageFiles()) {
            event.preventDefault();
            hasError = true;
            console.log('Validation error: Image files validation failed');
        }
        
        // ตรวจสอบไฟล์เสียง
        if (typeof validateVoiceRecording === 'function' && !validateVoiceRecording()) {
            event.preventDefault();
            hasError = true;
            console.log('Validation error: Voice recording validation failed');
        }
        
        // เลื่อนไปยัง error แรก
        if (hasError) {
            const firstErrorField = document.querySelector('.field-error, .border-red-500, .error-message');
            if (firstErrorField) {
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else {
            console.log('Form validation passed');
            showLoadingOverlay();
            
            // แสดง loading state บนปุ่ม submit
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    กำลังส่ง...
                `;
                
                // คืนค่าปุ่มหากเกิดข้อผิดพลาด
                setTimeout(() => {
                    if (submitButton.disabled) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        hideLoadingOverlay();
                    }
                }, 30000); // 30 วินาที
            }
        }
    });
    
    // เพิ่ม event listener สำหรับ message field
    const messageField = document.getElementById('message');
    if (messageField) {
        messageField.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('border-red-500', 'border-2');
                this.classList.add('border-gray-300');
                
                // ลบข้อความแจ้งเตือน
                const errorMsg = this.parentNode.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });
    }
}

function resetErrorStates() {
    // Reset report to field
    const reportToLabel = document.querySelector('label[class*="text-[#3E36AE]"]:first-of-type');
    if (reportToLabel) {
        reportToLabel.innerHTML = 'การรายงาน';
        reportToLabel.classList.remove('text-red-500', 'field-error');
        reportToLabel.classList.add('text-[#3E36AE]');
    }
    
    const reportToDropdown = document.getElementById('reportToDisplay');
    if (reportToDropdown) {
        reportToDropdown.classList.remove('border-red-500', 'border-2', 'error');
        reportToDropdown.classList.add('border-gray-300');
    }
    
    // Reset school field
    const schoolLabels = document.querySelectorAll('label[class*="text-"]');
    schoolLabels.forEach(label => {
        if (label.textContent.includes('โรงเรียน')) {
            label.innerHTML = 'โรงเรียน';
            label.classList.remove('text-red-500', 'field-error');
            label.classList.add('text-[#3E36AE]');
        }
    });
    
    const schoolDropdown = document.getElementById('schoolDisplay');
    if (schoolDropdown) {
        schoolDropdown.classList.remove('border-red-500', 'border-2', 'error');
        schoolDropdown.classList.add('border-gray-300');
    }
    
    // Reset message field
    const messageField = document.getElementById('message');
    if (messageField) {
        messageField.classList.remove('border-red-500', 'border-2');
        messageField.classList.add('border-gray-300');
        
        // ลบข้อความแจ้งเตือน
        const errorMsg = messageField.parentNode.querySelector('.error-message');
        if (errorMsg) {
            errorMsg.remove();
        }
    }
}

function showFieldError(fieldType, fieldName) {
    let targetLabel;
    let targetDropdown;
    
    if (fieldType === 'reportTo') {
        targetLabel = document.querySelector('label[class*="text-[#3E36AE]"]:first-of-type');
        targetDropdown = document.getElementById('reportToDisplay');
    } else if (fieldType === 'school') {
        const labels = document.querySelectorAll('label[class*="text-"]');
        labels.forEach(label => {
            if (label.textContent.includes('โรงเรียน')) {
                targetLabel = label;
            }
        });
        targetDropdown = document.getElementById('schoolDisplay');
    }
    
    if (targetLabel) {
        targetLabel.innerHTML = `${fieldName} <span class="text-red-500">*</span>`;
        targetLabel.classList.remove('text-[#3E36AE]');
        targetLabel.classList.add('text-red-500', 'field-error');
    }
    
    if (targetDropdown) {
        targetDropdown.classList.remove('border-gray-300');
        targetDropdown.classList.add('border-red-500', 'border-2', 'error');
    }
}

function clearFieldError(fieldType) {
    let targetLabel;
    let targetDropdown;
    let originalText;
    
    if (fieldType === 'reportTo') {
        targetLabel = document.querySelector('label[class*="text-red-500"], label[class*="field-error"]');
        targetDropdown = document.getElementById('reportToDisplay');
        originalText = 'การรายงาน';
        if (targetLabel && targetLabel.textContent.includes('การรายงาน')) {
            targetLabel.innerHTML = originalText;
            targetLabel.classList.remove('text-red-500', 'field-error');
            targetLabel.classList.add('text-[#3E36AE]');
        }
    } else if (fieldType === 'school') {
        const labels = document.querySelectorAll('label[class*="text-red-500"], label[class*="field-error"]');
        targetDropdown = document.getElementById('schoolDisplay');
        originalText = 'โรงเรียน';
        labels.forEach(label => {
            if (label.textContent.includes('โรงเรียน')) {
                label.innerHTML = originalText;
                label.classList.remove('text-red-500', 'field-error');
                label.classList.add('text-[#3E36AE]');
            }
        });
    }
    
    if (targetDropdown) {
        targetDropdown.classList.remove('border-red-500', 'border-2', 'error');
        targetDropdown.classList.add('border-gray-300');
    }
}

function showLoadingOverlay() {
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="loading-spinner">
                <svg class="animate-spin h-8 w-8 text-[#3E36AE] mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-[#3E36AE] font-medium">กำลังส่งรายงาน...</p>
                <p class="text-gray-500 text-sm mt-2">กรุณารอสักครู่</p>
            </div>
        `;
        document.body.appendChild(overlay);
    }
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function hideLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

function waitForElement(selector, callback) {
    const element = document.querySelector(selector);
    if (element) {
        callback(element);
    } else {
        setTimeout(() => waitForElement(selector, callback), 100);
    }
}

// Image preview functions
function validateImageFiles() {
    const imagePreview = document.getElementById('imagePreview');
    const photosInput = document.getElementById('photos');
    
    if (!imagePreview || !photosInput) {
        return true;
    }
    
    const previewCount = imagePreview.children.length;
    const inputFileCount = photosInput.files ? photosInput.files.length : 0;
    
    // ถ้าไม่มีรูปใน preview ก็ไม่ต้องตรวจสอบ
    if (previewCount === 0) {
        return true;
    }
    
    // ถ้ามีรูปใน preview แต่ไม่มีไฟล์ใน input
    if (previewCount > 0 && inputFileCount === 0) {
        alert('กรุณาเลือกไฟล์รูปภาพใหม่อีกครั้ง หรือลบรูปที่แสดงออกทั้งหมด');
        return false;
    }
    
    return true;
}

// Voice recording functions
function validateVoiceRecording() {
    const audioRecordingInput = document.getElementById('audio_recording');
    const recordedAudio = document.getElementById('recordedAudio');
    
    if (!audioRecordingInput || !recordedAudio) {
        return true;
    }
    
    // ถ้ามีการแสดงเสียงแต่ไม่มีข้อมูลใน input
    if (recordedAudio.src && recordedAudio.src !== '' && !audioRecordingInput.value) {
        alert('กรุณาบันทึกเสียงใหม่อีกครั้ง');
        return false;
    }
    
    return true;
}

// Export functions to global scope
window.initCustomSelects = initCustomSelects;
window.initFormValidation = initFormValidation;
window.resetErrorStates = resetErrorStates;
window.showFieldError = showFieldError;
window.clearFieldError = clearFieldError;
window.validateImageFiles = validateImageFiles;
window.validateVoiceRecording = validateVoiceRecording;
window.showLoadingOverlay = showLoadingOverlay;
window.hideLoadingOverlay = hideLoadingOverlay;
</script>