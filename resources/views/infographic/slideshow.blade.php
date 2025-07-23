{{-- resources/views/infographic/slideshow.blade.php --}}
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="mobile-web-app-capable" content="yes">
    <title>{{ $topic['title'] }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to bottom, #E5C8F6 0%, #929AFF 100%);
            overflow: hidden;
            font-family: 'K2D', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .slide-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px 80px 20px;
            box-sizing: border-box;
        }
        
        .slide-image {
            max-width: 95vw;
            max-height: 75vh;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            background: white;
            padding: 10px;
            box-sizing: border-box;
        }
        
        /* เพิ่ม responsive design สำหรับแนวตั้ง */
        @media screen and (orientation: portrait) {
            .slide-container {
                padding: 40px 15px 100px 15px;
            }
            
            .slide-image {
                max-width: 90vw;
                max-height: 80vh;
            }
            
            .navigation-area {
                width: 45% !important;
            }
        }
        
        /* เพิ่ม responsive design สำหรับแนวนอน */
        @media screen and (orientation: landscape) {
            .slide-container {
                padding: 40px 20px 70px 20px;
            }
            
            .slide-image {
                max-width: 85vw;
                max-height: 80vh;
            }
        }
        
        .navigation-area {
            position: absolute;
            top: 0;
            width: 50%;
            height: 100%;
            z-index: 10;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .nav-left {
            left: 0;
        }
        
        .nav-right {
            right: 0;
        }
        
        .progress-dots {
            position: fixed;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 20;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 18px;
            border-radius: 25px;
            backdrop-filter: blur(10px);
        }
        
        .progress-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .progress-dot.active {
            background-color: white;
            transform: scale(1.3);
        }
        
        .progress-dot:hover {
            background-color: rgba(255, 255, 255, 0.8);
        }
        
        .close-button {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 30;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .close-button:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.1);
        }
        
        .close-button svg {
            color: #666;
        }
        
        .slide-transition {
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
        
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        /* ปรับปรุงสำหรับหน้าจอขนาดเล็ก */
        @media screen and (max-width: 480px) {
            .slide-container {
                padding: 30px 10px 90px 10px;
            }
            
            .slide-image {
                max-width: 95vw;
                padding: 8px;
            }
            
            .close-button {
                top: 15px;
                right: 15px;
                padding: 8px;
            }
            
            .progress-dots {
                bottom: 10px;
                padding: 8px 14px;
                gap: 6px;
            }
            
            .progress-dot {
                width: 6px;
                height: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="close-button" onclick="closeSlideshow()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        
        <div class="navigation-area nav-left" onclick="previousSlide()"></div>
        <div class="navigation-area nav-right" onclick="nextSlide()"></div>
        
        <div id="loading-spinner" class="loading-spinner"></div>
        
        <img id="slide-image" 
             class="slide-image slide-transition" 
             src="" 
             alt="Infographic"
             onload="hideLoading()"
             style="opacity: 0;" />
    </div>
    
    <div class="progress-dots">
        @for($i = 0; $i < $totalImages; $i++)
            <div class="progress-dot {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}"></div>
        @endfor
    </div>

    <script>
        const images = @json($images);
        let currentSlide = 0;
        const totalSlides = images.length;

        const slideImage = document.getElementById('slide-image');
        const loadingSpinner = document.getElementById('loading-spinner');
        const dots = document.querySelectorAll('.progress-dot');

        document.addEventListener('DOMContentLoaded', function() {
            if (images.length > 0) {
                loadSlide(0);
            }
        });

        function loadSlide(index) {
            if (index < 0 || index >= totalSlides) return;
            
            currentSlide = index;
            showLoading();
            
            slideImage.style.opacity = '0';
            setTimeout(() => {
                slideImage.src = "{{ asset('') }}" + images[index].path;
                updateDots();
            }, 150);
        }

        function showLoading() {
            loadingSpinner.style.display = 'block';
        }

        function hideLoading() {
            loadingSpinner.style.display = 'none';
            slideImage.style.opacity = '1';
        }

        function updateDots() {
            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        function nextSlide() {
            if (currentSlide < totalSlides - 1) {
                loadSlide(currentSlide + 1);
            }
        }

        function previousSlide() {
            if (currentSlide > 0) {
                loadSlide(currentSlide - 1);
            }
        }

        function closeSlideshow() {
            window.history.back();
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            switch(e.key) {
                case 'ArrowLeft':
                    previousSlide();
                    break;
                case 'ArrowRight':
                    nextSlide();
                    break;
                case 'Escape':
                    closeSlideshow();
                    break;
            }
        });

        // Touch/Swipe navigation
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    nextSlide();
                } else {
                    previousSlide();
                }
            }
        }

        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                loadSlide(index);
            });
        });

        // Prevent context menu
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        // Auto-hide cursor
        let cursorTimeout;
        document.addEventListener('mousemove', function() {
            document.body.style.cursor = 'default';
            clearTimeout(cursorTimeout);
            cursorTimeout = setTimeout(() => {
                document.body.style.cursor = 'none';
            }, 3000);
        });

        // Handle orientation changes smoothly
        window.addEventListener('orientationchange', function() {
            setTimeout(() => {
                // Refresh image display after orientation change
                const currentSrc = slideImage.src;
                slideImage.style.opacity = '0';
                setTimeout(() => {
                    slideImage.style.opacity = '1';
                }, 100);
            }, 100);
        });
    </script>
</body>
</html>