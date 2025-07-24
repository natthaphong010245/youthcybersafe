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
        const introModal = document.getElementById('intro-modal');
        const gameContent = document.getElementById('game-content');
        const startGameBtn = document.getElementById('start-game-btn');
        const actionModal = document.getElementById('action-modal');
        const summaryModal = document.getElementById('summary-modal');
        const nextBtn = document.getElementById('next-btn');
        const startMainBtn = document.getElementById('start-main-btn');

        const actionsData = {
            'stop': {
                title: 'หยุดการกระทำทุกอย่าง',
                description: 'นิ่งเฉยไม่ตอบโต้ เพื่อไม่ให้เกิดการกระทำซ้ำ หรือเพิ่มความรุนแรง ใช้ในในกรณีที่เป็นเหตุการณ์ทะเลาะเบาะแว้งในขั้นเริ่มต้นแล้วค่อยไปปรับความเข้าใจกันภายหลัง เช่น โดนแซวเล็กน้อย',
                image: "{{ asset('images/game/13/stop.png') }}"
            },
            'remove': {
                title: 'ลบภาพที่เป็นการระรานออกทันที',
                description: 'ลบภาพ ข้อความ วิดีโอ ที่เป็นการระรานออกทันที หรืออาจจะติดต่อเจ้าหน้าที่ที่เป็นเจ้าของพื้นที่นั้น เช่น การกดปุ่มรายงานเนื้อหาบน Facebook IG และ TikTok',
                image: "{{ asset('images/game/13/remove.png') }}"
            },
            'be-strong': {
                title: 'เข้มแข็ง',
                description: 'เข้มแข็ง อดทน และมั่นใจในคุณค่าของตนเอง ไม่ให้คุณค่ากับคนหรือคำพูดที่ทำร้ายเรา',
                image: "{{ asset('images/game/13/be_strong.png') }}"
            },
            'block': {
                title: 'ปิดกั้นพวกเขาซะ',
                description: 'บล็อกผู้กลั่นแกล้ง เพื่อไม่ให้ถูกกระทำซ้ำ',
                image: "{{ asset('images/game/13/block.png') }}"
            },
            'tell': {
                title: 'บอกบุคคลที่ไว้ใจได้',
                description: 'บอกผู้ปกครอง ครู หรือบุคคลที่ไว้ใจเพื่อขอความช่วยเหลือ และแคปเก็บบันทึกหลักฐาน',
                image: "{{ asset('images/game/13/tell.png') }}"
            }
        };

        const actionOrder = ['stop', 'remove', 'be-strong', 'block', 'tell'];
        let viewedActions = new Set();
        let currentAction = '';

        function resetGameState() {
            [actionModal, summaryModal].forEach(modal => {
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('show-modal', 'hide-modal');
                }
            });
            
            document.querySelectorAll('.action-card-inner').forEach(cardInner => {
                if (cardInner) {
                    cardInner.classList.remove('viewed');
                    cardInner.style.transform = '';
                    cardInner.style.boxShadow = '';
                    cardInner.style.borderColor = '';
                    cardInner.style.background = '';
                }
            });
            
            const modalActionImage = document.getElementById('modal-action-image');
            const modalActionTitle = document.getElementById('modal-action-title');
            const modalActionDescription = document.getElementById('modal-action-description');
            
            if (modalActionImage) {
                modalActionImage.src = '';
                modalActionImage.alt = '';
            }
            if (modalActionTitle) {
                modalActionTitle.textContent = '';
            }
            if (modalActionDescription) {
                modalActionDescription.textContent = '';
            }
            
            viewedActions.clear();
            currentAction = '';
            
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

        document.querySelectorAll('.action-card').forEach(card => {
            card.addEventListener('click', function() {
                const action = this.getAttribute('data-action') || this.querySelector(
                    '.action-card-inner').getAttribute('data-action');
                if (action) {
                    showActionModal(action);
                }
            });
        });

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                hideModal(actionModal);

                viewedActions.add(currentAction);
                markActionAsViewed(currentAction);

                if (viewedActions.size === actionOrder.length) {
                    setTimeout(() => {
                        showSummaryModal();
                    }, 500);
                }
            });
        }

        if (startMainBtn) {
            startMainBtn.addEventListener('click', function() {
                window.location.href = "{{ route('game_14') }}";
            });
        }

        function showActionModal(action) {
            const data = actionsData[action];

            currentAction = action;

            const modalActionImage = document.getElementById('modal-action-image');
            const modalActionTitle = document.getElementById('modal-action-title');
            const modalActionDescription = document.getElementById('modal-action-description');

            if (modalActionImage && modalActionTitle && modalActionDescription) {
                modalActionImage.src = data.image;
                modalActionImage.alt = data.title;
                modalActionTitle.textContent = data.title;
                modalActionDescription.textContent = data.description;

                actionModal.classList.remove('hidden');
                actionModal.classList.add('show-modal');
            }
        }

        function showSummaryModal() {
            if (summaryModal) {
                summaryModal.classList.remove('hidden');
                summaryModal.classList.add('show-modal');
            }
        }

        function hideModal(modal) {
            if (modal) {
                modal.classList.remove('show-modal');
                modal.classList.add('hide-modal');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('hide-modal');
                }, 300);
            }
        }

        function markActionAsViewed(action) {
            const cards = document.querySelectorAll('.action-card');
            cards.forEach(card => {
                const cardAction = card.getAttribute('data-action') ||
                    card.querySelector('.action-card-inner').getAttribute('data-action');
                if (cardAction === action) {
                    const cardInner = card.querySelector('.action-card-inner');
                    if (cardInner) {
                        cardInner.classList.add('viewed');
                    }
                }
            });
        }

        [actionModal, summaryModal].forEach(modal => {
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        hideModal(this);
                    }
                });
            }
        });
    });
</script>

<style>
    .container {
        max-width: 400px;
    }

    .action-card {
        cursor: pointer;
    }

    .action-card-inner {
        background: white;
        border: 1.5px solid #E2E8F0;
        border-radius: 16px;
        padding: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 120px;
    }

    .action-card-inner:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.20);
        border-color: #CBD5E0;
    }

    .action-card-inner.viewed {
        border-color: #8b5cf6;
        background: #F7FAFC;
    }

    .action-image {
        width: 100px;
        height: 100px;
        object-fit: contain;
    }

    .modal-container {
        max-width: 400px;
    }

    .summary-container {
        max-width: 450px;
    }

    .modal-icon-container {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
    }

    .modal-action-icon {
        width: 100px;
        height: 100px;
        object-fit: contain;
    }

    .modal-title {
        font-size: 1.5rem; 
    }

    .modal-subtitle {
        font-size: 1rem; 
    }

    .modal-action-title {
        font-size: 1.25rem; 
    }

    .modal-description {
        font-size: 1rem; 
    }

    .summary-title {
        font-size: 1.375rem; 
    }

    .summary-subtitle {
        font-size: 0.875rem; 
    }

    .summary-method-title {
        font-size: 1.125rem; 
    }

    .summary-method-subtitle {
        font-size: 1rem; 
    }

    .summary-text {
        font-size: 0.875rem;
    }

    .summary-challenge-text {
        font-size: 1rem; 
    }

    @media (max-width: 640px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .action-card-inner {
            padding: 16px;
            min-height: 140px;
        }

        .action-image {
            width: 110px;
            height: 110px;
        }

        .modal-container {
            max-width: 350px;
        }

        .summary-container {
            max-width: 380px;
        }

        .modal-icon-container {
            width: 140px;
            height: 140px;
        }

        .modal-action-icon {
            width: 120px;
            height: 120px;
        }

        .modal-title {
            font-size: 1.25rem; 
        }

        .modal-subtitle {
            font-size: 0.875rem; 
        }

        .modal-action-title {
            font-size: 1.125rem; 
        }

        .modal-description {
            font-size: 0.9375rem; 
        }

        .summary-title {
            font-size: 1.25rem; 
        }

        .summary-method-title {
            font-size: 1rem; 
        }

        .summary-method-subtitle {
            font-size: 0.9375rem;
        }

        .summary-text {
            font-size: 0.8125rem; 
        }
    }

    @media (min-width: 768px) {
        .desktop-container .action-image {
            width: 110px;
            height: 110px;
        }

        .desktop-container .action-card-inner {
            min-height: 150px;
            padding: 24px;
        }

        .desktop-container .modal-container {
            max-width: 380px;
        }

        .desktop-container .summary-container {
            max-width: 400px;
        }

        .desktop-container .modal-action-icon {
            width: 110px;
            height: 110px;
        }
    }

    @media (min-width: 1024px) {
        .desktop-container .action-image {
            width: 120px;
            height: 120px;
        }

        .desktop-container .action-card-inner {
            min-height: 160px;
        }

        .desktop-container .modal-action-icon {
            width: 120px;
            height: 120px;
        }
    }

    .show-modal {
        animation: backdropFadeIn 0.3s ease-in-out forwards;
    }

    .show-modal > div {
        animation: contentScaleIn 0.4s ease-out 0.1s both;
    }

    .hide-modal {
        animation: backdropFadeOut 0.3s ease-in-out forwards;
    }

    .hide-modal > div {
        animation: contentScaleOut 0.3s ease-in forwards;
    }

    @keyframes backdropFadeIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    @keyframes backdropFadeOut {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }

    @keyframes contentScaleIn {
        0% {
            opacity: 0;
            transform: scale(0.7);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes contentScaleOut {
        0% {
            opacity: 1;
            transform: scale(1);
        }
        100% {
            opacity: 0;
            transform: scale(0.7);
        }
    }

    .animate-modal-show .modal-content {
        animation: contentSlideIn 0.4s ease-out forwards;
    }

    .animate-modal-fade-out {
        animation: backdropFadeOut 0.3s ease-out forwards;
    }

    .animate-modal-fade-out .modal-content {
        animation: contentScaleOut 0.3s ease-out forwards;
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

    .action-card-inner {
        will-change: transform, box-shadow;
    }

    .modal-action-icon,
    .action-image {
        will-change: auto;
    }

    .action-card:focus {
        outline: 2px solid #3E36AE;
        outline-offset: 2px;
    }

    .summary-container {
        scroll-behavior: smooth;
    }

    .summary-container::-webkit-scrollbar {
        width: 6px;
    }

    .summary-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .summary-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .summary-container::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
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
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>