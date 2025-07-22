<script>
    document.addEventListener('DOMContentLoaded', function() {
        const introModal = document.getElementById('intro-modal');
        const gameContent = document.getElementById('game-content');
        const startGameBtn = document.getElementById('start-game-btn');

        if (introModal && window.gameHasIntroModal) {
            setTimeout(() => {
                introModal.classList.add('animate-modal-show');
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

        let selectedText = null;
        let selectedImage = null;
        let matchedPairs = 0;

        const totalPairs = window.gamePairs || 4;
        const correctMatches = window.gameMatches || {
            '1': '2',
            '2': '1',
            '3': '4',
            '4': '3'
        };
        const nextRoute = window.gameNextRoute || "{{ route('main') }}";

        document.querySelectorAll('.text-option').forEach(option => {
            option.addEventListener('click', function() {
                if (this.classList.contains('matched')) return;

                document.querySelectorAll('.text-option').forEach(opt => {
                    if (!opt.classList.contains('matched')) {
                        opt.classList.remove('ring-4', 'ring-blue-400',
                            'ring-opacity-50');
                    }
                });

                this.classList.add('ring-4', 'ring-blue-400', 'ring-opacity-50');
                selectedText = this.getAttribute('data-text');

                checkForMatch();
            });
        });

        document.querySelectorAll('.image-option').forEach(option => {
            option.addEventListener('click', function() {
                if (this.classList.contains('matched')) return;

                document.querySelectorAll('.image-option').forEach(opt => {
                    if (!opt.classList.contains('matched')) {
                        opt.classList.remove('border-blue-400', 'bg-blue-50', 'ring-4',
                            'ring-blue-400', 'ring-opacity-50');
                        opt.classList.add('border-gray-300', 'bg-white');
                    }
                });

                this.classList.remove('border-gray-300', 'bg-white');
                this.classList.add('border-blue-400', 'bg-blue-50', 'ring-4', 'ring-blue-400',
                    'ring-opacity-50');
                selectedImage = this.getAttribute('data-image');

                checkForMatch();
            });
        });

        function checkForMatch() {
            if (selectedText && selectedImage) {
                if (correctMatches[selectedText] === selectedImage) {
                    const textElement = document.querySelector(`[data-text="${selectedText}"]`);
                    const imageElement = document.querySelector(`[data-image="${selectedImage}"]`);

                    textElement.style.transform = 'scale(1.05)';
                    imageElement.style.transform = 'scale(1.05)';

                    setTimeout(() => {
                        textElement.classList.remove('ring-4', 'ring-blue-400', 'ring-opacity-50',
                            'bg-[#5B21B6]');
                        textElement.classList.add('bg-green-500', 'matched');
                        textElement.style.transform = 'scale(1)';

                        imageElement.classList.remove('border-blue-400', 'bg-blue-50', 'ring-4',
                            'ring-blue-400', 'ring-opacity-50', 'border-gray-300', 'bg-white');
                        imageElement.classList.add('border-green-500', 'bg-green-100', 'matched');
                        imageElement.style.transform = 'scale(1)';

                        matchedPairs++;
                        updateProgress();

                        if (matchedPairs >= totalPairs) {
                            setTimeout(() => {
                                showCompletionModal();
                            }, 1000);
                        }
                    }, 300);

                    selectedText = null;
                    selectedImage = null;
                } else {
                    const textElement = document.querySelector(`[data-text="${selectedText}"]`);
                    const imageElement = document.querySelector(`[data-image="${selectedImage}"]`);

                    textElement.classList.add('ring-4', 'ring-red-400', 'ring-opacity-50');
                    textElement.style.animation = 'shake 0.5s ease-in-out';

                    imageElement.classList.remove('border-blue-400', 'bg-blue-50');
                    imageElement.classList.add('border-red-400', 'bg-red-50');
                    imageElement.style.animation = 'shake 0.5s ease-in-out';

                    setTimeout(() => {
                        textElement.classList.remove('ring-4', 'ring-blue-400', 'ring-opacity-50',
                            'ring-red-400');
                        textElement.style.animation = '';

                        imageElement.classList.remove('border-blue-400', 'bg-blue-50', 'border-red-400',
                            'bg-red-50', 'ring-4', 'ring-blue-400', 'ring-opacity-50');
                        imageElement.classList.add('border-gray-300', 'bg-white');
                        imageElement.style.animation = '';

                        selectedText = null;
                        selectedImage = null;
                    }, 1000);
                }
            }
        }

        function updateProgress() {
            const percentage = Math.round((matchedPairs / totalPairs) * 100);
            const progressBar = document.getElementById('progress-bar');
            const progressPercentage = document.getElementById('progress-percentage');

            if (progressBar && progressPercentage) {
                progressBar.style.width = percentage + '%';

                progressPercentage.textContent = percentage + '%';

                if (percentage === 100) {
                    progressBar.classList.add('animate-pulse');
                    progressPercentage.classList.add('text-gray-600');
                }
            }

            console.log('Progress updated:', percentage + '%');
        }

        function showCompletionModal() {
            const modal = document.getElementById('complete-overlay');

            if (!modal) {
                alert('เยี่ยม! คุณผ่านเกมนี้เรียบร้อยแล้ว');
                window.location.href = nextRoute;
                return;
            }

            modal.classList.remove('hidden');
            modal.style.display = 'flex';

            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.style.opacity = '1';
            }, 50);

            const finishBtn = modal.querySelector('#finish-game-btn');
            if (finishBtn) {
                finishBtn.onclick = function() {
                    modal.style.opacity = '0';

                    setTimeout(() => {
                        window.location.href = nextRoute;
                    }, 300);
                };
            } else {
                console.error('finish-game-btn not found!');
            }
        }
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

    .text-option:hover:not(.matched) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(91, 33, 182, 0.4);
    }

    .image-option:hover:not(.matched) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
    }

    .matched {
        pointer-events: none;
    }

    .text-option,
    .image-option {
        transition: all 0.3s ease;
    }

    button:hover {
        transform: translateY(-1px);
    }

    #complete-overlay {
        transition: opacity 0.3s ease-in-out;
    }

    #complete-overlay .bg-white {
        transform: scale(0.95);
        transition: transform 0.3s ease-in-out;
    }

    #complete-overlay:not(.opacity-0) .bg-white {
        transform: scale(1);
    }

    #progress-bar {
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    #progress-bar.animate-pulse {
        animation: progressPulse 1s ease-in-out infinite;
    }

    @keyframes progressPulse {

        0%,
        100% {
            box-shadow: 0 2px 4px rgba(99, 102, 241, 0.3);
        }

        50% {
            box-shadow: 0 4px 8px rgba(99, 102, 241, 0.6);
            transform: scaleY(1.1);
        }
    }

    .progress-container:hover #progress-bar {
        box-shadow: 0 4px 8px rgba(99, 102, 241, 0.4);
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-5px);
        }

        75% {
            transform: translateX(5px);
        }
    }
</style>
