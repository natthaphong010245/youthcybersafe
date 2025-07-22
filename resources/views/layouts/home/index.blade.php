<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <link rel="icon" href="./images/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            background-image: linear-gradient(to bottom, #E5C8F6 0%, #929AFF @yield('gradient-percentage', '40%'));
            background-attachment: fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'K2D',
            sans-serif;
        }

        .modal {
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .modal.show {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-content {
            max-height: 90vh;
            height: 90vh;
            display: flex;
            flex-direction: column;
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
        }
    </style>
</head>

<body class="relative">
    <div class="desktop-container">
        @include('layouts.home.about')
        @include('layouts.home.logo')
        @include('layouts.home.scripts')
    </div>
</body>
</div>
@include('layouts.responsive')

</html>
