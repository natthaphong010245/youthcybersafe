@push('scripts')
    <script>
        window.addEventListener('load', function() {
            console.log("Voice recorder script loaded");
            
            if (sessionStorage.getItem('notification_shown')) {
                sessionStorage.removeItem('notification_shown');
            }

            const STATE_INITIAL = 'initial';
            const STATE_RECORDING = 'recording';
            const STATE_PLAYBACK = 'playback';
            let currentState = STATE_INITIAL;

            const recordButton = document.getElementById('recordButton');
            const recordState = document.getElementById('recordState');
            const stopState = document.getElementById('stopState');
            const playState = document.getElementById('playState');
            const timer = document.getElementById('timer');
            const waveContainer = document.getElementById('waveContainer');
            const waveCircle = document.getElementById('waveCircle');
            const deleteButton = document.getElementById('deleteButton');
            const sendButton = document.getElementById('sendButton');
            const progressCircle = document.getElementById('progressCircle');
            const progressArc = document.getElementById('progressArc');
            const recordText = document.getElementById('recordText');
            const confirmModal = document.getElementById('confirmModal');
            const confirmSend = document.getElementById('confirmSend');
            const cancelSend = document.getElementById('cancelSend');
            const modalContent = document.getElementById('modalContent');
            const loadingContent = document.getElementById('loadingContent');

            const CIRCLE_CIRCUMFERENCE = 295.31; 

            let mediaRecorder;
            let audioChunks = [];
            let audioBlob;
            let audioUrl;
            let audio;
            let startTime;
            let timerInterval;
            let recordingDuration = 0;
            let isPlaying = false;
            let visualizationInterval;
            let playbackInterval;

            function updateUI(state) {
                console.log("Updating UI to state:", state);
                currentState = state;

                recordState.classList.add('hidden');
                stopState.classList.add('hidden');
                playState.classList.add('hidden');
                timer.classList.add('hidden');
                deleteButton.classList.add('hidden');
                sendButton.classList.add('hidden');
                waveContainer.classList.add('hidden');
                progressCircle.classList.add('hidden');

                if (state === STATE_INITIAL) {
                    recordButton.classList.remove('bg-[#929AFF]');
                    recordButton.classList.add('bg-gray-100');
                    recordState.classList.remove('hidden');
                    recordText.classList.remove('hidden'); 
                } else if (state === STATE_RECORDING) {
                    recordButton.classList.remove('bg-gray-100');
                    recordButton.classList.add('bg-[#929AFF]');
                    stopState.classList.remove('hidden');
                    timer.classList.remove('hidden');
                    waveContainer.classList.remove('hidden');
                    recordText.classList.add('hidden'); 
                } else if (state === STATE_PLAYBACK) {
                    recordButton.classList.remove('bg-gray-100');
                    recordButton.classList.add('bg-[#929AFF]');
                    playState.classList.remove('hidden');
                    timer.classList.remove('hidden');
                    deleteButton.classList.remove('hidden');
                    sendButton.classList.remove('hidden');
                    progressCircle.classList.remove('hidden');
                    recordText.classList.add('hidden'); 
                    updateProgressArc(0);
                }
            }

            function updateProgressArc(percent) {
                const offset = CIRCLE_CIRCUMFERENCE - (percent / 100 * CIRCLE_CIRCUMFERENCE);
                progressArc.style.strokeDashoffset = offset;
            }

            function formatTime(seconds) {
                if (isNaN(seconds) || !isFinite(seconds)) {
                    return "00:00";
                }
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = Math.floor(seconds % 60);
                return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
            }

            function updateTimer() {
                const currentTime = new Date();
                const elapsedTime = (currentTime - startTime) / 1000;
                recordingDuration = elapsedTime;
                timer.textContent = formatTime(elapsedTime);
            }

            function updatePlaybackTimerCountdown() {
                if (!audio || !isPlaying) return;

                const timeLeft = audio.duration - audio.currentTime;
                timer.textContent = formatTime(timeLeft);

                const percent = (audio.currentTime / audio.duration) * 100;
                updateProgressArc(percent);
            }

            function setupAudioVisualization(stream) {
                try {
                    const audioContext = new(window.AudioContext || window.webkitAudioContext)();
                    const analyser = audioContext.createAnalyser();
                    const microphone = audioContext.createMediaStreamSource(stream);
                    const scriptProcessor = audioContext.createScriptProcessor(2048, 1, 1);

                    analyser.smoothingTimeConstant = 0.8;
                    analyser.fftSize = 1024;

                    microphone.connect(analyser);
                    analyser.connect(scriptProcessor);
                    scriptProcessor.connect(audioContext.destination);

                    scriptProcessor.onaudioprocess = function() {
                        const array = new Uint8Array(analyser.frequencyBinCount);
                        analyser.getByteFrequencyData(array);

                        let values = 0;
                        for (let i = 0; i < array.length; i++) {
                            values += array[i];
                        }

                        const average = values / array.length;
                        const scaledAverage = average / 128; 

                        const scale = 1 + (scaledAverage * 2); 
                        waveCircle.style.transform = `scale(${scale})`;
                        waveCircle.style.opacity = 0.3 - (scaledAverage * 0.15);
                    };

                    return function() {
                        scriptProcessor.disconnect();
                        analyser.disconnect();
                        microphone.disconnect();
                    };
                } catch (error) {
                    console.error("Error setting up audio visualization:", error);
                    return setupSimpleVisualization();
                }
            }

            function setupSimpleVisualization() {
                console.log("Setting up simple visualization");
                if (visualizationInterval) {
                    clearInterval(visualizationInterval);
                }

                waveCircle.style.transition = 'transform 0.1s ease-out, opacity 0.1s ease-out';

                visualizationInterval = setInterval(() => {
                    if (currentState !== STATE_RECORDING) {
                        clearInterval(visualizationInterval);
                        return;
                    }

                    const randomLevel = Math.random() * 1.5;
                    const scale = 1 + randomLevel;
                    waveCircle.style.transform = `scale(${scale})`;
                    waveCircle.style.opacity = 0.3 - (randomLevel * 0.15);
                }, 150);

                return function() {
                    clearInterval(visualizationInterval);
                };
            }

            async function startRecording() {
                try {
                    console.log("Requesting microphone permission...");
                    const stream = await navigator.mediaDevices.getUserMedia({
                        audio: true
                    });
                    console.log("Permission granted, starting recording");

                    audioChunks = [];

                    let mimeType = 'audio/webm';
                    if (MediaRecorder.isTypeSupported('audio/webm;codecs=opus')) {
                        mimeType = 'audio/webm;codecs=opus';
                    }

                    mediaRecorder = new MediaRecorder(stream, {
                        mimeType: mimeType
                    });
                    console.log("MediaRecorder created with type:", mimeType);

                    let cleanupVisualization = null;

                    mediaRecorder.addEventListener('dataavailable', function(event) {
                        console.log("Data available from recorder, chunk size:", event.data.size);
                        audioChunks.push(event.data);
                    });

                    mediaRecorder.addEventListener('stop', function() {
                        console.log("Recording stopped, preparing playback");

                        audioBlob = new Blob(audioChunks, {
                            type: mimeType
                        });
                        console.log("Audio blob created, size:", audioBlob.size);

                        try {
                            audioUrl = URL.createObjectURL(audioBlob);
                            console.log("Audio URL created:", audioUrl);

                            audio = new Audio(audioUrl);

                            let metadataLoaded = false;

                            audio.onloadedmetadata = function() {
                                console.log("Audio loaded successfully, duration:", audio.duration);
                                if (audio.duration > 0 && isFinite(audio.duration)) {
                                    recordingDuration = audio.duration;
                                    if (!isPlaying) {
                                        timer.textContent = formatTime(recordingDuration);
                                    }
                                }
                                metadataLoaded = true;
                            };

                            setTimeout(() => {
                                if (!metadataLoaded) {
                                    console.log("Using recorded duration as fallback:",
                                        recordingDuration);
                                    timer.textContent = formatTime(recordingDuration);
                                }
                            }, 500);

                            audio.onerror = function() {
                                console.error("Error loading audio:", audio.error);
                                alert("ไม่สามารถโหลดไฟล์เสียงได้");
                            };

                            audio.addEventListener('ended', function() {
                                console.log("Audio playback ended");
                                isPlaying = false;
                                clearInterval(playbackInterval);

                                playState.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" viewBox="0 0 24 24" fill="#ffffff">
                            <path d="M8 5.14v14l11-7-11-7z" />
                        </svg>`;
                                updateProgressArc(0);
                                timer.textContent = formatTime(recordingDuration);
                            });

                            updateUI(STATE_PLAYBACK);
                        } catch (e) {
                            console.error("Error creating audio from blob:", e);
                            alert("เกิดข้อผิดพลาดในการสร้างไฟล์เสียง");
                        }

                        stream.getTracks().forEach(track => track.stop());

                        if (cleanupVisualization) {
                            cleanupVisualization();
                        }
                    });

                    mediaRecorder.start(100); 
                    console.log("Recording started");

                    startTime = new Date();
                    timerInterval = setInterval(updateTimer, 100);

                    try {
                        cleanupVisualization = setupAudioVisualization(stream);
                    } catch (e) {
                        console.error("Could not setup audio visualization:", e);
                        cleanupVisualization = setupSimpleVisualization();
                    }

                    updateUI(STATE_RECORDING);
                } catch (error) {
                    console.error('Error starting recording:', error);
                    alert('ไม่สามารถเข้าถึงไมโครโฟนได้ กรุณาตรวจสอบการอนุญาตการใช้งาน');
                }
            }

            function stopRecording() {
                console.log("Stopping recording");
                if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                    console.log("MediaRecorder state before stop:", mediaRecorder.state);
                    mediaRecorder.stop();
                    clearInterval(timerInterval);
                } else {
                    console.warn("Cannot stop recording - MediaRecorder not active");
                }
            }

            function playRecording() {
                console.log("Play button clicked, audio object exists:", !!audio);
                if (audio) {
                    if (isPlaying) {
                        console.log("Pausing audio");
                        audio.pause();
                        isPlaying = false;
                        clearInterval(playbackInterval);

                        playState.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" viewBox="0 0 24 24" fill="#ffffff">
                    <path d="M8 5.14v14l11-7-11-7z" />
                </svg>`;

                        timer.textContent = formatTime(recordingDuration);
                    } else {
                        console.log("Playing audio");

                        playState.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" viewBox="0 0 24 24" fill="#ffffff">
                    <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z" />
                </svg>`;

                        playbackInterval = setInterval(updatePlaybackTimerCountdown, 50);

                        try {
                            audio.currentTime = 0;

                            const playPromise = audio.play();

                            if (playPromise !== undefined) {
                                playPromise.then(() => {
                                    console.log("Audio play promise resolved successfully");
                                    isPlaying = true;
                                }).catch(error => {
                                    console.error("Audio play promise rejected with error:", error);
                                    alert("ไม่สามารถเล่นเสียงได้ โปรดลองใหม่อีกครั้ง");
                                    clearInterval(playbackInterval);
                                    isPlaying = false;
                                });
                            }
                        } catch (e) {
                            console.error("Exception when trying to play audio:", e);
                            alert("เกิดข้อผิดพลาดในการเล่นเสียง");
                            clearInterval(playbackInterval);
                        }
                    }
                } else {
                    console.error("Cannot play - audio object not created or is null");
                    alert("ไม่พบไฟล์เสียงที่บันทึก");
                }
            }

            function deleteRecordingFunc() {
                console.log("Delete recording clicked");
                if (audio) {
                    audio.pause();
                    audio = null;
                }

                if (audioUrl) {
                    URL.revokeObjectURL(audioUrl);
                    audioUrl = null;
                }

                if (playbackInterval) {
                    clearInterval(playbackInterval);
                }

                audioBlob = null;
                audioChunks = [];
                recordingDuration = 0;
                isPlaying = false;

                updateUI(STATE_INITIAL);
            }

            function showConfirmation() {
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

            function sendRecordingFunc() {
                showConfirmation();
            }

            function actualSendRecording() {
                console.log("Send recording confirmed, blob exists:", !!audioBlob);
                if (!audioBlob) {
                    alert("ไม่พบไฟล์เสียงที่บันทึก กรุณาบันทึกใหม่");
                    hideConfirmation();
                    return;
                }

                showLoading();

                console.log("Audio blob type:", audioBlob.type);
                console.log("Audio blob size:", audioBlob.size);

                const formData = new FormData();
                formData.append('audio_file', audioBlob, 'recording.webm');

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error("CSRF token not found");
                    alert("เกิดข้อผิดพลาด: ไม่พบ CSRF token");
                    hideConfirmation();
                    return;
                }

                formData.append('_token', csrfToken.getAttribute('content'));

                fetch('/safe-area/voice/store', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        console.log("Server response status:", response.status);

                        if (response.status >= 200 && response.status < 300) {
                            hideConfirmation();
                            
                            deleteRecordingFunc();
                            
                            showSuccessNotification();
                        } else {
                            return response.text().then(text => {
                                try {
                                    return JSON.parse(text);
                                } catch (e) {
                                    console.log("Response is not JSON:", text);
                                    throw new Error("Server responded with status " + response.status);
                                }
                            });
                        }
                    })
                    .then(data => {
                        if (data && !data.success) {
                            throw new Error(data.message || "Unknown error occurred");
                        }
                    })
                    .catch(error => {
                        console.error('Error saving recording:', error);
                        alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' + error.message);
                        hideConfirmation();
                    });
            }

            recordButton.addEventListener('click', function() {
                console.log("Record button clicked. Current state:", currentState);

                if (currentState === STATE_INITIAL) {
                    startRecording();
                } else if (currentState === STATE_RECORDING) {
                    stopRecording();
                } else if (currentState === STATE_PLAYBACK) {
                    playRecording();
                }
            });

            deleteButton.addEventListener('click', deleteRecordingFunc);
            sendButton.addEventListener('click', sendRecordingFunc);
            confirmSend.addEventListener('click', actualSendRecording);
            cancelSend.addEventListener('click', hideConfirmation);

            updateUI(STATE_INITIAL);

            console.log("Voice recorder fully initialized");
        });
    </script>
@endpush