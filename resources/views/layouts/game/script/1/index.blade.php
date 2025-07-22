<script>
    document.addEventListener('DOMContentLoaded', function() {
        const introModal = document.getElementById('intro-modal');
        const gameContent = document.getElementById('game-content');
        const startGameBtn = document.getElementById('start-game-btn');
        const answerOptions = document.querySelectorAll('.answer-option');
        const successModal = document.getElementById('success-modal');
        const failureModal = document.getElementById('failure-modal');
        const answerRevealModal = document.getElementById('answer-reveal-modal');
        const successBtn = document.getElementById('success-btn');
        const tryAgainBtn = document.getElementById('try-again-btn');
        const skipBtn = document.getElementById('skip-btn');
        const continueBtn = document.getElementById('continue-btn');

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

        answerOptions.forEach(option => {
            option.addEventListener('click', function() {
                const isCorrect = this.getAttribute('data-correct') === 'true';
                const answer = this.getAttribute('data-answer');

                answerOptions.forEach(opt => {
                    opt.style.pointerEvents = 'none';
                    const optDiv = opt.querySelector('div');
                    optDiv.classList.add('opacity-50');
                });

                const selectedDiv = this.querySelector('div');
                if (isCorrect) {
                    selectedDiv.style.backgroundColor = '#10b981';
                    selectedDiv.classList.remove('opacity-50');
                } else {
                    selectedDiv.style.backgroundColor = '#ef4444';
                    selectedDiv.classList.remove('opacity-50');
                }

                setTimeout(() => {
                    if (isCorrect) {
                        showSuccessModal();
                    } else {
                        showFailureModal();
                    }
                }, 800);
            });
        });

        function showSuccessModal() {
            successModal.classList.remove('hidden');
            successModal.classList.add('animate-modal-show');
        }

        function showFailureModal() {
            failureModal.classList.remove('hidden');
            failureModal.classList.add('animate-modal-show');
        }

        function showAnswerRevealModal() {
            answerRevealModal.classList.remove('hidden');
            answerRevealModal.classList.add('animate-modal-show');
        }

        function hideModal(modal) {
            modal.classList.remove('animate-modal-show');
            modal.classList.add('animate-modal-fade-out');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('animate-modal-fade-out');
            }, 300);
        }

        function resetGame() {
            answerOptions.forEach(opt => {
                opt.style.pointerEvents = 'auto';
                const optDiv = opt.querySelector('div');
                optDiv.classList.remove('opacity-50');
                optDiv.style.backgroundColor = '#6366f1';
            });
        }

        successBtn.addEventListener('click', function() {
            window.location.href = "@yield('next-route')";
        });

        tryAgainBtn.addEventListener('click', function() {
            hideModal(failureModal);
            setTimeout(() => {
                resetGame();
            }, 300);
        });

        skipBtn.addEventListener('click', function() {
            hideModal(failureModal);
            setTimeout(() => {
                showAnswerRevealModal();
            }, 300);
        });

        continueBtn.addEventListener('click', function() {
            window.location.href = "@yield('next-route')";
        });

        [successModal, failureModal, answerRevealModal].forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideModal(modal);
                    if (modal === failureModal) {
                        setTimeout(() => {
                            resetGame();
                        }, 300);
                    }
                }
            });
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

    @keyframes modalFadeOut {
        0% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.5;
            transform: scale(0.95);
        }
        100% {
            opacity: 0;
            transform: scale(0.9);
            visibility: hidden;
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

    @keyframes fadeIn {
        0% {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        50% {
            opacity: 0.7;
            transform: translateY(15px) scale(0.98);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-content {
        opacity: 0;
        transform: scale(0.8);
    }

    .answer-option {
        transition: all 0.3s ease;
    }

    .answer-option:hover div {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .answer-option div {
        transition: all 0.3s ease;
    }

    .answer-option:active div {
        transform: scale(0.95);
    }

    .answer-option[style*="pointer-events: none"] div {
        transform: none !important;
        box-shadow: none !important;
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

    .answer-option div {
        will-change: transform, background-color;
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