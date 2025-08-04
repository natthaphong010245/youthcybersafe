{{-- resources/views/layouts/report&consultation/behavioral_report/notification.blade.php --}}
<script>
function initSuccessAlert() {
    @if (session('success'))
        showSuccessNotification();
    @endif
}

function showSuccessNotification() {
    const notification = document.getElementById('success-notification');
    const modal = notification.querySelector('div:nth-child(1)');
    
    notification.classList.remove('hidden');
    
    setTimeout(() => {
        modal.classList.remove('scale-50');
        modal.classList.add('scale-100');
    }, 10);
    
    const homeButton = document.getElementById('go-home-btn');
    homeButton.onclick = function() {
        sessionStorage.setItem('went_to_home', 'true');
        
        window.location.href = "{{ route('main') }}";
    };
}

window.addEventListener('pageshow', function(event) {
    if (sessionStorage.getItem('went_to_home') === 'true') {
        sessionStorage.removeItem('went_to_home');
        window.location.reload(true);
    }
});

window.showSuccessNotification = showSuccessNotification;
</script>