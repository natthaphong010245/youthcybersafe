<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anti-Cyberbullying - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        purple: {
                            light: '#E5C8F6',
                            DEFAULT: '#928AE1',
                            dark: '#3E36AE'
                        },
                        blue: {
                            light: '#929AFF'
                        },
                        gray: {
                            border: '#B1B1B1',
                            button: '#7D7D7D'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .app-content {
            background-image: linear-gradient(to bottom, #E5C8F6 0%, #929AFF @yield('gradient-percentage', '40%'));
            min-height: @yield('min-height', '100vh');
        }
        
        @yield('additional-styles')
    </style>
</head>

<body class="m-0 p-0 min-h-screen">
    <div class="app-content w-full relative flex flex-col">
        <div class="flex-1 py-5 flex flex-col items-center justify-center">
            <h1 class="text-4xl font-bold text-center mb-4">Logo</h1>
            <div class="bg-black text-white font-bold text-center py-3 px-4 rounded-lg text-sm">
                ANTI - CYBERCULLYING
            </div>
        </div>

        <div class="relative bg-white rounded-t-3xl px-8 py-4 mb-[calc(@yield('form-margin-bottom', '0'))]">
            <h2 class="text-center mb-2 text-purple-dark">@yield('form-title')</h2>
            @yield('form-content')
        </div>

        @include('layouts.login&register.footer.login')
    </div>
</body>
</html>