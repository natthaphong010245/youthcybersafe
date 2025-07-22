<!DOCTYPE html>
<html lang="th" class="bg-gradient-to-b from-[#E5C8F6] to-[#929AFF] bg-no-repeat bg-fixed h-screen">

@include('layouts.head')

<body class="font-k2d">
    <div class="desktop-container">
        <div class="page-layout">
            @include('layouts.nav.sub')
            @include('layouts.logo')

            <div class="content-section pt-8">
                <main class="main bg-white rounded-top-section pt-6 pb-10 flex-grow h-full">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    @stack('scripts')
    
    @yield('scripts')

@include('layouts.responsive')
    
</body>
</html>