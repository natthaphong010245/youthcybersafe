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
        const container = document.getElementById('mystery-container');
        const wrongOverlay = document.getElementById('wrong-overlay');
        const infoOverlay = document.getElementById('info-overlay');
        const successOverlay = document.getElementById('success-overlay');
        const tryAgainBtn = document.getElementById('try-again-btn');
        const skipBtn = document.getElementById('skip-btn');
        const nextBtn = document.getElementById('next-btn');
        const finishBtn = document.getElementById('finish-btn');
        
        let gameBoxes = [];
        let correctBoxIndex = -1;
        let gameCompleted = false;

        function resetGameState() {
            [wrongOverlay, infoOverlay, successOverlay].forEach(modal => {
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('animate-fadeIn', 'animate-fadeOut');
                }
            });

            gameCompleted = false;
            correctBoxIndex = -1;
            gameBoxes = [];

            if (gameContent) {
                gameContent.classList.remove('game-blur', 'animate-unblur');
            }

            if (container) {
                container.innerHTML = '';
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
                createMysteryBoxes();
            }, 300);
        });
        
        function createMysteryBoxes() {
            container.innerHTML = '';
            gameBoxes = [];
            
            correctBoxIndex = Math.floor(Math.random() * 3);
            
            const boxConfigs = [
                { size: 'w-48 h-48 sm:w-36 sm:h-36', position: 'top-0 left-3' },   
                { size: 'w-48 h-48 sm:w-36 sm:h-36', position: 'top-20 right-3' }, 
                { size: 'w-52 h-52 sm:w-40 sm:h-40', position: 'top-80 sm:top-60 bottom-0 left-1/2 transform -translate-x-1/2' } 
            ];
            
            for (let i = boxConfigs.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [boxConfigs[i], boxConfigs[j]] = [boxConfigs[j], boxConfigs[i]];
            }
            
            for (let i = 0; i < 3; i++) {
                const box = document.createElement('div');
                box.className = `absolute ${boxConfigs[i].size} ${boxConfigs[i].position} cursor-pointer transition-transform hover:scale-105`;
                box.innerHTML = `
                    <img src="{{ asset('images/game/7/mystery_box.png') }}" alt="Mystery Box" class="w-full h-full object-contain">
                `;
                
                box.addEventListener('click', function() {
                    handleBoxClick(i);
                });
                
                container.appendChild(box);
                gameBoxes.push(box);
            }
        }
        
        function handleBoxClick(boxIndex) {
            gameBoxes.forEach(box => {
                box.style.pointerEvents = 'none';
            });
            
            if (boxIndex === correctBoxIndex) {
                gameCompleted = true;
                setTimeout(() => {
                    showInfoModal(); 
                }, 300);
            } else {
                setTimeout(() => {
                    showWrongModal();
                }, 300);
            }
        }
        
        function showWrongModal() {
            wrongOverlay.classList.remove('hidden');
            wrongOverlay.classList.add('animate-fadeIn');
        }
        
        function showInfoModal() {
            infoOverlay.classList.remove('hidden');
            infoOverlay.classList.add('animate-fadeIn');
        }
        
        function showSuccessModal() {
            successOverlay.classList.remove('hidden');
            successOverlay.classList.add('animate-fadeIn');
        }
        
        function hideModal(modal, callback) {
            modal.classList.remove('animate-fadeIn');
            modal.classList.add('animate-fadeOut');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('animate-fadeOut');
                if (callback) callback();
            }, 500);
        }
        
        function resetGame() {
            gameBoxes.forEach(box => {
                box.style.pointerEvents = 'auto';
            });
        }
        
        tryAgainBtn.addEventListener('click', function() {
            hideModal(wrongOverlay, function() {
                resetGame();
            });
        });
        
        skipBtn.addEventListener('click', function() {
            hideModal(wrongOverlay, function() {
                window.location.href = "{{ route('game_8') }}";
            });
        });
        
        nextBtn.addEventListener('click', function() {
            hideModal(infoOverlay, function() {
                setTimeout(() => {
                    showSuccessModal();
                }, 300);
            });
        });
        
        finishBtn.addEventListener('click', function() {
            hideModal(successOverlay, function() {
                window.location.href = "{{ route('game_8') }}";
            });
        });

        window.resetGameState = resetGameState;
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
    
    #wrong-overlay .bg-white,
    #info-overlay .bg-white,
    #success-overlay .bg-white {
        animation: modalSlideIn 0.5s ease-in-out forwards;
    }
    
    #wrong-overlay.animate-fadeOut .bg-white,
    #info-overlay.animate-fadeOut .bg-white,
    #success-overlay.animate-fadeOut .bg-white {
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
    
    #mystery-container {
        position: relative;
        width: 100%;
        height: 550px; 
        max-width: 100%;
        overflow: hidden; 
    }

    button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    button:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cursor-pointer:hover {
        filter: brightness(1.1);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 640px) {
        #mystery-container {
            height: 500px;
        }
        
        .cursor-pointer {
            max-width: 140px;
            max-height: 140px;
        }
        
        .w-48 { width: 10rem; }
        .h-48 { height: 10rem; }
        .w-52 { width: 11rem; }
        .h-52 { height: 11rem; }
    }

    @media (max-width: 480px) {
        #mystery-container {
            height: 450px;
        }
        
        .top-80 {
            top: 16rem; 
        }
        
        .w-48 { width: 8rem; }
        .h-48 { height: 8rem; }
        .w-52 { width: 9rem; }
        .h-52 { height: 9rem; }
    }
</style>