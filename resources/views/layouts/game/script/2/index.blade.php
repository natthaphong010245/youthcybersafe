{{-- resources/views/layouts/game/script/2/index.blade.php --}}
<script>
    // เพิ่มการจัดการเมื่อผู้เล่นย้อนกลับหน้า
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    });

    window.addEventListener('beforeunload', function() {
        sessionStorage.setItem('gamePageLeft', 'true');
    });

    window.addEventListener('load', function() {
        if (sessionStorage.getItem('gamePageLeft') === 'true') {
            sessionStorage.removeItem('gamePageLeft');
            resetGameState();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const introModal = document.getElementById('intro-modal');
        const gameContent = document.getElementById('game-content');
        const startGameBtn = document.getElementById('start-game-btn');
        const answerCloudsContainer = document.getElementById('answer-clouds-container');
        const dropZonesContainer = document.getElementById('drop-zones-container');
        const correctOverlay = document.getElementById('correct-overlay');
        const wrongOverlay = document.getElementById('wrong-overlay');
        const completeOverlay = document.getElementById('complete-overlay');

        const correctAnswers = ['เจตนา', 'เจ็บปวด', 'จงใจทำซ้ำ'];
        const wrongAnswers = ['จิ๊จ๊ะ', 'จ๊ะจ๋า'];
        const allAnswers = [...correctAnswers, ...wrongAnswers];

        const answerImages = {
            'เจตนา': 'images/game/2/intent.png',
            'เจ็บปวด': 'images/game/2/painful.png',
            'จงใจทำซ้ำ': 'images/game/2/intentionally_repetitive.png',
            'จิ๊จ๊ะ': 'images/game/2/jijja.png',
            'จ๊ะจ๋า': 'images/game/2/jaja.png'
        };

        let completedZones = 0;
        let gameInitialized = false;
        let usedAnswers = new Set();

        // ปรับตำแหน่ง clouds ให้อยู่ในกรอบและเหมาะสมกับมือถือ
        const cloudPositions = [
            { position: 'top-2 left-4', size: 'w-28 h-auto' },
            { position: 'top-2 right-4', size: 'w-24 h-auto' },
            { position: 'top-16 left-8', size: 'w-32 h-auto' },
            { position: 'top-20 right-8', size: 'w-28 h-auto' },
            { position: 'top-32 left-1/2 transform -translate-x-1/2', size: 'w-30 h-auto' }
        ];

        // ฟังก์ชันรีเซ็ตสถานะเกม
        function resetGameState() {
            // ซ่อน modal ทั้งหมด
            [correctOverlay, wrongOverlay, completeOverlay].forEach(modal => {
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('animate-fadeIn', 'animate-fadeOut');
                }
            });

            // รีเซ็ตสถานะเกม
            completedZones = 0;
            usedAnswers.clear();
            gameInitialized = false;

            // รีเซ็ต drop zones
            document.querySelectorAll('.drop-zone').forEach(zone => {
                zone.innerHTML = '<img src="{{ asset("images/material/cloud.png") }}" alt="Empty Cloud" class="w-32 h-22 opacity-30">';
            });

            // รีเซ็ต game content
            if (gameContent) {
                gameContent.classList.remove('game-blur', 'animate-unblur');
            }

            // รีเซ็ต container
            if (answerCloudsContainer) {
                answerCloudsContainer.innerHTML = '';
            }

            // แสดง intro modal อีกครั้งถ้ายังมี
            if (introModal && !introModal.style.display === 'none') {
                gameContent.classList.add('game-blur');
                setTimeout(() => {
                    introModal.classList.add('animate-modal-show');
                }, 100);
            }
        }

        // เรียกใช้ resetGameState ตอนเริ่มต้น
        resetGameState();

        setTimeout(() => {
            introModal.classList.add('animate-modal-show');
            gameContent.classList.add('game-blur');
        }, 100);

        startGameBtn.addEventListener('click', function() {
            introModal.classList.remove('animate-modal-show');
            introModal.classList.add('animate-modal-fade-out');

            setTimeout(() => {
                introModal.style.display = 'none';
                gameContent.classList.remove('game-blur');
                gameContent.classList.add('animate-unblur');

                if (!gameInitialized) {
                    createAnswerClouds();
                    gameInitialized = true;
                }
            }, 300);
        });

        function shuffleArray(array) {
            const shuffled = [...array];
            for (let i = shuffled.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
            }
            return shuffled;
        }

        function createAnswerClouds() {
            answerCloudsContainer.innerHTML = '';
            const shuffledAnswers = shuffleArray(allAnswers);
            const shuffledPositions = shuffleArray(cloudPositions);

            shuffledAnswers.forEach((answer, index) => {
                const cloud = document.createElement('div');
                const position = shuffledPositions[index];

                const hasTransform = position.size.includes('transform');
                const sizeClasses = position.size.replace('transform -translate-x-1/2', '').trim();

                cloud.className =
                    `absolute ${position.position} ${sizeClasses} cursor-pointer answer-cloud`;
                if (hasTransform) {
                    cloud.classList.add('transform', '-translate-x-1/2');
                }

                cloud.dataset.answer = answer;
                cloud.dataset.originalIndex = index;

                cloud.innerHTML = `
                    <div class="relative w-full h-full">
                        <img src="{{ asset("") }}${answerImages[answer]}" alt="Cloud" class="w-full h-full">
                    </div>
                `;

                // Add click event listener
                cloud.addEventListener('click', handleCloudClick);

                answerCloudsContainer.appendChild(cloud);
            });
        }

        function handleCloudClick(e) {
            const clickedCloud = e.target.closest('.answer-cloud');
            if (!clickedCloud) return;

            const answer = clickedCloud.dataset.answer;

            // Check if answer is already used
            if (usedAnswers.has(answer)) {
                return;
            }

            // Add visual feedback
            clickedCloud.style.transform = 'scale(0.95)';
            setTimeout(() => {
                if (clickedCloud.classList.contains('transform')) {
                    clickedCloud.style.transform = 'translateX(-50%) scale(1)';
                } else {
                    clickedCloud.style.transform = 'scale(1)';
                }
            }, 150);

            if (correctAnswers.includes(answer)) {
                // Find next available drop zone
                const nextDropZone = findNextAvailableDropZone();
                
                if (nextDropZone) {
                    placeCloudInZone(clickedCloud, nextDropZone, answer);
                    usedAnswers.add(answer);
                    completedZones++;

                    if (completedZones < 3) {
                        showCorrectModal();
                    } else {
                        showCompleteModal();
                    }
                }
            } else {
                showWrongModal();
                // Add shake animation for wrong answer
                clickedCloud.classList.add('shake-animation');
                setTimeout(() => {
                    clickedCloud.classList.remove('shake-animation');
                }, 600);
            }
        }

        function findNextAvailableDropZone() {
            const dropZones = document.querySelectorAll('.drop-zone');
            for (let zone of dropZones) {
                if (!zone.querySelector('.placed-cloud')) {
                    return zone;
                }
            }
            return null;
        }

        function placeCloudInZone(cloud, zone, answer) {
            // Animate the cloud moving to the zone
            const cloudRect = cloud.getBoundingClientRect();
            const zoneRect = zone.getBoundingClientRect();
            
            // Create a flying cloud animation
            const flyingCloud = cloud.cloneNode(true);
            flyingCloud.style.position = 'fixed';
            flyingCloud.style.left = cloudRect.left + 'px';
            flyingCloud.style.top = cloudRect.top + 'px';
            flyingCloud.style.zIndex = '1000';
            flyingCloud.style.pointerEvents = 'none';
            flyingCloud.style.transition = 'all 0.6s ease-out';
            
            document.body.appendChild(flyingCloud);
            
            // Hide original cloud
            cloud.style.opacity = '0.3';
            cloud.style.pointerEvents = 'none';
            
            // Animate to drop zone
            setTimeout(() => {
                flyingCloud.style.left = (zoneRect.left + (zoneRect.width - cloudRect.width) / 2) + 'px';
                flyingCloud.style.top = (zoneRect.top + (zoneRect.height - cloudRect.height) / 2) + 'px';
                flyingCloud.style.transform = 'scale(0.8)';
            }, 50);
            
            // Place in zone after animation
            setTimeout(() => {
                zone.innerHTML = `<img src="{{ asset("") }}${answerImages[answer]}" alt="Cloud" class="w-32 h-22 placed-cloud">`;
                document.body.removeChild(flyingCloud);
            }, 650);
        }

        function showCorrectModal() {
            correctOverlay.classList.remove('hidden');
            correctOverlay.classList.add('animate-fadeIn');
        }

        function showWrongModal() {
            wrongOverlay.classList.remove('hidden');
            wrongOverlay.classList.add('animate-fadeIn');
        }

        function showCompleteModal() {
            completeOverlay.classList.remove('hidden');
            completeOverlay.classList.add('animate-fadeIn');
        }

        function hideModal(modal) {
            modal.classList.remove('animate-fadeIn');
            modal.classList.add('animate-fadeOut');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('animate-fadeOut');
            }, 500);
        }

        function resetGame() {
            completedZones = 0;
            usedAnswers.clear();
            
            document.querySelectorAll('.drop-zone').forEach(zone => {
                zone.innerHTML = '<img src="{{ asset("images/material/cloud.png") }}" alt="Empty Cloud" class="w-32 h-22 opacity-30">';
            });
            
            document.querySelectorAll('.answer-cloud').forEach(cloud => {
                cloud.style.opacity = '1';
                cloud.style.pointerEvents = 'auto';
            });
        }

        document.getElementById('continue-correct-btn').addEventListener('click', function() {
            hideModal(correctOverlay);
        });

        document.getElementById('try-again-wrong-btn').addEventListener('click', function() {
            hideModal(wrongOverlay);
        });

        document.getElementById('skip-btn').addEventListener('click', function() {
            hideModal(wrongOverlay);
            setTimeout(() => {
                window.location.href = "{{ route('game_3') }}";
            }, 300);
        });

        document.getElementById('finish-game-btn').addEventListener('click', function() {
            window.location.href = "{{ route('game_3') }}";
        });

        // เปิดฟังก์ชัน resetGameState ให้ global scope
        window.resetGameState = resetGameState;
    });
</script>

<style>
    .animate-modal-show .modal-backdrop {
        animation: backdropFadeIn 0.3s ease-out forwards;
    }

    .animate-modal-show .modal-content {
        animation: contentSlideIn 0.4s ease-out 0.15s both;
    }

    .animate-modal-hide {
        animation: modalHide 0.3s ease-out forwards;
    }

    .animate-modal-fade-out {
        animation: backdropFadeOut 0.3s ease-out forwards;
    }

    .animate-modal-fade-out .modal-content {
        animation: contentScaleOut 0.3s ease-out forwards;
    }

    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }

    @keyframes backdropFadeIn {
        0% {
            background-color: rgba(0, 0, 0, 0);
        }
        100% {
            background-color: rgba(0, 0, 0, 0.4);
        }
    }

    @keyframes contentSlideIn {
        0% {
            opacity: 0;
            transform: scale(0.8);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes modalHide {
        0% {
            opacity: 1;
            transform: scale(1);
        }
        100% {
            opacity: 0;
            transform: scale(0.8);
        }
    }

    @keyframes backdropFadeOut {
        0% {
            background-color: rgba(0, 0, 0, 0.4);
        }
        100% {
            background-color: rgba(0, 0, 0, 0);
            visibility: hidden;
        }
    }

    @keyframes contentScaleOut {
        0% {
            opacity: 1;
            transform: scale(1);
        }
        100% {
            opacity: 0;
            transform: scale(0.3);
        }
    }

    .game-blur {
        filter: blur(3px);
        transition: filter 0.3s ease-out;
        transform: scale(1.02);
    }

    .animate-unblur {
        animation: unblurGame 0.4s ease-out forwards;
    }

    @keyframes unblurGame {
        0% {
            filter: blur(3px);
            transform: scale(1.02);
        }
        100% {
            filter: blur(0px);
            transform: scale(1);
        }
    }

    #game-content {
        opacity: 1;
        transition: all 0.3s ease-out;
    }

    .modal-content {
        opacity: 0;
        transform: scale(0.8);
    }

    .animate-fadeIn {
        animation: fadeIn 0.5s ease-in-out forwards;
    }

    .animate-fadeOut {
        animation: fadeOut 0.5s ease-in-out forwards;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }

    .answer-cloud:hover {
        transform: scale(1.05);
        transition: transform 0.2s ease;
    }

    .answer-cloud.transform:hover {
        transform: translateX(-50%) scale(1.05);
    }

    .answer-cloud {
        touch-action: auto;
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        transition: all 0.2s ease;
        transform-origin: center center;
    }

    .answer-cloud:active {
        transform: scale(0.95);
    }

    .answer-cloud.transform:active {
        transform: translateX(-50%) scale(0.95);
    }

    .drop-zone {
        transition: all 0.3s ease;
    }

    .drop-zone img {
        transition: all 0.2s ease;
    }

    img {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }

    .sun-container {
        border: none;
        outline: none;
    }

    .shake-animation {
        animation: shake 0.6s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    .shake-animation.transform {
        animation: shakeTransform 0.6s ease-in-out;
    }

    @keyframes shakeTransform {
        0%, 100% { transform: translateX(-50%); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-50%) translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(-50%) translateX(5px); }
    }

    /* ปรับ container height ให้เหมาะสมกับตำแหน่งใหม่ */
    #answer-clouds-container {
        min-height: 260px !important;
        max-height: 260px;
        overflow: hidden;
    }

    /* เพิ่ม custom classes สำหรับ Tailwind */
    .w-30 { width: 7.5rem; }
    .w-18 { width: 4.5rem; }
    .h-9 { height: 2.25rem; }
    .h-10 { height: 2.5rem; }
    .h-12 { height: 3rem; }
    .h-13 { height: 3.25rem; }
    .h-14 { height: 3.5rem; }
    .h-18 { height: 4.5rem; }
    .h-22 { height: 5.5rem; }
    .w-20 { width: 5rem; }
    .w-22 { width: 5.5rem; }
    .w-24 { width: 6rem; }
    .w-32 { width: 8rem; }
    .w-36 { width: 9rem; }
    .h-24 { height: 6rem; }

    /* Responsive design ที่ปรับปรุงแล้ว */
    @media (max-width: 640px) {
        #answer-clouds-container {
            min-height: 220px !important;
            max-height: 220px;
        }

        .drop-zone {
            width: 7rem !important;
            height: 5rem !important;
            margin: 0 0.25rem;
        }

        .drop-zone img {
            width: 6rem !important;
            height: 4rem !important;
        }

        .answer-cloud {
            font-size: 0.7rem;
        }

        #drop-zones-container {
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
        }

        .answer-cloud {
            min-width: 3rem;
            min-height: 2rem;
        }

        .card-container {
            padding: 0.5rem 0.5rem;
        }

        /* ปรับขนาด clouds ในมือถือ */
        .answer-cloud .w-28,
        .answer-cloud .w-24,
        .answer-cloud .w-32,
        .answer-cloud .w-30 {
            width: 5rem !important;
            height: auto !important;
        }

        .sun-container {
            width: 4rem !important;
            height: 4rem !important;
        }

        h2 {
            font-size: 1rem !important;
            line-height: 1.4 !important;
        }

        p {
            font-size: 0.9rem !important;
        }
    }

    @media (max-width: 480px) {
        #answer-clouds-container {
            min-height: 180px !important;
            max-height: 180px;
        }

        .answer-cloud .w-28,
        .answer-cloud .w-24,
        .answer-cloud .w-32,
        .answer-cloud .w-30 {
            width: 4rem !important;
        }

        .drop-zone {
            width: 6rem !important;
            height: 4rem !important;
        }

        .drop-zone img {
            width: 5rem !important;
            height: 3rem !important;
        }
    }
</style>