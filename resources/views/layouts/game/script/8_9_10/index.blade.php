<script>
    document.addEventListener('DOMContentLoaded', function() {
        const introModal = document.getElementById('intro-modal');
        const gameContent = document.getElementById('game-content');
        const startGameBtn = document.getElementById('start-game-btn');

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

        const infoOverlay = document.getElementById('info-overlay');
        const successOverlay = document.getElementById('success-overlay');
        const wrongOverlay = document.getElementById('wrong-overlay');
        
        const nextBtn = document.getElementById('next-btn');
        const finishBtn = document.getElementById('finish-btn');
        const tryAgainBtn = document.getElementById('try-again-btn');
        const skipBtn = document.getElementById('skip-btn');
        
        const nextRoute = window.gameNextRoute || "{{ route('main') }}";
        
        const correctChoice = document.querySelector('.correct-choice');
        const wrongChoice = document.querySelector('.wrong-choice');
        
        if (correctChoice && wrongChoice) {
            correctChoice.addEventListener('click', function() {
                showInfoModal();
            });
            
            wrongChoice.addEventListener('click', function() {
                showWrongModal();
            });
        }
        
        const illegalBtn = document.getElementById('illegal-btn');
        const legalBtn = document.getElementById('legal-btn');
        
        if (illegalBtn && legalBtn) {
            illegalBtn.addEventListener('click', function() {
                showInfoModal(); 
            });

            legalBtn.addEventListener('click', function() {
                showWrongModal();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                hideModal(infoOverlay, function() {
                    setTimeout(() => {
                        showSuccessModal();
                    }, 300);
                });
            });
        }

        if (finishBtn) {
            finishBtn.addEventListener('click', function() {
                hideModal(successOverlay, function() {
                    setTimeout(() => {
                        window.location.href = nextRoute;
                    }, 300);
                });
            });
        }

        if (tryAgainBtn) {
            tryAgainBtn.addEventListener('click', function() {
                hideModal(wrongOverlay);
            });
        }

        if (skipBtn) {
            skipBtn.addEventListener('click', function() {
                hideModal(wrongOverlay, function() {
                    setTimeout(() => {
                        window.location.href = nextRoute;
                    }, 300);
                });
            });
        }
        
        function showInfoModal() {
            if (infoOverlay) {
                infoOverlay.classList.remove('hidden');
                infoOverlay.classList.add('animate-fadeIn');
            }
        }

        function showSuccessModal() {
            if (successOverlay) {
                successOverlay.classList.remove('hidden');
                successOverlay.classList.add('animate-fadeIn');
            }
        }
        
        function showWrongModal() {
            if (wrongOverlay) {
                wrongOverlay.classList.remove('hidden');
                wrongOverlay.classList.add('animate-fadeIn');
            }
        }
        
        function hideModal(modal, callback) {
            if (modal) {
                modal.classList.remove('animate-fadeIn');
                modal.classList.add('animate-fadeOut');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('animate-fadeOut');
                    if (callback) callback();
                }, 500);
            }
        }
    });
</script>

<style>
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
    
    #info-overlay .bg-white,
    #success-overlay .bg-white,
    #wrong-overlay .bg-white {
        animation: modalSlideIn 0.5s ease-in-out forwards;
    }
    
    #info-overlay.animate-fadeOut .bg-white,
    #success-overlay.animate-fadeOut .bg-white,
    #wrong-overlay.animate-fadeOut .bg-white {
        animation: modalSlideOut 0.5s ease-in-out forwards;
    }
    
    @keyframes modalSlideIn {
        0% { 
            opacity: 0; 
            transform: scale(0.9) translateY(-20px); 
        }
        100% { 
            opacity: 1; 
            transform: scale(1) translateY(0); 
        }
    }
    
    @keyframes modalSlideOut {
        0% { 
            opacity: 1; 
            transform: scale(1) translateY(0); 
        }
        100% { 
            opacity: 0; 
            transform: scale(0.9) translateY(-20px); 
        }
    }
    
    .choice-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 25px rgba(0,0,0,0.15) !important;
    }
    
    .choice-card:active {
        transform: translateY(0);
        box-shadow: 0 0 15px rgba(0,0,0,0.1) !important;
    }
    
    button:hover {
        transform: translateY(-1px);
    }

    button:active {
        transform: translateY(0);
    }
    
    img {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
    
    @media (max-width: 640px) {
        .choice-card img {
            width: 100%;
            height: 160px;
        }
        
        #info-overlay .bg-white,
        #success-overlay .bg-white,
        #wrong-overlay .bg-white {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }
        
        .card-container {
            padding: 0.2rem 0.5rem;
        }
        
        h2 {
            font-size: 1rem;
        }
        
        h3 {
            font-size: 1.1rem;
        }
        
        .text-sm {
            font-size: 0.875rem;
        }
        
        .space-y-2 > * + * {
            margin-top: 0.75rem;
        }
        
        button {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }
    }
</style>