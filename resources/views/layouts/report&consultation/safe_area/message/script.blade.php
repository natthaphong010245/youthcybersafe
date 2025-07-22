@push('scripts')
    <script>
        window.addEventListener('load', function() {
            if (sessionStorage.getItem('notification_shown')) {
                sessionStorage.removeItem('notification_shown');
            }
            
            if (sessionStorage.getItem('message_submitted')) {
                sessionStorage.removeItem('message_submitted');
                window.location.reload();
                return;
            }

            const textarea = document.getElementById('message');

            function resizeTextarea() {
                textarea.style.height = 'auto';
                textarea.style.height = (textarea.scrollHeight + 2) + 'px';
            }

            resizeTextarea();
            textarea.addEventListener('input', resizeTextarea);

            const confirmModal = document.getElementById('confirmModal');
            const modalContent = document.getElementById('modalContent');
            const loadingContent = document.getElementById('loadingContent');
            const submitBtn = document.getElementById('submitBtn');
            const confirmSend = document.getElementById('confirmSend');
            const cancelSend = document.getElementById('cancelSend');
            const messageForm = document.getElementById('messageForm');

            resetModalState();

            window.addEventListener('pageshow', function(event) {
                resetModalState();

                if (event.persisted && sessionStorage.getItem('message_submitted')) {
                    sessionStorage.removeItem('message_submitted');
                    window.location.reload();
                }
            });

            function resetModalState() {
                confirmModal.classList.add('hidden');
                modalContent.classList.remove('hidden');
                loadingContent.classList.add('hidden');

                submitBtn.disabled = false;
            }

            function showConfirmation() {
                if (textarea.value.trim() === '') {
                    alert('กรุณาใส่ข้อความก่อนส่ง');
                    return;
                }

                confirmModal.classList.remove('hidden');
                modalContent.classList.remove('hidden');
                loadingContent.classList.add('hidden');
            }

            function hideConfirmation() {
                confirmModal.classList.add('hidden');
            }

            function showLoading() {
                modalContent.classList.add('hidden');
                loadingContent.classList.remove('hidden');
            }

            function submitForm() {
                showLoading();

                const form = document.getElementById('messageForm');
                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            hideConfirmation();
                            
                            textarea.value = '';
                            resizeTextarea();
                            
                            showSuccessNotification();
                        } else {
                            throw new Error('เกิดข้อผิดพลาดในการส่งข้อความ');
                        }
                    })
                    .catch(error => {
                        resetModalState();
                        alert(error.message);
                    });
            }

            submitBtn.addEventListener('click', showConfirmation);
            confirmSend.addEventListener('click', submitForm);
            cancelSend.addEventListener('click', hideConfirmation);

            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                showConfirmation();
            });
        });
    </script>
@endpush