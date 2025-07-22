@push('scripts')
  <script>
    let selectedOptionValue = null;
    const currentScenarioId = {{ $scenarioId }};
    
    document.addEventListener('DOMContentLoaded', function () {
      setTimeout(() => {
        showIntroModal();
      }, 0);
    });

    function showIntroModal() {
      const modal = document.getElementById('intro-modal');
      modal.classList.add('show');
      document.getElementById('game-content').classList.remove('show');
    }

    function startGame() {
      const modal = document.getElementById('intro-modal');
      modal.classList.remove('show');

      setTimeout(() => {
        document.getElementById('game-content').classList.add('show');
      }, 0); 
    }

    function selectOption(optionId) {
      document.querySelectorAll('.option-card').forEach(card => {
        card.classList.remove('selected');
      });
      
      event.currentTarget.classList.add('selected');
      
      document.querySelector(`input[value="${optionId}"]`).checked = true;
      selectedOptionValue = optionId;
      
      setTimeout(() => {
        submitAnswer();
      }, 300);
    }

    document.querySelectorAll('input[name="option"]').forEach(radio => {
      radio.addEventListener('change', function() {
        selectedOptionValue = this.value;
      });
    });

    function submitAnswer() {
      if (!selectedOptionValue) return;
      
      const results = {
        @foreach($scenario['options'] as $option)
        '{{ $option['id'] }}': {
          isCorrect: {{ $option['isCorrect'] ? 'true' : 'false' }},
          title: '{{ $option['feedback']['title'] }}',
          message: `{{ $option['feedback']['message'] }}`,
          image: '{{ $option['isCorrect'] ? 'correct.png' : 'wrong.png' }}'
        },
        @endforeach
      };

      const result = results[selectedOptionValue];
      
      showResultModal(result);
    }

    function showResultModal(result) {
      const modal = document.getElementById('resultModal');
      const imageDiv = document.getElementById('resultImage');
      const title = document.getElementById('resultTitle');
      const message = document.getElementById('resultMessage');
      
      imageDiv.innerHTML = `<img src="{{ asset('images/') }}/${result.image}" alt="Result" class="w-full h-full object-contain">`;
      
      title.textContent = result.title;
      message.innerHTML = result.message.replace(/\n/g, '<br>');
      
      modal.style.display = 'flex'; 
      setTimeout(() => {
        modal.classList.add('show');
      }, 10); 
    }

    function goToNextScenario() {
      const modal = document.getElementById('resultModal');
      modal.classList.remove('show');
      
      setTimeout(() => {
        modal.style.display = 'none';
        
        if (currentScenarioId == 13) {
          showCompletionModal();
        } else {
          @if(isset($scenario['nextRoute']) && $scenario['nextRoute'])
            window.location.href = "{{ route($scenario['nextRoute']) }}";
          @else
            window.location.href = "{{ route('scenario.index') }}";
          @endif
        }
      }, 300);
    }

    function skipScenario() {
      if (currentScenarioId == 13) {
        showCompletionModal();
      } else {
        @if(isset($scenario['nextRoute']) && $scenario['nextRoute'])
          window.location.href = "{{ route($scenario['nextRoute']) }}";
        @else
          window.location.href = "{{ route('scenario.index') }}";
        @endif
      }
    }

    function showCompletionModal() {
      const modal = document.getElementById('completionModal');
      modal.style.display = 'flex';
      setTimeout(() => {
        modal.classList.add('show');
      }, 100);
    }

    function goToHome() {
      window.location.href = "{{ route('main') }}";
    }

    function closeModal() {
      goToNextScenario();
    }

    document.addEventListener('DOMContentLoaded', function() {
      const resultModal = document.getElementById('resultModal');
      if (resultModal) {
        resultModal.addEventListener('click', function(e) {
          if (e.target === this) {
            goToNextScenario();
          }
        });
      }
    });
  </script>
@endpush