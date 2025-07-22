<script>
function showSuccessNotification() {
    const notification = document.getElementById('success-notification');
    const modal = notification.querySelector('div:nth-child(1)');
    
    sessionStorage.setItem('notification_shown', 'true');
    
    const timestamp = Date.now();
    sessionStorage.setItem('notification_timestamp', timestamp);
    
    window.history.pushState({notificationShown: true, timestamp: timestamp}, '', window.location.href);
    
    notification.classList.remove('hidden');
    
    setTimeout(() => {
        modal.classList.remove('scale-50');
        modal.classList.add('scale-100');
    }, 10);
    
    const homeButton = document.getElementById('go-home-btn');
    homeButton.onclick = function() {
        sessionStorage.removeItem('notification_shown');
        sessionStorage.removeItem('notification_timestamp');
        
        window.location.href = "{{ route('main') }}";
    };
}

window.addEventListener('popstate', function(event) {
    if (sessionStorage.getItem('notification_shown') === 'true') {
        sessionStorage.removeItem('notification_shown');
        sessionStorage.removeItem('notification_timestamp');
        
        window.location.reload(true);
        return;
    }
});

window.addEventListener('load', function() {
    sessionStorage.removeItem('notification_shown');
    sessionStorage.removeItem('notification_timestamp');
    
    const notification = document.getElementById('success-notification');
    if (notification) {
        notification.classList.add('hidden');
        const modal = notification.querySelector('div');
        if (modal) {
            modal.classList.remove('scale-100');
            modal.classList.add('scale-50');
        }
    }
});

window.addEventListener('beforeunload', function() {
    sessionStorage.removeItem('notification_shown');
    sessionStorage.removeItem('notification_timestamp');
});

document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        sessionStorage.removeItem('notification_shown');
        sessionStorage.removeItem('notification_timestamp');
    }
});

window.showSuccessNotification = showSuccessNotification;
</script>