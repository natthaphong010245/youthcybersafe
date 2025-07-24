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
        const messageInput = document.getElementById('message-input');
        const addMessageBtn = document.getElementById('add-message-btn');
        const nextBtn = document.getElementById('next-btn');
        const messagesContainer = document.getElementById('messages-container');
        
        let messages = [];
        const maxMessages = 8;

        function resetGameState() {
            const introModal = document.getElementById('intro-modal');
            const gameContent = document.getElementById('game-content');
            
            messages = [];
            
            if (messagesContainer) {
                messagesContainer.innerHTML = '';
            }
            
            if (messageInput) {
                messageInput.value = '';
            }
            
            updateUI();
            
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
        
        function addMessage() {
            const messageText = messageInput.value.trim();
            
            if (messageText === '' || messages.length >= maxMessages) {
                return;
            }
            
            messages.push(messageText);
            messageInput.value = '';
            updateMessagesDisplay();
            updateUI();
        }
        
        function updateMessagesDisplay() {
            if (!messagesContainer) return;
            
            messagesContainer.innerHTML = '';
            
            const containerWidth = messagesContainer.clientWidth;
            const containerHeight = messagesContainer.clientHeight;
            const centerX = containerWidth / 2;
            const centerY = containerHeight / 2;
            const radius = Math.min(containerWidth, containerHeight) * 0.35;
            
            messages.forEach((message, index) => {
                let angle;
                
                if (index === 0) {
                    angle = -90;
                } else if (index === 1) {
                    angle = 0;
                } else if (index === 2) {
                    angle = 90; 
                } else if (index === 3) {
                    angle = 180;
                } else {
                    const mainPositions = [-90, 0, 90, 180];
                    const gaps = [-45, 45, 135, -135];
                    const extraPositions = [-67.5, -22.5, 22.5, 67.5, 112.5, 157.5];
                    
                    if (index < 8) {
                        angle = gaps[index - 4];
                    } else {
                        angle = extraPositions[index - 8];
                    }
                }
                
                const radian = (angle * Math.PI) / 180;
                
                const x = centerX + radius * Math.cos(radian);
                const y = centerY + radius * Math.sin(radian);
                
                const messageElement = document.createElement('div');
                messageElement.className = 'absolute transform -translate-x-1/2 -translate-y-1/2 bg-[#929AFF]/10 text-[#5F58C9] px-4 py-2 rounded-lg text-sm font-medium shadow-md border border-[#5F58C9]/75 animate-fadeIn cursor-pointer hover:bg-[#929AFF]/20 transition-colors';
                messageElement.style.left = x + 'px';
                messageElement.style.top = y + 'px';
                messageElement.textContent = message;
                
                messageElement.onclick = () => {
                    removeMessage(index);
                };
                
                messagesContainer.appendChild(messageElement);
            });
        }
        
        function removeMessage(index) {
            messages.splice(index, 1);
            updateMessagesDisplay();
            updateUI();
        }
        
        function updateUI() {
            if (addMessageBtn) {
                addMessageBtn.disabled = messages.length >= maxMessages;
            }
            
            if (nextBtn) {
                nextBtn.disabled = messages.length === 0;
            }
        }
        
        if (addMessageBtn) {
            addMessageBtn.addEventListener('click', addMessage);
        }
        
        if (messageInput) {
            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    addMessage();
                }
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                console.log('Messages to save:', messages);
                window.location.href = "{{ route('game_7') }}";
            });
        }
        
        window.addEventListener('resize', function() {
            if (messages.length > 0) {
                updateMessagesDisplay();
            }
        });
        
        updateUI();
    });
</script>

<style>
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-in-out forwards;
    }
    
    @keyframes fadeIn {
        0% { 
            opacity: 0; 
            transform: translate(-50%, -50%) scale(0.8); 
        }
        100% { 
            opacity: 1; 
            transform: translate(-50%, -50%) scale(1); 
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
    
    @media (max-width: 640px) {
        .card-container {
            padding: 0.75rem;
            padding-bottom: 1rem;
        }
        
        h2 {
            font-size: 1rem;
            padding: 0 1rem;
            margin-bottom: 1rem;
        }
        
        #messages-container {
            font-size: 0.75rem;
        }
        
        #messages-container div {
            padding: 0.5rem 0.5rem;
            max-width: 120px;
            font-size: 1rem;
            text-align: center;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }
    
    #messages-container div {
        max-width: 150px;
        white-space: nowrap;
        text-align: center;
        transition: all 0.3s ease;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    #messages-container div:hover {
        transform: translate(-50%, -50%) scale(1.05);
        z-index: 10;
        max-width: 200px;
        white-space: normal;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    #message-input:focus {
        outline: none;
        box-shadow: none;
    }
    
    button {
        transition: all 0.2s ease;
    }
    
    button:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    button:active:not(:disabled) {
        transform: translateY(0);
    }
    
    .card-container {
        margin-bottom: 0 !important;
    }
    
    .bg-white {
        padding-bottom: 1rem;
    }
</style>