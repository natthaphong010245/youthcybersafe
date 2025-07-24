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
        function resetGameState() {
            const introModal = document.getElementById('intro-modal');
            const gameContent = document.getElementById('game-content');
            const signalModal = document.getElementById('signal-modal');
            const successModal = document.getElementById('success-modal');
            const retryModal = document.getElementById('retry-modal');
            
            [signalModal, successModal, retryModal].forEach(modal => {
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex', 'animate-fadeIn', 'animate-fadeOut');
                }
            });
            
            document.querySelectorAll('.card-hover').forEach(card => {
                if (card) {
                    card.style.transform = '';
                    card.style.boxShadow = '';
                }
            });
            
            document.querySelectorAll('button').forEach(btn => {
                if (btn && !btn.id) {
                    btn.style.transform = '';
                    btn.style.boxShadow = '';
                    btn.style.pointerEvents = 'auto';
                    btn.disabled = false;
                }
            });
            
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

        if (introModal && gameContent && startGameBtn) {
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
    });

    const signalData = {
        1: {
            title: "สัญญาณเตือน\n1",
            content: `หลีกหนีสถานการณ์ทางสังคมไม่อยากไปโรงเรียนเก็บตัวไม่อยากสุงสิงกับใครมีมุมมองต่อตัว เองใน แง่ลบ เช่น ฉันอ่อนแอ ไม่มีทางสู้`
        },
         2: {
            title: "สัญญาณเตือน\n2",
            content: `-สามารถไปโรงเรียนได้ปกติ<br>-สดใส ร่าเริง เพราะคิดว่าเราน่าจะฮอตเป็นการ เรียกยอด engagement บนโซเชียล`
        }
    };

    function showSignalDetails(signalNumber) {
        const modal = document.getElementById('signal-modal');
        const numberSpan = document.getElementById('signal-number');
        const contentDiv = document.getElementById('signal-content');

        if (modal && numberSpan && contentDiv) {
            numberSpan.textContent = signalNumber;
            contentDiv.innerHTML = signalData[signalNumber].content.replace(/\n/g, '<br>');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.classList.add('animate-fadeIn');
            }, 10);
        }
    }

    function closeSignalModal() {
        const modal = document.getElementById('signal-modal');
        if (modal) {
            modal.classList.remove('animate-fadeIn');
            modal.classList.add('animate-fadeOut');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'animate-fadeOut');
            }, 500);
        }
    }

    function selectAnswer(choice) {
        closeSignalModal();

        setTimeout(() => {
            if (choice === 1) {
                showSuccessModal();
            } else {
                showRetryModal();
            }
        }, 600);
    }

    function showSuccessModal() {
        const modal = document.getElementById('success-modal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.classList.add('animate-fadeIn');
            }, 10);
        }
    }

    function showRetryModal() {
        const modal = document.getElementById('retry-modal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.classList.add('animate-fadeIn');
            }, 10);
        }
    }

    function hideModal(modal, callback) {
        if (modal) {
            modal.classList.remove('animate-fadeIn');
            modal.classList.add('animate-fadeOut');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'animate-fadeOut');
                if (callback) callback();
            }, 500);
        }
    }

    function goToNextGame() {
        const modal = document.getElementById('success-modal');
        hideModal(modal, function() {
            window.location.href = "{{ route('game_12') }}";
        });
    }

    function tryAgain() {
        const modal = document.getElementById('retry-modal');
        hideModal(modal);
    }

    function skipGame() {
        const modal = document.getElementById('retry-modal');
        hideModal(modal, function() {
            window.location.href = "{{ route('game_12') }}";
        });
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            if (e.target.id === 'signal-modal') {
                closeSignalModal();
            }
        }
    });

    document.querySelectorAll('.modal-content').forEach(modal => {
        modal.addEventListener('click', function(e) {
            e.stopPropagation();
        });
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

    .modal-backdrop {
        transition: all 0.3s ease;
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

    .card-hover {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .card-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.05);
            opacity: 0.8;
        }
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

    .animate-fadeIn .modal-content {
        animation: modalSlideIn 0.5s ease-in-out forwards;
    }
    
    .animate-fadeOut .modal-content {
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

    @media (max-width: 640px) {
        .p-8 {
            padding: 1.5rem;
        }
        .gap-4 {
            gap: 0.75rem;
        }
        .modal-content {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }
    }
    
    @media (min-width: 768px) {
        .main {
            padding-left: 2rem;
            padding-right: 2rem;
        }
    }
</style>