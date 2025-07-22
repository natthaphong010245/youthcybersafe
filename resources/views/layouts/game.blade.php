<!DOCTYPE html>
<html lang="th" class="bg-gradient-to-b from-[#E5C8F6] to-[#929AFF] bg-no-repeat bg-fixed h-screen">
@include('layouts.head')

<body class="font-k2d">
  <div class="desktop-container">
    <div class="page-layout">
      <div class="header-section">
        <div class="pt-1">
          @include('layouts.nav.sub')
          
          <div class="text-center mb-6 relative">
              <div class="flex items-center justify-center">
                  <div class="relative">
                      @yield('game-title')
                  </div>
              </div>
          </div>
        </div>
      </div>
      
      <div class="content-section">
        <main class="bg-white rounded-top-section pt-8 pb-10 desktop-main flex-grow h-full">
          @yield('content')
        </main>
      </div>
    </div>
  </div>
@include('layouts.responsive')
</body>
</html>