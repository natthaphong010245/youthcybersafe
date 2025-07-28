<script>
function initImagePreview() {
    const uploadMoreContainer = document.getElementById('uploadMoreContainer');
    const photosInput = document.getElementById('photos');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewModal = document.getElementById('imagePreviewModal');
    const fullSizeImage = document.getElementById('fullSizeImage');

    uploadMoreContainer.addEventListener('click', () => photosInput.click());
    document.getElementById('closeImagePreviewModal').addEventListener('click', () => {
        imagePreviewModal.classList.add('hidden');
        document.getElementById('mapContainer').style.display = 'block';
    });

    photosInput.addEventListener('change', function() {
        const fileCount = this.files.length;
        const currentCount = imagePreview.children.length;
        
        if (currentCount + fileCount > 3) {
            alert('คุณสามารถอัปโหลดได้สูงสุด 3 รูป');
            this.value = '';
            return;
        }

        // ตรวจสอบขนาดไฟล์
        for (let i = 0; i < this.files.length; i++) {
            const file = this.files[i];
            const maxSize = 10 * 1024 * 1024; // 10MB
            
            if (file.size > maxSize) {
                alert(`ไฟล์ ${file.name} มีขนาดใหญ่เกินไป (สูงสุด 10MB)`);
                this.value = '';
                return;
            }
            
            // ตรวจสอบประเภทไฟล์
            if (!file.type.startsWith('image/')) {
                alert(`ไฟล์ ${file.name} ไม่ใช่ไฟล์รูปภาพ`);
                this.value = '';
                return;
            }
        }

        Array.from(this.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'relative';
                imgContainer.dataset.fileName = file.name;
                imgContainer.dataset.fileSize = file.size;
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-20 h-20 object-cover rounded-lg cursor-pointer touch-manipulation hover:opacity-80 transition-opacity';
                img.alt = file.name;
                img.onclick = () => {
                    fullSizeImage.src = e.target.result;
                    imagePreviewModal.classList.remove('hidden');
                    document.getElementById('mapContainer').style.display = 'none';
                };

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md z-20 touch-manipulation transition-colors';
                removeBtn.innerHTML = '×';
                removeBtn.style.fontSize = '16px';
                removeBtn.style.fontWeight = 'bold';
                removeBtn.onclick = (event) => {
                    event.stopPropagation();
                    imagePreview.removeChild(imgContainer);
                    updatePhotosInput();
                    if (imagePreview.children.length < 3) {
                        uploadMoreContainer.classList.remove('hidden');
                    }
                };

                // เพิ่มข้อมูลไฟล์
                const fileInfo = document.createElement('div');
                fileInfo.className = 'absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-xs p-1 rounded-b-lg opacity-0 hover:opacity-100 transition-opacity';
                fileInfo.innerHTML = `
                    <div class="truncate">${file.name}</div>
                    <div>${(file.size / 1024).toFixed(1)} KB</div>
                `;

                imgContainer.appendChild(img);
                imgContainer.appendChild(removeBtn);
                imgContainer.appendChild(fileInfo);
                imagePreview.appendChild(imgContainer);

                if (imagePreview.children.length >= 3) {
                    uploadMoreContainer.classList.add('hidden');
                }
            };
            
            reader.onerror = function() {
                alert(`ไม่สามารถอ่านไฟล์ ${file.name} ได้`);
            };
            
            reader.readAsDataURL(file);
        });
        
        // ล้าง input เพื่อให้สามารถเลือกไฟล์เดิมซ้ำได้
        this.value = '';
    });

    // ฟังก์ชันอัปเดต input files เมื่อลบรูป
    function updatePhotosInput() {
        const dt = new DataTransfer();
        const containers = imagePreview.querySelectorAll('[data-file-name]');
        
        // ไม่สามารถอัปเดต input files จาก preview ได้
        // เพราะ security ของ browser ไม่อนุญาต
        // จึงต้องให้ user เลือกไฟล์ใหม่ทั้งหมด
        console.log('Files updated. Current preview count:', containers.length);
    }
}

// เพิ่มฟังก์ชันตรวจสอบไฟล์ก่อนส่งฟอร์ม
function validateImageFiles() {
    const imagePreview = document.getElementById('imagePreview');
    const containers = imagePreview.querySelectorAll('[data-file-name]');
    
    if (containers.length === 0) {
        return true; // ไม่มีรูปก็ได้
    }
    
    const photosInput = document.getElementById('photos');
    if (!photosInput.files || photosInput.files.length === 0) {
        // ถ้ามี preview แต่ไม่มีไฟล์ใน input แสดงว่าผู้ใช้ลบไฟล์ออกแล้ว
        // ให้แจ้งเตือนให้เลือกไฟล์ใหม่
        if (containers.length > 0) {
            alert('กรุณาเลือกไฟล์รูปภาพใหม่อีกครั้ง');
            return false;
        }
    }
    
    return true;
}

// ให้เรียกใช้ validateImageFiles() ใน form validation
</script>

