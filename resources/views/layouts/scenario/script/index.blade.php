 @push('scripts')
<script>
  function goToScenario(scenarioId) {
    const button = document.querySelector(`[onclick="goToScenario(${scenarioId})"]`);
    const route = button?.dataset.route;
    if (route) {
      window.location.href = route;
    }
  }

  document.addEventListener('keydown', function(e) {
    const buttons = [...document.querySelectorAll('.scenario-button')];
    const current = document.activeElement;
    const currentIndex = buttons.indexOf(current);
    
    if (currentIndex === -1) return;
    
    let nextIndex = currentIndex;
    
    switch(e.key) {
      case 'ArrowRight': 
        nextIndex = currentIndex % 2 === 0 ? currentIndex + 1 : Math.min(currentIndex + 1, buttons.length - 1); 
        break;
      case 'ArrowLeft': 
        nextIndex = currentIndex % 2 === 1 ? currentIndex - 1 : Math.max(currentIndex - 1, 0); 
        break;
      case 'ArrowDown': 
        nextIndex = Math.min(currentIndex + 2, buttons.length - 1); 
        break;
      case 'ArrowUp': 
        nextIndex = Math.max(currentIndex - 2, 0); 
        break;
      case 'Enter': 
      case ' ':
        e.preventDefault();
        goToScenario(parseInt(buttons[currentIndex].dataset.route.match(/\d+/)[0])); 
        return;
    }
    
    if (nextIndex !== currentIndex && buttons[nextIndex]) {
      e.preventDefault();
      buttons[nextIndex].focus();
    }
  });

  document.querySelectorAll('.scenario-button').forEach(button => {
    button.addEventListener('click', function() {
      this.style.transform = 'scale(0.95)';
      setTimeout(() => {
        this.style.transform = '';
      }, 150);
    });
  });
</script>
@endpush