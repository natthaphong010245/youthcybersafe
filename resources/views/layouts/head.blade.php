<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>YOUTH CYBERSAFE</title>
  <link rel="icon" href="{{ asset('images/logo.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=K2D:wght@500&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
  background-image: linear-gradient(to bottom, #E5C8F6 0%, #929AFF @yield('gradient-percentage', '40%'));
  background-attachment: fixed;
  background-size: cover;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  font-family: 'K2D', sans-serif;
}

html, body {
  height: 100%;
  display: flex;
  flex-direction: column;
}

.rounded-top-section {
  border-top-left-radius: 40px;
  border-top-right-radius: 40px;
}

.page-layout {
  display: flex;
  flex-direction: column;
  min-height: 100%;
}

.header-section {
  flex: 0 0 auto;
}

.content-section {
  flex: 1 1 auto;
  display: flex;
  flex-direction: column;
}

img {
  max-width: 100%;
  height: auto;
}

.card-container {
  display: flex;
  flex-direction: column;
}

@media (min-width: 768px) {
  .desktop-container {
    width: 90%;
    max-width: 1000px;
    margin: 0 auto;
  }
  
  .desktop-main {
    max-width: 768px;
    margin: 0 auto;
  }
}
  </style>
</head>