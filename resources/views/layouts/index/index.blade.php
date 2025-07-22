<!DOCTYPE html>
<html lang="th" class="bg-gradient-to-b from-[#E5C8F6] to-[#929AFF] bg-no-repeat bg-fixed h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Anti-Cyberbullying</title>
  <link rel="icon" href="./images/logo.png">
  <link href="https://fonts.googleapis.com/css2?family=K2D:wght@500&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            'k2d': ['"K2D"', 'sans-serif'],
          },
          keyframes: {
            fadeIn: {
              'from': { opacity: '0', transform: 'translateY(20px)' },
              'to': { opacity: '1', transform: 'translateY(0)' }
            },
            fadeOut: {
              'from': { opacity: '0' },
              '50%': { opacity: '1' },
              'to': { opacity: '1' }
            }
          },
          animation: {
            'fade-in': 'fadeIn 1s ease forwards',
            'fade-in-delay': 'fadeIn 1s ease forwards 0.3s',
            'fade-out': 'fadeOut 1.2s ease forwards'
          }
        }
      }
    }
  </script>
  <style>
    html, body {
      height: 100%;
      width: 100%;
    }
    
    .page-transition {
      pointer-events: none;
      opacity: 0;
      transition: opacity 1s ease;
    }
    
    .opacity-0 {
      opacity: 0;
    }
    
    .animate-fade-in {
      animation: fadeIn 1s ease forwards;
    }
    
    .animate-fade-in-delay {
      opacity: 0;
      animation: fadeIn 1s ease forwards 0.3s;
    }
    
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    @keyframes fadeOut {
      from {
        opacity: 0;
      }
      50% {
        opacity: 1;
      }
      to {
        opacity: 1;
      }
    }
  </style>
</head>
<body class="flex flex-col items-center justify-center bg-transparent overflow-hidden font-k2d min-h-screen">
  @yield('content')
  
  <div class="fixed top-0 left-0 w-full h-full bg-transparent z-[9999] page-transition" id="pageTransition"></div>

  <script>
    function centerContent() {
      const windowHeight = window.innerHeight;
      document.body.style.height = windowHeight + 'px';
    }
    
    window.addEventListener('load', centerContent);
    window.addEventListener('resize', centerContent);
    
    setTimeout(() => {
      const transition = document.getElementById('pageTransition');
      transition.style.backgroundColor = 'rgba(229, 200, 246, 1)';
      transition.classList.add('animate-fade-out');
      
      setTimeout(() => {
        window.location.href = 'home';
      }, 300); 
    }, 2000); 
  </script>
</body>
</html>