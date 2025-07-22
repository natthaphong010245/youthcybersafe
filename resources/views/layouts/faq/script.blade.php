<script>
        function toggleFaqItem(button) {
            const faqItem = button.closest('.faq-item');
            const answer = faqItem.querySelector('.faq-answer');
            const iconPlus = button.querySelector('.icon-plus');
            const iconMinus = button.querySelector('.icon-minus');
            
            const isCurrentlyOpen = answer.classList.contains('active');
            
            if (isCurrentlyOpen) {
                answer.classList.remove('active');
                button.classList.remove('active');
                iconPlus.classList.remove('hidden');
                iconMinus.classList.add('hidden');
            } else {
                answer.classList.add('active');
                button.classList.add('active');
                iconPlus.classList.add('hidden');
                iconMinus.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const allAnswers = document.querySelectorAll('.faq-answer');
            const allQuestions = document.querySelectorAll('.faq-question');
            const allIconsPlus = document.querySelectorAll('.icon-plus');
            const allIconsMinus = document.querySelectorAll('.icon-minus');
            
            allAnswers.forEach(answer => answer.classList.remove('active'));
            allQuestions.forEach(question => question.classList.remove('active'));
            allIconsPlus.forEach(icon => icon.classList.remove('hidden'));
            allIconsMinus.forEach(icon => icon.classList.add('hidden'));
        });
    </script>

    <style>
        .border-custom {
            border-color: #524AC4;
        }
        
        .faq-item {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .faq-question.active .faq-icon {
            background-color: #4A4A4A !important;
        }
        
        .faq-answer.active {
            max-height: 1000px;
        }

        .faq-answer a {
            color: rgb(37 99 235);
            text-decoration: underline;
        }

        .faq-answer a:hover {
            color: rgb(29 78 216);
        }

        .icon-plus,
        .icon-minus {
            transition: opacity 0.3s ease;
        }
    </style>