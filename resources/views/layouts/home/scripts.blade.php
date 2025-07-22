<style>
    .modal {
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .modal.show {
        opacity: 1;
        pointer-events: auto;
    }

    .modal-size {
        width: 90%;
        max-width: 400px;
        max-height: 85vh;
        height: auto;
    }

    .modal-content {
        display: flex;
        flex-direction: column;
    }

    .modal-body {
        flex: 1;
        overflow-y: auto;
        max-height: calc(85vh - 80px);
    }

    .modal-title {
        font-size: 1.125rem; 
    }

    .modal-text {
        font-size: 0.875rem; 
        line-height: 1.5;
    }

    .section-title {
        font-size: 1rem; 
    }

    .name-text {
        font-size: 0.9375rem; 
    }

    .position-text {
        font-size: 0.8125rem; 
        line-height: 1.4;
    }

    .cert-logo {
        height: 2.75rem; 
        width: 2.75rem; 
    }

    @media (max-width: 767px) {
        .modal-size {
            width: 95%;
            max-width: 350px;
            margin: 0 auto;
        }

        .modal-title {
            font-size: 1rem; 
        }

        .modal-text {
            font-size: 0.8125rem; 
        }

        .section-title {
            font-size: 0.9375rem; 
        }

        .name-text {
            font-size: 0.875rem; 
        }

        .position-text {
            font-size: 0.75rem; 

        .cert-logo {
            height: 2.5rem; 
            width: 2.5rem; 
        }
    }
}

    @media (min-width: 768px) {
        .desktop-container .modal-size {
            width: 85%;
            max-width: 380px;
        }

        .modal-title {
            font-size: 1.0625rem; 
        }

        .modal-text {
            font-size: 0.8125rem;
        }

        .section-title {
            font-size: 0.9375rem; 
        }

        .name-text {
            font-size: 0.875rem; 
        }

        .position-text {
            font-size: 0.75rem; 
        }
    }

    @media (min-width: 1024px) {
        .desktop-container .modal-size {
            width: 80%;
            max-width: 360px;
        }
    }

    .modal-body::-webkit-scrollbar {
        width: 4px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 2px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const infoBtn = document.getElementById('infoBtn');
    const modal = document.getElementById('infoModal');
    const closeBtn = document.getElementById('closeBtn');
    const modalOverlay = document.getElementById('modalOverlay');

    infoBtn.addEventListener('click', function() {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    });

    function closeModal() {
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    closeBtn.addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', closeModal);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            closeModal();
        }
    });

    modal.querySelector('.modal-content').addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
</script>