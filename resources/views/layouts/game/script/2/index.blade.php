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
        const cloudOptions = document.querySelectorAll('.cloud-option');
        const answerSlots = document.querySelectorAll('.answer-slot');
        const successModal = document.getElementById('success-modal');
        const failureModal = document.getElementById('failure-modal');
        const successBtn = document.getElementById('success-btn');
        const tryAgainBtn = document.getElementById('try-again-btn');
        const skipBtn = document.getElementById('skip-btn');

        let selectedAnswers = [];
        let usedOptions = new Set();
        const correctAnswers = ['เจตนา', 'จงใจทำซ้ำ', 'เจ็บปวด'];

        function resetGameState() {
            selectedAnswers = [];
            usedOptions.clear();
            
            [successModal, failureModal].forEach(modal => {
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('animate-modal-show', 'animate-modal-fade-out');
                }
            });

            // Reset cloud options
            cloudOptions.forEach(option => {
                option.style.opacity = '1';
                option.style.pointerEvents = 'auto';
                option.classList.remove('used');
            });

            // Reset answer slots
            answerSlots.forEach(slot => {
                slot.innerHTML = `
                    <img src="{{ asset('images/material/cloud.png') }}" alt="ช่องคำตอบ" class="w-full h-full object-contain opacity-30">
                `;
                slot.classList.remove('filled');
                slot.dataset.answer = '';
            });

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

        // Intro modal handling
        if (introModal) {
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
                }, 300);
            });
        }

        cloudOptions.forEach(option => {
            option.addEventListener('click', function() {
                if (usedOptions.has(option.dataset.text)) {
                    return;
                }

                const isCorrect = option.dataset.correct === 'true';
                
                if (!isCorrect) {
                    setTimeout(() => {
                        showFailureModal();
                    }, 300);
                    return;
                }

                const emptySlot = findEmptySlot();
                if (!emptySlot) {
                    return; 
                }

                addAnswerToSlot(emptySlot, option);
                
                usedOptions.add(option.dataset.text);
                option.style.opacity = '0.3';
                option.style.pointerEvents = 'none';
                option.classList.add('used');

                if (selectedAnswers.length === 3) {
                    setTimeout(() => {
                        showSuccessModal();
                    }, 500);
                }
            });
        });

        answerSlots.forEach(slot => {
            slot.addEventListener('click', function() {
                if (slot.classList.contains('filled')) {
                    removeAnswerFromSlot(slot);
                }
            });
        });

        function findEmptySlot() {
            return Array.from(answerSlots).find(slot => !slot.classList.contains('filled'));
        }

        function addAnswerToSlot(slot, option) {
            const answerText = option.dataset.text;
            const isCorrect = option.dataset.correct === 'true';
            
            selectedAnswers.push({
                text: answerText,
                correct: isCorrect,
                slot: slot.dataset.slot,
                originalOption: option
            });

            slot.classList.add('filled');
            slot.dataset.answer = answerText;
            
            const cloudImg = option.cloneNode(true);
            cloudImg.classList.remove('cloud-option', 'cursor-pointer', 'hover:scale-105');
            cloudImg.classList.add('w-full', 'h-full', 'object-contain');
            cloudImg.style.opacity = '1';
            cloudImg.style.pointerEvents = 'none';
            
            slot.innerHTML = '';
            slot.appendChild(cloudImg);
            
            slot.title = 'คลิกเพื่อลบ';
        }

        function removeAnswerFromSlot(slot) {
            const answerText = slot.dataset.answer;
            
            selectedAnswers = selectedAnswers.filter(answer => answer.text !== answerText);
            
            usedOptions.delete(answerText);
            
            const originalOption = Array.from(cloudOptions).find(option => option.dataset.text === answerText);
            if (originalOption) {
                originalOption.style.opacity = '1';
                originalOption.style.pointerEvents = 'auto';
                originalOption.classList.remove('used');
            }
            
            slot.innerHTML = `
                <img src="{{ asset('images/material/cloud.png') }}" alt="ช่องคำตอบ" class="w-full h-full object-contain opacity-30">
            `;
            slot.classList.remove('filled');
            slot.dataset.answer = '';
            slot.title = '';
        }

        function showSuccessModal() {
            successModal.classList.remove('hidden');
            successModal.classList.add('animate-modal-show');
        }

        function showFailureModal() {
            failureModal.classList.remove('hidden');
            failureModal.classList.add('animate-modal-show');
        }

        function hideModal(modal) {
            modal.classList.remove('animate-modal-show');
            modal.classList.add('animate-modal-fade-out');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('animate-modal-fade-out');
            }, 300);
        }

        function resetAnswers() {
            selectedAnswers.forEach(answer => {
                const slot = document.querySelector(`[data-slot="${answer.slot}"]`);
                if (slot) {
                    removeAnswerFromSlot(slot);
                }
            });
        }

        successBtn.addEventListener('click', function() {
            window.location.href = "@yield('next-route')";
        });

        tryAgainBtn.addEventListener('click', function() {
            hideModal(failureModal);
            setTimeout(() => {
                resetAnswers();
            }, 300);
        });

        skipBtn.addEventListener('click', function() {
            window.location.href = "@yield('next-route')";
        });

        [successModal, failureModal].forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideModal(modal);
                    if (modal === failureModal) {
                        setTimeout(() => {
                            resetAnswers();
                        }, 300);
                    }
                }
            });
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

    .animate-modal-fade-out {
        animation: backdropFadeOut 0.3s ease-out forwards;
    }

    .animate-modal-fade-out .modal-content {
        animation: contentScaleOut 0.3s ease-out forwards;
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

    .modal-content {
        opacity: 0;
        transform: scale(0.8);
    }

    .cloud-option {
        transition: all 0.3s ease;
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
    }

    .cloud-option:hover:not(.used) {
        transform: translateY(-3px) scale(1.05);
        filter: drop-shadow(0 8px 15px rgba(0, 0, 0, 0.2));
    }

    .cloud-option.used {
        transition: all 0.3s ease;
    }

    .answer-slot {
        transition: all 0.3s ease;
    }

    .answer-slot:hover:not(.filled) {
        transform: scale(1.05);
        filter: brightness(1.1);
    }

    .answer-slot:hover.filled {
        transform: scale(0.95);
    }

    .answer-slot.filled {
        background-color: transparent;
        cursor: pointer;
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

    button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    button:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>