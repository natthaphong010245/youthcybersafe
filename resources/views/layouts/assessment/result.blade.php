<!DOCTYPE html>
<html lang="th">

@include('layouts.head')

<body class="relative">
        <div class="desktop-container mt-6">
    <div class="mt-6">
    @include('layouts.logo')
    </div>
     @yield('content')
    @yield('scripts')
    </div>
</body>

@include('layouts.responsive')
</html>