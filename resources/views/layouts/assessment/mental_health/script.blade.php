<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('questionnaire-form');
        if (!form) {
            console.log('ไม่พบฟอร์ม #questionnaire-form');
            return; 
        }
    
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
        
        const totalQuestions = 21;
        let answeredQuestions = 0;
    
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
                const nextQuestionRadio = document.querySelector(`input[name="question${nextQuestionNumber}"]`);
                if (nextQuestionRadio) {
                    const nextQuestionContainer = document.getElementById(`question-container-${nextQuestionNumber}`);
                    if (nextQuestionContainer) {
                        setTimeout(() => {
                            scrollToCenter(nextQuestionContainer);
                        }, 300);
                    }
                }
            }
            
            if (progressBar && progressPercentage) {
                updateProgress();
            }
        }
    
        const radioButtons = document.querySelectorAll('.radio-option');
        radioButtons.forEach(radio => {
            if (radio) {
                radio.addEventListener('change', function() {
                    handleSelection(this);
                });
            }
        });
    
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
    
        if (progressBar && progressPercentage) {
            updateProgress();
        }
        
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                if (submitButton) {
                    submitButton.innerHTML = 'ส่งคำตอบ';
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-75', 'cursor-not-allowed');
                }
            }
        });
    });
</script>