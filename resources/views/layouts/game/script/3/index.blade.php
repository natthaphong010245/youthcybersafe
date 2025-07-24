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
            if (typeof window.resetGameState === 'function') {
                window.resetGameState();
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        let correctSelections = 0;
        const requiredSelections = 2;
        let selectedCards = [];
        let currentInfoModalCard = null;

        function resetGameState() {
            const introModal = document.getElementById('intro-modal');
            const gameContent = document.getElementById('game-content');
            const feedbackOverlay = document.getElementById('feedback-overlay');
            const gameCompleteModal = document.getElementById('game-complete-modal');
            
            [feedbackOverlay, gameCompleteModal].forEach(modal => {
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('animate-fadeIn', 'animate-fadeOut', 'animate-modal-show', 'animate-modal-fade-out');
                }
            });
            
            document.querySelectorAll('.card-option').forEach(card => {
                const cardDiv = card.querySelector('div');
                const cardImage = card.querySelector('.card-image');
                const textElement = card.querySelector('.text-indigo-700, .text-gray-500');
                const marker = card.querySelector('.marker-overlay');
                
                cardDiv.classList.remove('border-green-500', 'border-red-500', 'border-2');
                cardDiv.classList.add('border-indigo-200');
                
                if (cardImage) {
                    cardImage.classList.remove('opacity-50');
                }
                
                if (textElement) {
                    textElement.classList.remove('text-gray-500');
                    textElement.classList.add('text-indigo-700');
                }
                
                if (marker) {
                    marker.classList.add('hidden');
                    marker.classList.remove('animate-fadeIn');
                }
            });
            
            correctSelections = 0;
            selectedCards = [];
            currentInfoModalCard = null;
            
            if (gameContent) {
                gameContent.classList.remove('game-blur', 'animate-unblur');
            }
            
            if (introModal && introModal.style.display !== 'none') {
                gameContent.classList.add('game-blur');
                setTimeout(() => {
                    introModal.classList.add('animate-modal-show');
                }, 100);
            }
        }

        window.resetGameState = resetGameState;

        resetGameState();

        const introModal = document.getElementById('intro-modal');
        const gameContent = document.getElementById('game-content');
        const startGameBtn = document.getElementById('start-game-btn');

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
                }, 300);
            });
        }

        const cardGrid = document.getElementById('card-grid');
        if (cardGrid) {
            const cards = Array.from(cardGrid.children);
            
            for (let i = cards.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                cardGrid.appendChild(cards[j]);
            }
        }
        
        document.querySelectorAll('.card-option').forEach(card => {
            card.addEventListener('click', function() {
                const isCorrect = this.getAttribute('data-correct') === 'true';
                const cardType = this.getAttribute('data-card');
                
                if (selectedCards.includes(cardType)) {
                    return;
                }
                
                if (isCorrect) {
                    this.querySelector('div').classList.add('border-green-500', 'border-2');
                    this.querySelector('div').classList.remove('border-indigo-200');
                    correctSelections++;
                    selectedCards.push(cardType);
                    
                    const marker = this.querySelector('.marker-overlay');
                    marker.classList.remove('hidden');
                    marker.classList.add('animate-fadeIn');
                    
                    setTimeout(() => {
                        currentInfoModalCard = cardType;
                        showInfoModal(cardType, this);
                    }, 800);
                } else {
                    this.querySelector('div').classList.add('border-red-500', 'border-2');
                    this.querySelector('div').classList.remove('border-indigo-200');
                    
                    this.querySelector('.card-image').classList.add('opacity-50');
                    const textElement = this.querySelector('.text-indigo-700');
                    if (textElement) {
                        textElement.classList.add('text-gray-500');
                        textElement.classList.remove('text-indigo-700');
                    }
                    
                    const marker = this.querySelector('.marker-overlay');
                    marker.classList.remove('hidden');
                    marker.classList.add('animate-fadeIn');
                }
            });
        });
        
        function showInfoModal(cardType, cardElement) {
            const overlay = document.getElementById('feedback-overlay');
            const content = document.getElementById('info-content');
            const continueBtn = document.getElementById('continue-btn');
            
            if (cardType === 'anonymous') {
                content.innerHTML = `
                    <img src="{{ asset('images/game/3/anonymous.png') }}" alt="Anonymous" class="w-auto h-48 mx-auto mb-4">
                    <h3 class="text-xl font-bold text-indigo-800 mb-2">ANONYMOUS</h3>
                    <p class="text-indigo-800">ความเป็นนิรนาม การไม่ระบุตัวตน เราไม่รู้จักว่าคือใครขณะเราทำออนไลน์ เป็นใครอำนาจของทุกคนในพื้นที่ออนไลน์เท่าเทียมกัน</p>
                `;
            } else if (cardType === 'anyplace') {
                content.innerHTML = `
                    <img src="{{ asset('images/game/3/place_time.png') }}" alt="Any place any time" class="w-auto h-48 mx-auto mb-4">
                    <h3 class="text-xl font-bold text-indigo-800 mb-2">ANY PLACE ANY TIME</h3>
                    <p class="text-indigo-800">เกิดได้ทุกที่ ทุกเวลา 24/7 ที่ไหนก็ได้ในโลก เพียงแค่มีอินเตอร์เน็ตเชื่อมต่อมีการแชร์ มีการแคป มีการส่งต่อ แพร่กระจายไปอย่างรวดเร็ว(virality)</p>
                `;
            }
            
            overlay.classList.remove('hidden');
            overlay.classList.add('animate-fadeIn');
            
            continueBtn.onclick = function() {
                hideModal(overlay, function() {
                    if (correctSelections >= requiredSelections) {
                        setTimeout(() => {
                            showGameCompleteModal();
                        }, 500);
                    }
                });
            };
        }

        function showGameCompleteModal() {
            const modal = document.getElementById('game-complete-modal');
            const startNextBtn = document.getElementById('start-next-game-btn');
            
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('animate-modal-show');
            }
            
            if (startNextBtn) {
                startNextBtn.onclick = function() {
                    window.location.href = "{{ route('game_4_1') }}";
                };
            }
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

        function hideGameModal(modal, callback) {
            modal.classList.remove('animate-modal-show');
            modal.classList.add('animate-modal-fade-out');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('animate-modal-fade-out');
                if (callback) callback();
            }, 300);
        }
    });
</script>

<style>
    .marker-overlay {
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 0.5rem;
        z-index: 5;
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.5s ease-in-out forwards;
    }
    
    .animate-fadeOut {
        animation: fadeOut 0.5s ease-in-out forwards;
    }
    
    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }
    
    @keyframes fadeOut {
        0% { opacity: 1; }
        100% { opacity: 0; }
    }

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

    button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    button:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-option:hover .card-image {
        transform: scale(1.02);
        transition: transform 0.2s ease;
    }

    .card-option {
        transition: all 0.2s ease;
    }

    .card-option:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
    }

    .card-option div {
        transition: all 0.3s ease;
    }
</style>