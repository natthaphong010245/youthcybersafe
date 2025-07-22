<script>
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
            let draggedElement = null;
            let draggedOriginalPosition = null;
            let touchOffset = {
                x: 0,
                y: 0
            };
            let isDragging = false;
            let gameInitialized = false;

            const cloudPositions = [{
                position: 'top-4 left-4',
                size: 'w-32 h-auto'
            }, {
                position: 'top-10 right-20',
                size: 'w-24 h-auto'
            }, {
                position: 'top-28 left-14',
                size: 'w-28 h-auto'
            }, {
                position: 'top-32 right-6',
                size: 'w-32 h-auto'
            }, {
                position: 'top-52 left-1/2 transform -translate-x-1/2',
                size: 'w-28 h-auto'
            }];

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
                        setupDropZones();
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
                        `absolute ${position.position} ${sizeClasses} cursor-move answer-cloud`;
                    if (hasTransform) {
                        cloud.classList.add('transform', '-translate-x-1/2');
                    }

                    cloud.draggable = true;
                    cloud.dataset.answer = answer;
                    cloud.dataset.originalIndex = index;

                    cloud.innerHTML = `
                        <div class="relative w-full h-full">
                            <img src="{{ asset('') }}${answerImages[answer]}" alt="Cloud" class="w-full h-full">
                        </div>
                    `;

                    cloud.addEventListener('dragstart', handleDragStart);
                    cloud.addEventListener('dragend', handleDragEnd);

                    cloud.addEventListener('touchstart', handleTouchStart, {
                        passive: false
                    });
                    cloud.addEventListener('touchmove', handleTouchMove, {
                        passive: false
                    });
                    cloud.addEventListener('touchend', handleTouchEnd, {
                        passive: false
                    });

                    answerCloudsContainer.appendChild(cloud);
                });
            }

            function handleDragStart(e) {
                draggedElement = e.target.closest('.answer-cloud');
                draggedOriginalPosition = {
                    parent: draggedElement.parentNode,
                    nextSibling: draggedElement.nextSibling
                };
                e.dataTransfer.effectAllowed = 'move';
            }

            function handleDragEnd(e) {
                setTimeout(() => {
                    draggedElement = null;
                    draggedOriginalPosition = null;
                }, 100);
            }

            function handleTouchStart(e) {
                e.preventDefault();
                const touch = e.touches[0];
                draggedElement = e.target.closest('.answer-cloud');

                if (!draggedElement) return;

                draggedOriginalPosition = {
                    parent: draggedElement.parentNode,
                    nextSibling: draggedElement.nextSibling
                };

                const rect = draggedElement.getBoundingClientRect();
                touchOffset.x = touch.clientX - rect.left;
                touchOffset.y = touch.clientY - rect.top;

                isDragging = true;
                draggedElement.style.zIndex = '1000';

                const hasTranslate = draggedElement.classList.contains('transform');
                if (hasTranslate) {
                    draggedElement.style.transform = 'translateX(-50%) scale(1.1)';
                } else {
                    draggedElement.style.transform = 'scale(1.1)';
                }
            }

            function handleTouchMove(e) {
                if (!isDragging || !draggedElement) return;

                e.preventDefault();
                const touch = e.touches[0];

                const x = touch.clientX - touchOffset.x;
                const y = touch.clientY - touchOffset.y;

                draggedElement.style.position = 'fixed';
                draggedElement.style.left = x + 'px';
                draggedElement.style.top = y + 'px';
                draggedElement.style.pointerEvents = 'none';

                const elementBelow = document.elementFromPoint(touch.clientX, touch.clientY);
                const dropZone = elementBelow?.closest('.drop-zone');

                document.querySelectorAll('.drop-zone img').forEach(img => {
                    img.style.opacity = '0.3';
                    img.style.filter = '';
                });

                if (dropZone) {
                    const cloudImg = dropZone.querySelector('img');
                    if (cloudImg) {
                        cloudImg.style.opacity = '0.6';
                        cloudImg.style.filter = 'brightness(1.2)';
                    }
                }
            }

            function handleTouchEnd(e) {
                if (!isDragging || !draggedElement) return;

                e.preventDefault();
                const touch = e.changedTouches[0];
                const elementBelow = document.elementFromPoint(touch.clientX, touch.clientY);
                const dropZone = elementBelow?.closest('.drop-zone');

                draggedElement.style.position = '';
                draggedElement.style.left = '';
                draggedElement.style.top = '';
                draggedElement.style.zIndex = '';
                draggedElement.style.transform = '';
                draggedElement.style.pointerEvents = '';

                document.querySelectorAll('.drop-zone img').forEach(img => {
                    img.style.opacity = '0.3';
                    img.style.filter = '';
                });

                if (dropZone) {
                    const answer = draggedElement.dataset.answer;

                    if (dropZone.querySelector('.placed-cloud')) {
                        returnToOriginalPosition();
                        isDragging = false;
                        return;
                    }

                    if (correctAnswers.includes(answer)) {
                        placeCloudInZone(draggedElement, dropZone);
                        completedZones++;

                        if (completedZones < 3) {
                            showCorrectModal();
                        } else {
                            showCompleteModal();
                        }
                    } else {
                        showWrongModal();
                        returnToOriginalPosition();
                    }
                } else {
                    returnToOriginalPosition();
                }

                isDragging = false;
                setTimeout(() => {
                    draggedElement = null;
                    draggedOriginalPosition = null;
                }, 100);
            }

            function setupDropZones() {
                document.querySelectorAll('.drop-zone').forEach(zone => {
                    zone.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        e.dataTransfer.dropEffect = 'move';
                        const cloudImg = this.querySelector('img');
                        if (cloudImg) {
                            cloudImg.style.opacity = '0.6';
                            cloudImg.style.filter = 'brightness(1.2)';
                        }
                    });

                    zone.addEventListener('dragleave', function(e) {
                        const cloudImg = this.querySelector('img');
                        if (cloudImg) {
                            cloudImg.style.opacity = '0.3';
                            cloudImg.style.filter = '';
                        }
                    });

                    zone.addEventListener('drop', function(e) {
                        e.preventDefault();
                        const cloudImg = this.querySelector('img');
                        if (cloudImg) {
                            cloudImg.style.opacity = '0.3';
                            cloudImg.style.filter = '';
                        }

                        if (!draggedElement) return;

                        const answer = draggedElement.dataset.answer;

                        if (this.querySelector('.placed-cloud')) {
                            returnToOriginalPosition();
                            return;
                        }

                        if (correctAnswers.includes(answer)) {
                            placeCloudInZone(draggedElement, this);
                            completedZones++;

                            if (completedZones < 3) {
                                showCorrectModal();
                            } else {
                                showCompleteModal();
                            }
                        } else {
                            showWrongModal();
                            returnToOriginalPosition();
                        }
                    });
                });
            }

            function placeCloudInZone(cloud, zone) {
                const answer = cloud.dataset.answer;
                zone.innerHTML =
                    `<img src="{{ asset('') }}${answerImages[answer]}" alt="Cloud" class="w-32 h-22 placed-cloud">`;
                cloud.remove();
            }

            function returnToOriginalPosition() {
                if (draggedElement && draggedOriginalPosition) {
                    if (draggedOriginalPosition.nextSibling) {
                        draggedOriginalPosition.parent.insertBefore(draggedElement, draggedOriginalPosition
                            .nextSibling);
                    } else {
                        draggedOriginalPosition.parent.appendChild(draggedElement);
                    }
                }
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
            touch-action: none;
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

        #answer-clouds-container {
            min-height: 280px;
        }

        @media (max-width: 640px) {
            #answer-clouds-container {
                min-height: 280px;
            }

            .drop-zone {
                width: 7rem;
                height: 5rem;
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
            }

            .answer-cloud {
                min-width: 3rem;
                min-height: 2rem;
            }

            .card-container {
                padding: 0.5rem 0.5rem;
            }
        }
    </style>