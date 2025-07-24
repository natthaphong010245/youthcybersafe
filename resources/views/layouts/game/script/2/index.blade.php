<script>
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    });

    window.addEventListener('beforeunload', function() {
        sessionStorage.setItem('game2PageLeft', 'true');
    });

    window.addEventListener('load', function() {
        if (sessionStorage.getItem('game2PageLeft') === 'true') {
            sessionStorage.removeItem('game2PageLeft');
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
        const allAnswers = ['จิ๊จ๊ะ', 'เจตนา', 'จงใจทำซ้ำ', 'เจ็บปวด', 'จ๊ะจ๋า'];

        const answerImages = {
            'เจตนา': 'images/game/2/intent.png',
            'เจ็บปวด': 'images/game/2/painful.png',
            'จงใจทำซ้ำ': 'images/game/2/intentionally_repetitive.png',
            'จิ๊จ๊ะ': 'images/game/2/jijja.png',
            'จ๊ะจ๋า': 'images/game/2/jaja.png'
        };

        let completedZones = 0;
        let currentDropZone = 0;
        let gameInitialized = false;
        let selectedAnswers = [];

        function resetGameState() {
            completedZones = 0;
            currentDropZone = 0;
            selectedAnswers = [];
            gameInitialized = false;

            [correctOverlay, wrongOverlay, completeOverlay].forEach(modal => {
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('animate-fadeIn', 'animate-fadeOut');
                }
            });

            if (gameContent) {
                gameContent.classList.remove('game-blur', 'animate-unblur');
            }

            if (answerCloudsContainer) {
                answerCloudsContainer.innerHTML = '';
            }

            document.querySelectorAll('.drop-zone').forEach((zone, index) => {
                zone.innerHTML = `<img src="{{ asset('images/material/cloud.png') }}" alt="Empty Cloud" class="w-32 h-22 opacity-30">`;
            });
        }

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
            
            answerCloudsContainer.className = 'relative mb-6 px-4';
            answerCloudsContainer.style.height = '320px';

            const cloudConfigs = [
                { answer: 'จิ๊จ๊ะ', position: 'top-8 left-4', size: 'w-28 h-20', id: 'jijja' },
                { answer: 'เจตนา', position: 'top-8 right-4', size: 'w-24 h-18', id: 'intent' },
                { answer: 'จงใจทำซ้ำ', position: 'top-32 left-4', size: 'w-32 h-22', id: 'repetitive' },
                { answer: 'เจ็บปวด', position: 'top-32 right-4', size: 'w-28 h-20', id: 'painful' },
                { answer: 'จ๊ะจ๋า', position: 'top-56 left-1/2 transform -translate-x-1/2', size: 'w-24 h-18', id: 'jaja' }
            ];

            cloudConfigs.forEach((config, index) => {
                const cloud = document.createElement('div');
                
                const hasTransform = config.position.includes('transform');
                const positionClasses = config.position.replace('transform -translate-x-1/2', '').trim();
                
                cloud.className = `absolute ${positionClasses} ${config.size} cursor-pointer transition-all duration-200 hover:scale-105 active:scale-95 answer-cloud`;
                
                if (hasTransform) {
                    cloud.classList.add('transform', '-translate-x-1/2');
                }
                
                cloud.dataset.answer = config.answer;
                cloud.dataset.id = config.id;
                cloud.dataset.selected = 'false';

                cloud.innerHTML = `
                    <div class="relative w-full h-full">
                        <img src="{{ asset('') }}${answerImages[config.answer]}" alt="${config.answer}" class="w-full h-full object-contain">
                    </div>
                `;

                cloud.addEventListener('click', handleCloudClick);

                answerCloudsContainer.appendChild(cloud);
            });
        }

        function handleCloudClick(e) {
            const cloud = e.currentTarget;
            const answer = cloud.dataset.answer;
            const cloudId = cloud.dataset.id;
            
            if (selectedAnswers.includes(cloudId)) {
                return;
            }

            if (currentDropZone >= 3) {
                return;
            }

            cloud.style.transform = cloud.classList.contains('transform') ? 'translateX(-50%) scale(0.9)' : 'scale(0.9)';
            setTimeout(() => {
                if (cloud.classList.contains('transform')) {
                    cloud.style.transform = 'translateX(-50%)';
                } else {
                    cloud.style.transform = '';
                }
            }, 150);

            if (correctAnswers.includes(answer)) {
                placeAnswerInZone(answer, currentDropZone);
                markCloudAsUsed(cloud);
                selectedAnswers.push(cloudId);
                completedZones++;
                currentDropZone++;

                if (completedZones < 3) {
                    showCorrectModal();
                } else {
                    showCompleteModal();
                }
            } else {
                showWrongModal();
            }
        }

        function placeAnswerInZone(answer, zoneIndex) {
            const zone = document.querySelector(`[data-zone="${zoneIndex}"]`);
            if (zone) {
                zone.innerHTML = `<img src="{{ asset('') }}${answerImages[answer]}" alt="${answer}" class="w-32 h-22 placed-cloud">`;
            }
        }

        function markCloudAsUsed(cloud) {
            cloud.style.opacity = '0.5';
            cloud.style.pointerEvents = 'none';
            cloud.dataset.selected = 'true';
            cloud.classList.add('cursor-not-allowed');
            cloud.classList.remove('cursor-pointer', 'hover:scale-105');
            
            if (cloud.classList.contains('transform')) {
                cloud.style.transform = 'translateX(-50%)';
            } else {
                cloud.style.transform = '';
            }
        }

        function resetUsedClouds() {
            document.querySelectorAll('.answer-cloud').forEach(cloud => {
                cloud.style.opacity = '1';
                cloud.style.pointerEvents = 'auto';
                cloud.dataset.selected = 'false';
                cloud.classList.remove('cursor-not-allowed');
                cloud.classList.add('cursor-pointer', 'hover:scale-105');
                if (cloud.classList.contains('transform')) {
                    cloud.style.transform = 'translateX(-50%)';
                } else {
                    cloud.style.transform = '';
                }
            });
        }

        function resetDropZones() {
            currentDropZone = 0;
            completedZones = 0;
            selectedAnswers = [];

            document.querySelectorAll('.drop-zone').forEach((zone, index) => {
                zone.innerHTML = `<img src="{{ asset('images/material/cloud.png') }}" alt="Empty Cloud" class="w-32 h-22 opacity-30">`;
            });
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

    .answer-cloud {
        touch-action: manipulation;
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        transition: all 0.2s ease;
        transform-origin: center center;
    }

    .answer-cloud:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .answer-cloud.transform:hover {
        transform: translateX(-50%) scale(1.05);
    }

    .answer-cloud:active {
        transform: scale(0.95);
    }

    .answer-cloud.transform:active {
        transform: translateX(-50%) scale(0.95);
    }

    .answer-cloud.cursor-not-allowed:hover {
        transform: none;
        box-shadow: none;
    }

    .answer-cloud.cursor-not-allowed.transform:hover {
        transform: translateX(-50%);
        box-shadow: none;
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

    .w-18 { width: 4.5rem; }
    .w-26 { width: 6.5rem; }
    .h-9 { height: 2.25rem; }
    .h-10 { height: 2.5rem; }
    .h-12 { height: 3rem; }
    .h-13 { height: 3.25rem; }
    .h-14 { height: 3.5rem; }
    .h-18 { height: 4.5rem; }
    .h-19 { height: 4.75rem; }
    .h-22 { height: 5.5rem; }
    .w-20 { width: 5rem; }
    .w-22 { width: 5.5rem; }
    .w-24 { width: 6rem; }
    .w-28 { width: 7rem; }
    .w-32 { width: 8rem; }
    .w-36 { width: 9rem; }
    .h-20 { height: 5rem; }
    .h-24 { height: 6rem; }

    #answer-clouds-container {
        min-height: 320px;
    }

    @media (max-width: 640px) {
        #answer-clouds-container {
            height: 280px !important;
            min-height: 280px;
        }

        .answer-cloud {
            position: absolute !important;
        }

        .answer-cloud[data-id="jijja"] {
            top: 1rem !important;
            left: 0.5rem !important;
            width: 4.5rem !important;
            height: 3.5rem !important;
        }

        .answer-cloud[data-id="intent"] {
            top: 1rem !important;
            right: 0.5rem !important;
            width: 4rem !important;
            height: 3rem !important;
        }

        .answer-cloud[data-id="repetitive"] {
            top: 5.5rem !important;
            left: 0.5rem !important;
            width: 5rem !important;
            height: 3.75rem !important;
        }

        .answer-cloud[data-id="painful"] {
            top: 5.5rem !important;
            right: 0.5rem !important;
            width: 4.5rem !important;
            height: 3.5rem !important;
        }

        .answer-cloud[data-id="jaja"] {
            top: 10rem !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 4rem !important;
            height: 3rem !important;
        }

        .drop-zone {
            width: 6rem;
            height: 4rem;
            margin: 0 0.25rem;
        }

        .drop-zone img {
            width: 5.5rem !important;
            height: 3.5rem !important;
        }

        #drop-zones-container {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .card-container {
            padding: 0.5rem 0.5rem;
        }
    }

    @media (min-width: 641px) {
        .answer-cloud[data-id="jijja"] {
            top: 2rem;
            left: 1rem;
            width: 7rem;
            height: 5rem;
        }

        .answer-cloud[data-id="intent"] {
            top: 2rem;
            right: 1rem;
            width: 6rem;
            height: 4.5rem;
        }

        .answer-cloud[data-id="repetitive"] {
            top: 8rem;
            left: 1rem;
            width: 8rem;
            height: 5.5rem;
        }

        .answer-cloud[data-id="painful"] {
            top: 8rem;
            right: 1rem;
            width: 7rem;
            height: 5rem;
        }

        .answer-cloud[data-id="jaja"] {
            top: 14rem;
            left: 50%;
            transform: translateX(-50%);
            width: 6rem;
            height: 4.5rem;
        }
    }

    .placed-cloud {
        animation: placeCloud 0.3s ease-out;
    }

    @keyframes placeCloud {
        0% {
            opacity: 0;
            transform: scale(0.8);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>