{{-- resources/views/layouts/report&consultation/behavioral_report/record.blade.php --}}
<script>
function initVoiceRecording() {
    const recordButton = document.getElementById('recordButton');
    const recordedAudio = document.getElementById('recordedAudio');
    let mediaRecorder, audioChunks = [], isRecording = false;

    const micIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
    </svg>`;
    const stopIcon = `<div class="w-4 h-4 bg-red-600 rounded-sm"></div>`;

    recordButton.addEventListener('click', function() {
        if (isRecording) {
            stopRecording();
            updateRecordButton(micIcon, 'bg-[#7F77E0]', ['bg-white', 'border-gray-300']);
        } else {
            startRecording();
            updateRecordButton(stopIcon, 'bg-white border border-gray-300', ['bg-[#7F77E0]']);
        }
        isRecording = !isRecording;
    });

    function updateRecordButton(html, addClasses, removeClasses) {
        recordButton.innerHTML = html;
        recordButton.classList.remove(...removeClasses);
        recordButton.classList.add(...addClasses.split(' '));
    }

    function startRecording() {
        audioChunks = [];
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.start();

                mediaRecorder.addEventListener('dataavailable', event => audioChunks.push(event.data));
                mediaRecorder.addEventListener('stop', () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/mp3' });
                    recordedAudio.src = URL.createObjectURL(audioBlob);

                    const reader = new FileReader();
                    reader.onloadend = () => document.getElementById('audio_recording').value = reader.result;
                    reader.readAsDataURL(audioBlob);
                });
            })
            .catch(error => {
                console.error('Error accessing microphone:', error);
                alert('ไม่สามารถเข้าถึงไมโครโฟนได้ กรุณาตรวจสอบการอนุญาตการใช้ไมโครโฟน');
                isRecording = false;
                updateRecordButton(micIcon, 'bg-[#7F77E0]', ['bg-white', 'border-gray-300']);
            });
    }

    function stopRecording() {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
            mediaRecorder.stream.getTracks().forEach(track => track.stop());
        }
    }
}
</script>