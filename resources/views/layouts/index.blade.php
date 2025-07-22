<!DOCTYPE html>
<html lang="th" class="bg-gradient-to-b from-[#E5C8F6] to-[#929AFF] bg-no-repeat bg-fixed min-h-screen">

@include('layouts.head')

<body class="font-k2d relative">
    <div class="desktop-container">
        @yield('content')
    </div>
</body>
@include('layouts.responsive')

</html>