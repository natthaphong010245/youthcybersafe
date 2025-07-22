<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, orientation=landscape">
    <meta name="screen-orientation" content="landscape">
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
        }
        
        @media screen and (orientation: portrait) {
            html {
                transform: rotate(90deg);
                transform-origin: left top;
                width: 100vh;
                height: 100vw;
                overflow-x: hidden;
                position: absolute;
                top: 100%;
                left: 0;
            }
        }
        
        .slide-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 10px 20px 60px 20px;
        }
        
        .slide-image {
            max-width: 95vw;
            max-height: 85vh;
            object-fit: contain;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            background: white;
            padding: 10px;
            margin-top: 10px;
        }
        
        .navigation-area {
            position: absolute;
            top: 0;
            width: 50%;
            height: 100%;
            z-index: 10;
            cursor: pointer;
        }
        
        .nav-left {
            left: 0;
        }
        
        .nav-right {
            right: 0;
        }
        
        .progress-dots {
            position: fixed;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 6px;
            z-index: 20;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }
        
        .progress-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }
        
        .progress-dot.active {
            background-color: white;
            transform: scale(1.3);
        }
        
        .close-button {
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 30;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            padding: 8px;
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
        
        .title-overlay {
            position: fixed;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 25;
            background: rgba(255, 255, 255, 0.95);
            color: #3E36AE;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            max-width: 80vw;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
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

        .slide-counter {
            position: fixed;
            top: 70px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 25;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 8px 16px;
            border-radius: 15px;
            font-size: 14px;
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

        function requestLandscape() {
            if (screen.orientation && screen.orientation.lock) {
                screen.orientation.lock('landscape').catch(function(error) {
                    console.log('Orientation lock failed:', error);
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            requestLandscape();
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

        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

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

        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                loadSlide(index);
            });
        });

        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        let cursorTimeout;
        document.addEventListener('mousemove', function() {
            document.body.style.cursor = 'default';
            clearTimeout(cursorTimeout);
            cursorTimeout = setTimeout(() => {
                document.body.style.cursor = 'none';
            }, 3000);
        });
    </script>
</body>
</html>