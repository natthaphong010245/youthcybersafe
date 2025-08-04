{{-- resources/views/layouts/report&consultation/behavioral_report/photo.blade.php --}}
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
        if (imagePreview.children.length + fileCount > 3) {
            alert('คุณสามารถอัปโหลดได้สูงสุด 3 รูป');
            this.value = '';
            return;
        }

        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'relative';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-20 h-20 object-cover rounded-lg cursor-pointer touch-manipulation';
                img.onclick = () => {
                    fullSizeImage.src = e.target.result;
                    imagePreviewModal.classList.remove('hidden');
                    document.getElementById('mapContainer').style.display = 'none';
                };

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md z-20 touch-manipulation';
                removeBtn.innerHTML = '×';
                removeBtn.style.fontSize = '16px';
                removeBtn.style.fontWeight = 'bold';
                removeBtn.onclick = (event) => {
                    event.stopPropagation();
                    imagePreview.removeChild(imgContainer);
                    if (imagePreview.children.length < 3) {
                        uploadMoreContainer.classList.remove('hidden');
                    }
                };

                imgContainer.appendChild(img);
                imgContainer.appendChild(removeBtn);
                imagePreview.appendChild(imgContainer);

                if (imagePreview.children.length >= 3) {
                    uploadMoreContainer.classList.add('hidden');
                }
            };
            reader.readAsDataURL(file);
        });
    });
}
</script>