<!DOCTYPE html>
<html lang="th">

@include('layouts.head')

<body class="relative">
    <div class="desktop-container">
    @include('layouts.nav.sub')
    @include('layouts.logo')
        @yield('content')
    @yield('scripts')
    </div>
</body>
@include('layouts.responsive')

</html>
