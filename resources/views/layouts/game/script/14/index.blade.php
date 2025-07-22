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

    const letterCards = document.querySelectorAll('.letter-card');
    const letterModal = document.getElementById('letter-modal');
    const summaryModal = document.getElementById('summary-modal');
    const modalImage = document.getElementById('modal-letter-image');
    const modalTitle = document.getElementById('modal-letter-title');
    const modalDescription = document.getElementById('modal-letter-description');
    const continueBtn = document.getElementById('continue-btn');
    
    // แก้ไข: เปลี่ยนจาก finishBtn เป็น exitBtn และ startBtn
    const exitBtn = document.getElementById('exit-btn');
    const startBtn = document.getElementById('start-btn');
    
    let selectedLetters = new Set();
    let currentLetter = '';
    
    const letterData = {
        'c': {
            title: 'Cyber Security',
            description: 'ใช้อย่างปลอดภัย',
            image: '{{ asset("images/game/14/c.png") }}'
        },
        'o': {
            title: 'Online Empath',
            description: 'ใส่ใจผู้อื่น',
            image: '{{ asset("images/game/14/o.png") }}'
        },
        'n1': {
            title: 'Netiquette',
            description: 'ใช้อย่างมีสุภาพ',
            image: '{{ asset("images/game/14/n.png") }}'
        },
        'n2': {
            title: 'Notification',
            description: 'ปิดการแจ้งเตือน',
            image: '{{ asset("images/game/14/n.png") }}'
        },
        'e': {
            title: 'Exploration',
            description: 'สำรวจเพื่อแนะนำ',
            image: '{{ asset("images/game/14/e.png") }}'
        },
        'c2': {
            title: 'Collect & Connect',
            description: 'แกน-แกร์ ผ่อมบกร้',
            image: '{{ asset("images/game/14/c.png") }}'
        },
        't': {
            title: 'Two-Step Verification',
            description: 'เปิดโครี่ทำกิดให้ช่วย',
            image: '{{ asset("images/game/14/t.png") }}'
        }
    };
    
    letterCards.forEach(card => {
        card.addEventListener('click', function() {
            const letter = this.getAttribute('data-letter');
            currentLetter = letter;
            showLetterModal(letter);
        });
    });
    
    continueBtn.addEventListener('click', function() {
        hideModal(letterModal);
        
        selectedLetters.add(currentLetter);
        updateCardAppearance(currentLetter);
        
        if (selectedLetters.size === 7) {
            setTimeout(() => {
                showSummaryModal();
            }, 500);
        }
    });
    
    // แก้ไข: เพิ่ม event listener สำหรับปุ่ม "ออก"
    exitBtn.addEventListener('click', function() {
        hideModal(summaryModal);
        setTimeout(() => {
            window.location.href = "{{ route('game') }}";
        }, 500);
    });
    
    // แก้ไข: เพิ่ม event listener สำหรับปุ่ม "เริ่มต้น"
    startBtn.addEventListener('click', function() {
        hideModal(summaryModal);
        setTimeout(() => {
            window.location.href = "{{ route('game_1_1') }}";
        }, 500);
    });
    
    function showLetterModal(letter) {
        const data = letterData[letter];
        
        modalImage.src = data.image;
        modalImage.alt = data.title;
        modalTitle.textContent = data.title;
        modalDescription.textContent = data.description;
        
        letterModal.classList.remove('hidden');
        letterModal.classList.add('animate-fadeIn');
    }
    
    function showSummaryModal() {
        summaryModal.classList.remove('hidden');
        summaryModal.classList.add('animate-fadeIn');
    }
    
    function hideModal(modal) {
        modal.classList.remove('animate-fadeIn');
        modal.classList.add('animate-fadeOut');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('animate-fadeOut');
        }, 500);
    }
    
    function updateCardAppearance(letter) {
        const card = document.querySelector(`[data-letter="${letter}"]`);
        const cardInner = card.querySelector('div');
        
        cardInner.classList.remove('border-transparent', 'hover:border-indigo-200');
        cardInner.classList.add('border-purple-500');
        
        card.setAttribute('data-selected', 'true');
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
                transform: scale(0.3); // Scale down smaller for "วาปเล็กลง" effect
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
        
        #letter-modal .bg-white,
        #summary-modal .bg-white {
            animation: modalSlideIn 0.5s ease-in-out forwards;
        }
        
        #letter-modal.animate-fadeOut .bg-white,
        #summary-modal.animate-fadeOut .bg-white {
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
        
        .letter-card:hover div {
            transform: translateY(-2px);
        }
        
        .letter-card:active div {
            transform: translateY(0);
        }
        
        .letter-card[data-selected="true"] div {
            border-color: #8b5cf6 !important;
            border-width: 2px;
        }
        
        img {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }
        
        @media (max-width: 640px) {
            .grid {
                gap: 2rem;
                padding: 0;
            }
            
            .letter-card div {
                padding: 1rem;
            }
            
            .letter-card img {
                height: 6rem;
            }
            
            #letter-modal .bg-white,
            #summary-modal .bg-white {
                margin: 2rem;
                max-width: calc(100% - 2rem);
            }
            
            .card-container {
                padding: 1rem 0.5rem;
            }
            
            h2 {
                font-size: 1.125rem;
            }
            
            h3 {
                font-size: 1.375rem;
            }
            
            .text-base {
                font-size: 1rem;
            }
            
            .text-lg {
                font-size: 1.125rem;
            }
        }
    </style>