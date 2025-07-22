<style>
body {
  background-image: linear-gradient(to bottom, #E5C8F6 0%, #929AFF @yield('gradient-percentage', '40%'));
  background-attachment: fixed;
  background-size: cover;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  font-family: 'K2D', sans-serif;
  margin: 0;
  padding: 0;
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

.desktop-container {
  width: 100%;
  height: 100vh;
  display: flex;
  flex-direction: column;
}

@media (min-width: 768px) {
  .desktop-container {
    width: 100%;
    max-width: 430px; 
    margin: 0 auto;
    height: 100vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    position: relative;
  }
  
  .desktop-container > * {
    flex-shrink: 0;
  }
  
  .desktop-container .page-layout {
    min-height: 100vh;
    height: 100vh;
  }
}

@media (min-width: 1024px) {
  .desktop-container {
    width: 100%;
    max-width: 430px;
    margin: 0 auto;
    height: 100vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.15);
    position: relative;
  }
}

@media (min-width: 1440px) {
  .desktop-container {
    max-width: 430px;
    height: 100vh;
  }
}

@media (min-width: 768px) {
  body {
    overflow-x: hidden;
  }
  
  .desktop-container {
    overflow-x: hidden;
    overflow-y: auto;
  }
  
  html {
    background-image: linear-gradient(to bottom, #E5C8F6 0%, #929AFF 40%);
    background-attachment: fixed;
    background-size: cover;
    min-height: 100vh;
  }
}
</style>