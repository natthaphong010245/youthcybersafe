<script>
function initVoiceRecording() {
    const recordButton = document.getElementById('recordButton');
    const recordedAudio = document.getElementById('recordedAudio');
    const audioRecordingInput = document.getElementById('audio_recording');
    let mediaRecorder, audioChunks = [], isRecording = false, recordingStartTime;

    const micIcon = `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
    </svg>`;
    
    const stopIcon = `<div class="w-4 h-4 bg-red-600 rounded-sm animate-pulse"></div>`;
    
    const recordingIcon = `<div class="flex items-center justify-center">
        <div class="w-4 h-4 bg-red-600 rounded-sm animate-pulse"></div>
    </div>`;

    recordButton.addEventListener('click', function() {
        if (isRecording) {
            stopRecording();
        } else {
            startRecording();
        }
    });

    function updateRecordButton(html, addClasses, removeClasses, isRecordingState = false) {
        recordButton.innerHTML = html;
        recordButton.classList.remove(...removeClasses);
        recordButton.classList.add(...addClasses.split(' '));
        
        if (isRecordingState) {
            recordButton.classList.add('animate-pulse');
            recordButton.title = 'คลิกเพื่อหยุดบันทึก';
        } else {
            recordButton.classList.remove('animate-pulse');
            recordButton.title = 'คลิกเพื่อบันทึกเสียง';
        }
    }

    function startRecording() {
        console.log('Starting voice recording...');
        audioChunks = [];
        recordingStartTime = Date.now();
        
        // ตรวจสอบการรองรับ
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('เบราว์เซอร์ของคุณไม่รองรับการบันทึกเสียง');
            return;
        }

        navigator.mediaDevices.getUserMedia({ 
            audio: {
                echoCancellation: true,
                noiseSuppression: true,
                sampleRate: 44100
            } 
        })
        .then(stream => {
            // ตรวจสอบการรองรับ MediaRecorder
            if (!MediaRecorder.isTypeSupported('audio/webm')) {
                console.warn('audio/webm not supported, using default');
            }
            
            const options = {
                mimeType: MediaRecorder.isTypeSupported('audio/webm;codecs=opus') 
                    ? 'audio/webm;codecs=opus' 
                    : MediaRecorder.isTypeSupported('audio/webm') 
                    ? 'audio/webm' 
                    : 'audio/mp4'
            };
            
            mediaRecorder = new MediaRecorder(stream, options);
            
            mediaRecorder.addEventListener('dataavailable', event => {
                if (event.data.size > 0) {
                    audioChunks.push(event.data);
                }
            });
            
            mediaRecorder.addEventListener('stop', () => {
                const recordingDuration = (Date.now() - recordingStartTime) / 1000;
                console.log(`Recording stopped. Duration: ${recordingDuration}s`);
                
                if (audioChunks.length === 0) {
                    alert('ไม่มีข้อมูลเสียงที่บันทึกได้');
                    return;
                }
                
                const mimeType = mediaRecorder.mimeType || 'audio/webm';
                const audioBlob = new Blob(audioChunks, { type: mimeType });
                
                // ตรวจสอบขนาดไฟล์
                const maxSize = 50 * 1024 * 1024; // 50MB
                if (audioBlob.size > maxSize) {
                    alert('ไฟล์เสียงมีขนาดใหญ่เกินไป (สูงสุด 50MB)');
                    return;
                }
                
                console.log(`Audio blob created. Size: ${audioBlob.size} bytes, Type: ${mimeType}`);
                
                // สร้าง URL สำหรับเล่น
                const audioUrl = URL.createObjectURL(audioBlob);
                recordedAudio.src = audioUrl;
                recordedAudio.style.display = 'block';
                
                // แปลงเป็น base64 เพื่อส่งไปยังเซิร์ฟเวอร์
                const reader = new FileReader();
                reader.onloadend = function() {
                    const base64Data = reader.result;
                    audioRecordingInput.value = base64Data;
                    console.log('Audio converted to base64 successfully');
                    
                    // แสดงข้อมูลไฟล์
                    const fileInfo = document.createElement('div');
                    fileInfo.className = 'text-xs text-gray-500 mt-2';
                    fileInfo.innerHTML = `
                        ระยะเวลา: ${recordingDuration.toFixed(1)}s | 
                        ขนาด: ${(audioBlob.size / 1024).toFixed(1)} KB | 
                        ประเภท: ${mimeType}
                    `;
                    
                    // ลบข้อมูลเก่าถ้ามี
                    const oldInfo = recordedAudio.parentNode.querySelector('.text-xs');
                    if (oldInfo) {
                        oldInfo.remove();
                    }
                    
                    recordedAudio.parentNode.appendChild(fileInfo);
                };
                
                reader.onerror = function() {
                    console.error('Error converting audio to base64');
                    alert('เกิดข้อผิดพลาดในการแปลงไฟล์เสียง');
                };
                
                reader.readAsDataURL(audioBlob);
            });
            
            mediaRecorder.addEventListener('error', (event) => {
                console.error('MediaRecorder error:', event.error);
                alert('เกิดข้อผิดพลาดในการบันทึกเสียง: ' + event.error.message);
                resetRecordButton();
            });

            // เริ่มบันทึก
            mediaRecorder.start(1000); // บันทึกทุก 1 วินาที
            isRecording = true;
            updateRecordButton(recordingIcon, 'bg-red-500 border-red-500', ['bg-[#7F77E0]'], true);
            
            console.log('Recording started successfully');
        })
        .catch(error => {
            console.error('Error accessing microphone:', error);
            let errorMessage = 'ไม่สามารถเข้าถึงไมโครโฟนได้';
            
            if (error.name === 'NotAllowedError') {
                errorMessage = 'กรุณาอนุญาตการใช้ไมโครโฟนในเบราว์เซอร์';
            } else if (error.name === 'NotFoundError') {
                errorMessage = 'ไม่พบไมโครโฟนในอุปกรณ์ของคุณ';
            } else if (error.name === 'NotReadableError') {
                errorMessage = 'ไมโครโฟนถูกใช้งานโดยแอปพลิเคชันอื่น';
            }
            
            alert(errorMessage);
            resetRecordButton();
        });
    }

    function stopRecording() {
        console.log('Stopping voice recording...');
        
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
            
            // หยุด stream
            mediaRecorder.stream.getTracks().forEach(track => {
                track.stop();
                console.log('Audio track stopped');
            });
        }
        
        isRecording = false;
        resetRecordButton();
    }
    
    function resetRecordButton() {
        updateRecordButton(micIcon, 'bg-[#7F77E0]', ['bg-red-500', 'border-red-500', 'animate-pulse'], false);
        isRecording = false;
    }

    // ฟังก์ชันล้างการบันทึก
    window.clearVoiceRecording = function() {
        if (isRecording) {
            stopRecording();
        }
        
        recordedAudio.src = '';
        recordedAudio.style.display = 'none';
        audioRecordingInput.value = '';
        
        // ลบข้อมูลไฟล์
        const fileInfo = recordedAudio.parentNode.querySelector('.text-xs');
        if (fileInfo) {
            fileInfo.remove();
        }
        
        console.log('Voice recording cleared');
    };
}

// ฟังก์ชันตรวจสอบไฟล์เสียงก่อนส่งฟอร์ม
function validateVoiceRecording() {
    const audioRecordingInput = document.getElementById('audio_recording');
    const recordedAudio = document.getElementById('recordedAudio');
    
    // ถ้ามีการแสดงเสียงแต่ไม่มีข้อมูลใน input
    if (recordedAudio.src && !audioRecordingInput.value) {
        alert('กรุณาบันทึกเสียงใหม่อีกครั้ง');
        return false;
    }
    
    return true;
}
</script>