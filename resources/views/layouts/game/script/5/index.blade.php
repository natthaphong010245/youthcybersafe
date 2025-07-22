<script>
    document.addEventListener('DOMContentLoaded', function() {
        const introModal = document.getElementById('intro-modal');
        const gameContent = document.getElementById('game-content');
        const startGameBtn = document.getElementById('start-game-btn');

        if (introModal && window.gameHasIntroModal) {
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

        const characterOptions = document.getElementById('character-options');
        const sequenceSlots = document.querySelectorAll('.sequence-slot');
        const characterElements = Array.from(characterOptions.children);
        
        let selectedSequence = [];
        let availableOptions = [...window.gameCharacters];
        const correctSequence = window.gameCorrectSequence;
        const nextRoute = window.gameNextRoute;
        const skipRoute = window.gameSkipRoute;
        
        function shuffleOptions() {
            for (let i = characterElements.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                characterOptions.appendChild(characterElements[j]);
            }
        }
        
        shuffleOptions();
        
        document.querySelectorAll('.character-option').forEach(option => {
            option.addEventListener('click', function() {
                const character = this.getAttribute('data-character');
                
                if (!availableOptions.includes(character)) return;
                
                const nextSlot = Array.from(sequenceSlots).find(slot => 
                    slot.querySelector('.slot-content').classList.contains('hidden')
                );
                
                if (nextSlot) {
                    addToSequence(character, nextSlot);
                    const buttonDiv = this.querySelector('div');
                    buttonDiv.style.backgroundColor = '#939393';
                    buttonDiv.style.color = 'white';
                    this.style.pointerEvents = 'none';
                    availableOptions = availableOptions.filter(opt => opt !== character);
                    
                    if (selectedSequence.length === correctSequence.length) {
                        setTimeout(checkAnswer, 500);
                    }
                }
            });
        });
        
        function addToSequence(character, slot) {
            const slotContent = slot.querySelector('.slot-content');
            const slotNumber = slot.querySelector('.slot-number');
            
            selectedSequence.push(character);
            
            const characterText = getCharacterText(character);
            slotContent.innerHTML = `
                <div class="selected-character bg-indigo-500 text-white rounded-lg w-full h-full flex flex-col justify-center items-center text-center font-medium relative transition-all hover:bg-indigo-600">
                    <div class="${characterText.mainSize || 'text-lg'}">${characterText.main}</div>
                    <div class="text-xs">${characterText.sub}</div>
                    <button class="remove-btn absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600" data-character="${character}">
                        ×
                    </button>
                </div>
            `;
            
            slotContent.classList.remove('hidden');
            slotNumber.classList.add('hidden');
            
            slotContent.querySelector('.remove-btn').addEventListener('click', function(e) {
                e.stopPropagation();
                removeFromSequence(character, slot);
            });
        }
        
        function removeFromSequence(character, slot) {
            const slotContent = slot.querySelector('.slot-content');
            const slotNumber = slot.querySelector('.slot-number');
            
            selectedSequence = selectedSequence.filter(item => item !== character);
            
            slotContent.innerHTML = '';
            slotContent.classList.add('hidden');
            slotNumber.classList.remove('hidden');
            
            const characterOption = document.querySelector(`[data-character="${character}"]`);
            const buttonDiv = characterOption.querySelector('div');
            buttonDiv.style.backgroundColor = '#6366f1';
            buttonDiv.style.color = 'white';
            characterOption.style.pointerEvents = 'auto';
            availableOptions.push(character);
        }
        
        function getCharacterText(character) {
            const texts = {
                'bully': { main: 'ผู้รังแก', sub: 'BULLY', mainSize: 'text-lg' },
                'victim': { main: 'ผู้ถูกรังแก', sub: 'VICTIM', mainSize: 'text-lg' },
                'bystander': { main: 'ผู้เห็นเหตุการณ์', sub: 'BYSTANDER', mainSize: 'text-lg' }
            };
            return texts[character] || { main: character, sub: '', mainSize: 'text-lg' };
        }
        
        function checkAnswer() {
            const isCorrect = JSON.stringify(selectedSequence) === JSON.stringify(correctSequence);
            
            if (isCorrect) {
                showSuccessModal();
            } else {
                showFailureModal();
            }
        }
        
        function showSuccessModal() {
            const modal = document.getElementById('success-modal');
            modal.classList.remove('hidden');
            modal.classList.add('animate-modal-show');
            
            document.getElementById('success-btn').onclick = function() {
                window.location.href = nextRoute;
            };
        }
        
        function showFailureModal() {
            const modal = document.getElementById('failure-modal');
            modal.classList.remove('hidden');
            modal.classList.add('animate-modal-show');
            
            document.getElementById('retry-btn').onclick = function() {
                modal.classList.add('hidden');
                modal.classList.remove('animate-modal-show');
                resetGame();
            };
            
            document.getElementById('skip-btn').onclick = function() {
                window.location.href = skipRoute;
            };
        }

        function resetGame() {
            document.querySelectorAll('.character-option').forEach(option => {
                const buttonDiv = option.querySelector('div');
                buttonDiv.style.backgroundColor = '#6366f1';
                buttonDiv.style.color = 'white';
                option.style.pointerEvents = 'auto';
            });

            sequenceSlots.forEach(slot => {
                const slotContent = slot.querySelector('.slot-content');
                const slotNumber = slot.querySelector('.slot-number');
                slotContent.innerHTML = '';
                slotContent.classList.add('hidden');
                slotNumber.classList.remove('hidden');
            });

            selectedSequence = [];
            availableOptions = [...window.gameCharacters];
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
    
    .aspect-square {
        aspect-ratio: 1 / 1;
    }
    
    .sequence-slot {
        transition: all 0.3s ease;
        position: relative;
    }
    
    .sequence-slot .slot-content {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 0.5rem;
    }
    
    .sequence-slot.filled {
        border-color: #6366f1;
        background-color: #f0f4ff;
    }
    
    .character-option {
        transition: all 0.3s ease;
    }
    
    .character-option:not([style*="pointer-events: none"]):hover div {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    
    .character-option div {
        transition: all 0.3s ease;
    }
    
    .remove-btn {
        transition: all 0.2s ease;
    }
    
    .remove-btn:hover {
        transform: scale(1.1);
    }
    
    .selected-character {
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
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