<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('questionnaire-form');
    if (form) {
        initializeQuestionnaireForm(form);
    }
    

    const carousel = document.getElementById('result-carousel');
    const dots = document.querySelectorAll('.dot');
    if (carousel && dots.length > 0) {
        initializeCarousel(carousel, dots);
    }
});


function initializeQuestionnaireForm(form) {
    const submitButton = document.querySelector('.submit-btn');
    if (!submitButton) {
        console.log('ไม่พบปุ่ม .submit-btn');
        return; 
    }
    
    submitButton.disabled = false;
    submitButton.classList.remove('opacity-75', 'cursor-not-allowed');
    if (submitButton.querySelector('.animate-spin')) {
        submitButton.textContent = 'ส่งคำตอบ';
    }

    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    
    let totalQuestions = detectTotalQuestions();
    let answeredQuestions = 0;

    if (progressBar && progressPercentage) {
        updateProgress();
    }

    setupRadioButtonListeners();
    setupFormSubmitListener(form, submitButton, totalQuestions);
    setupPageShowListener(submitButton);


    function detectTotalQuestions() {
        const currentPath = window.location.pathname;
        
        if (currentPath.includes('mental_health')) {
            return 21;
        } else if (currentPath.includes('cyberbullying/overview')) {
            return 18;
        } else if (currentPath.includes('cyberbullying/person_action') || currentPath.includes('cyberbullying/victim')) {
            return 9;
        }
        
        const questionContainers = document.querySelectorAll('.question-container');
        if (questionContainers.length > 0) {
            return questionContainers.length;
        }
        
        return 21;
    }

    function updateProgress() {
        if (!progressBar || !progressPercentage) return;
        
        answeredQuestions = 0;
        
        for (let i = 1; i <= totalQuestions; i++) {
            const radios = document.querySelectorAll(`input[name="question${i}"]:checked`);
            if (radios.length > 0) {
                answeredQuestions++;
            }
        }
        
        const percentage = Math.round((answeredQuestions / totalQuestions) * 100);
        
        progressBar.style.transition = 'width 0.5s ease-in-out';
        progressBar.style.width = `${percentage}%`;
        progressPercentage.textContent = `${percentage}%`;
    }

    function scrollToCenter(element) {
        if (!element) return;
        
        const rect = element.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const finalPosition = scrollTop + rect.top - (window.innerHeight / 2) + (rect.height / 2);

        window.scrollTo({
            top: finalPosition,
            behavior: 'smooth'
        });
    }

    function handleSelection(radioElement) {
        if (!radioElement) return;
        
        const questionNumber = parseInt(radioElement.getAttribute('data-question'));
        if (isNaN(questionNumber)) return;
        
        const selectedValue = radioElement.value;
        
        const questionContainer = document.getElementById(`question-container-${questionNumber}`);
        if (questionContainer) {
            const allRadios = questionContainer.querySelectorAll('.radio-option');
            allRadios.forEach(radio => {
                radio.style.backgroundColor = 'white';
                radio.style.borderColor = '#d1d5db';
                radio.style.boxShadow = 'none';
                radio.checked = false;
            });
            
            radioElement.checked = true;
            radioElement.style.backgroundColor = '#3E36AE';
            radioElement.style.borderColor = '#3E36AE';
            radioElement.style.boxShadow = 'inset 0 0 0 2px white';
            
            const allOptions = questionContainer.querySelectorAll('.option-container');
            allOptions.forEach(option => {
                if (option && option.getAttribute('data-value') === selectedValue) {
                    option.classList.add('selected-option');
                } else if (option) {
                    option.classList.remove('selected-option');
                }
            });
        }
        
        const questionTitle = document.getElementById(`question-title-${questionNumber}`);
        if (questionTitle) {
            questionTitle.style.color = '#4b5563';
            questionTitle.style.fontWeight = '600';
            
            const asterisk = questionTitle.querySelector('.required-asterisk');
            if (asterisk) {
                asterisk.remove();
            }
        }
        
        const allOptionTexts = questionContainer.querySelectorAll('.option-text');
        allOptionTexts.forEach(optionText => {
            if (optionText) {
                optionText.style.color = '#4b5563'; 
            }
        });
        
        if (questionNumber < totalQuestions) {
            const nextQuestionNumber = questionNumber + 1;
            const nextQuestionContainer = document.getElementById(`question-container-${nextQuestionNumber}`);
            if (nextQuestionContainer) {
                setTimeout(() => {
                    scrollToCenter(nextQuestionContainer);
                }, 300);
            }
        }
        
        if (progressBar && progressPercentage) {
            updateProgress();
        }
    }

    function setupRadioButtonListeners() {
        const radioButtons = document.querySelectorAll('.radio-option');
        radioButtons.forEach(radio => {
            if (radio) {
                radio.addEventListener('change', function() {
                    handleSelection(this);
                });
            }
        });
    }

    function setupFormSubmitListener(form, submitButton, totalQuestions) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const allQuestionTitles = document.querySelectorAll('.question-title');
            allQuestionTitles.forEach(title => {
                const asterisk = title.querySelector('.required-asterisk');
                if (asterisk) {
                    asterisk.remove();
                }
            });
            
            const originalButtonText = submitButton.innerHTML;
            submitButton.innerHTML = `
                <div class="flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            `;
            
            submitButton.disabled = true;
            submitButton.classList.add('opacity-75', 'cursor-not-allowed');
            
            let allAnswered = true;
            let firstUnanswered = null;

            for (let i = 1; i <= totalQuestions; i++) {
                const radioButtons = document.querySelectorAll(`input[name="question${i}"]:checked`);
                
                if (radioButtons.length === 0) {
                    allAnswered = false;
                    
                    const questionTitle = document.getElementById(`question-title-${i}`);
                    if (questionTitle) {
                        questionTitle.style.color = '#ef4444';
                        
                        if (!questionTitle.querySelector('.required-asterisk')) {
                            const asterisk = document.createElement('span');
                            asterisk.className = 'required-asterisk';
                            asterisk.style.color = '#ef4444';
                            asterisk.style.fontWeight = 'bold';
                            asterisk.style.marginLeft = '4px';
                            asterisk.textContent = ' *';
                            questionTitle.appendChild(asterisk);
                        }
                    }
                    
                    if (firstUnanswered === null) {
                        firstUnanswered = i;
                    }
                }
            }
            
            if (allAnswered) {
                setTimeout(function() {
                    form.submit();
                }, 500);
            } else {
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-75', 'cursor-not-allowed');
                
                if (firstUnanswered !== null) {
                    const unansweredContainer = document.getElementById(`question-container-${firstUnanswered}`);
                    if (unansweredContainer) {
                        scrollToCenter(unansweredContainer);
                    }
                }
            }
        });
    }

    function setupPageShowListener(submitButton) {
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                if (submitButton) {
                    submitButton.innerHTML = 'ส่งคำตอบ';
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-75', 'cursor-not-allowed');
                }
            }
        });
    }
}

function initializeCarousel(carousel, dots) {
    let currentSlide = 0;
    let startX = 0;
    let currentX = 0;
    let isDragging = false;

    function updateDots() {
        dots.forEach((dot, index) => {
            if (index === currentSlide) {
                dot.classList.remove('bg-gray-300');
                dot.classList.add('bg-[#3E36AE]');
            } else {
                dot.classList.remove('bg-[#3E36AE]');
                dot.classList.add('bg-gray-300');
            }
        });
    }

    function moveToSlide(slideIndex) {
        if (slideIndex < 0 || slideIndex >= dots.length) return;
        
        currentSlide = slideIndex;
        const translateX = -slideIndex * 100;
        carousel.style.transform = `translateX(${translateX}%)`;
        updateDots();
    }

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            moveToSlide(index);
        });
    });

    function handleStart(e) {
        isDragging = true;
        startX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
        carousel.style.transition = 'none';
        e.preventDefault();
    }

    function handleMove(e) {
        if (!isDragging) return;
        e.preventDefault();
        currentX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
        const deltaX = currentX - startX;
        const currentTranslateX = -currentSlide * 100;
        const newTranslateX = currentTranslateX + (deltaX / carousel.offsetWidth) * 100;
        carousel.style.transform = `translateX(${newTranslateX}%)`;
    }

    function handleEnd() {
        if (!isDragging) return;
        isDragging = false;
        carousel.style.transition = 'transform 0.3s ease-in-out';
        
        const deltaX = currentX - startX;
        const threshold = carousel.offsetWidth * 0.2;

        if (Math.abs(deltaX) > threshold) {
            if (deltaX > 0 && currentSlide > 0) {
                moveToSlide(currentSlide - 1);
            } else if (deltaX < 0 && currentSlide < dots.length - 1) {
                moveToSlide(currentSlide + 1);
            } else {
                moveToSlide(currentSlide);
            }
        } else {
            moveToSlide(currentSlide);
        }
        
        startX = 0;
        currentX = 0;
    }

    carousel.addEventListener('mousedown', handleStart);
    document.addEventListener('mousemove', handleMove);
    document.addEventListener('mouseup', handleEnd);

    carousel.addEventListener('touchstart', handleStart, { passive: false });
    carousel.addEventListener('touchmove', handleMove, { passive: false });
    carousel.addEventListener('touchend', handleEnd);

    carousel.addEventListener('dragstart', e => e.preventDefault());

    document.addEventListener('keydown', function(e) {
        if (!carousel || document.activeElement === carousel) return;
        
        if (e.key === 'ArrowLeft' && currentSlide > 0) {
            moveToSlide(currentSlide - 1);
        } else if (e.key === 'ArrowRight' && currentSlide < dots.length - 1) {
            moveToSlide(currentSlide + 1);
        }
    });

    updateDots();
    moveToSlide(0);
}
</script>