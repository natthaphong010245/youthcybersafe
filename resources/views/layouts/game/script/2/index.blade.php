<script>
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

        const cloudPositions = [
            { position: 'top-2 left-2', size: 'w-24 h-auto sm:w-32' },
            { position: 'top-8 right-4', size: 'w-20 h-auto sm:w-24' },
            { position: 'top-20 left-8', size: 'w-22 h-auto sm:w-28' },
            { position: 'top-24 right-12', size: 'w-26 h-auto sm:w-32' },
            { position: 'top-40 left-1/2 transform -translate-x-1/2', size: 'w-24 h-auto sm:w-28' }
        ];

        function resetGameState() {
            [correctOverlay, wrongOverlay, completeOverlay].forEach(modal => {
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('animate-fadeIn', 'animate-fadeOut');
                }
            });

            completedZones = 0;
            usedAnswers.clear();
            gameInitialized = false;

            document.querySelectorAll('.drop-zone').forEach(zone => {
                zone.innerHTML = '<img src="{{ asset("images/material/cloud.png") }}" alt="Empty Cloud" class="w-24 h-16 sm:w-32 sm:h-22 opacity-30">';
            });

            if (answerCloudsContainer) {
                answerCloudsContainer.innerHTML = '';
            }

            if (gameContent) {
                gameContent.classList.remove('game-blur', 'animate-unblur');
            }

            if (introModal && !introModal.style.display === 'none') {
                gameContent.classList.add('game-blur');
                setTimeout(() => {
                    introModal.classList.add('animate-modal-show');
                }, 100);
            }
        }

        resetGameState();

        setTimeout(() => {
            if (introModal) {
                introModal.classList.add('animate-modal-show');
                gameContent.classList.add('game-blur');
            }
        }, 100);

        if (startGameBtn) {
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
        }

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

                const hasTransform = position.position.includes('transform');
                const sizeClasses = position.size.replace('transform -translate-x-1/2', '').trim();

                cloud.className = `absolute ${position.position} ${sizeClasses} cursor-pointer answer-cloud transition-all duration-200`;
                if (hasTransform) {
                    cloud.classList.add('transform', '-translate-x-1/2');
                }

                cloud.dataset.answer = answer;
                cloud.dataset.originalIndex = index;

                cloud.innerHTML = `
                    <div class="relative w-full h-full">
                        <img src="{{ asset("") }}${answerImages[answer]}" alt="Cloud" class="w-full h-full object-contain">
                    </div>
                `;

                cloud.addEventListener('click', handleCloudClick);

                answerCloudsContainer.appendChild(cloud);
            });
        }

        function handleCloudClick(e) {
            const clickedCloud = e.target.closest('.answer-cloud');
            if (!clickedCloud) return;

            const answer = clickedCloud.dataset.answer;

            if (usedAnswers.has(answer)) {
                return;
            }

            clickedCloud.style.transform = 'scale(0.95)';
            setTimeout(() => {
                if (clickedCloud.classList.contains('transform')) {
                    clickedCloud.style.transform = 'translateX(-50%) scale(1)';
                } else {
                    clickedCloud.style.transform = 'scale(1)';
                }
            }, 150);

            if (correctAnswers.includes(answer)) {
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
            const cloudRect = cloud.getBoundingClientRect();
            const zoneRect = zone.getBoundingClientRect();
            
            const flyingCloud = cloud.cloneNode(true);
            flyingCloud.style.position = 'fixed';
            flyingCloud.style.left = cloudRect.left + 'px';
            flyingCloud.style.top = cloudRect.top + 'px';
            flyingCloud.style.zIndex = '1000';
            flyingCloud.style.pointerEvents = 'none';
            flyingCloud.style.transition = 'all 0.6s ease-out';
            
            document.body.appendChild(flyingCloud);
            
            cloud.style.opacity = '0.3';
            cloud.style.pointerEvents = 'none';
            
            setTimeout(() => {
                flyingCloud.style.left = (zoneRect.left + (zoneRect.width - cloudRect.width) / 2) + 'px';
                flyingCloud.style.top = (zoneRect.top + (zoneRect.height - cloudRect.height) / 2) + 'px';
                flyingCloud.style.transform = 'scale(0.8)';
            }, 50);
            
            setTimeout(() => {
                zone.innerHTML = `<img src="{{ asset("") }}${answerImages[answer]}" alt="Cloud" class="w-24 h-16 sm:w-32 sm:h-22 placed-cloud object-contain">`;
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
                zone.innerHTML = '<img src="{{ asset("images/material/cloud.png") }}" alt="Empty Cloud" class="w-24 h-16 sm:w-32 sm:h-22 opacity-30">';
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

    #answer-clouds-container {
        min-height: 320px;
    }

    .w-20 { width: 5rem; }
    .w-22 { width: 5.5rem; }
    .w-24 { width: 6rem; }
    .w-26 { width: 6.5rem; }
    .w-28 { width: 7rem; }
    .w-32 { width: 8rem; }
    .w-36 { width: 9rem; }
    .h-16 { height: 4rem; }
    .h-18 { height: 4.5rem; }
    .h-20 { height: 5rem; }
    .h-22 { height: 5.5rem; }
    .h-24 { height: 6rem; }

    @media (max-width: 640px) {
        #answer-clouds-container {
            min-height: 280px;
        }

        .drop-zone {
            width: 6rem !important;
            height: 4rem !important;
            margin: 0 0.25rem;
        }

        .drop-zone img {
            width: 5.5rem !important;
            height: 3.5rem !important;
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
            padding: 0.5rem 1rem;
        }

        .sun-container {
            width: 4rem !important;
            height: 4rem !important;
        }

        h2 {
            font-size: 1rem !important;
            line-height: 1.4;
        }

        .text-lg {
            font-size: 0.9rem !important;
        }
    }

    @media (max-width: 480px) {
        #answer-clouds-container {
            min-height: 260px;
        }

        .drop-zone {
            width: 5rem !important;
            height: 3.5rem !important;
        }

        .drop-zone img {
            width: 4.5rem !important;
            height: 3rem !important;
        }

        .answer-cloud {
            font-size: 0.6rem;
        }

        h2 {
            font-size: 0.9rem !important;
        }
    }
</style>