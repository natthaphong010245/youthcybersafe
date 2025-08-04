{{-- resources/views/layouts/report&consultation/behavioral_report/position.blade.php --}}
<style>
    .leaflet-tile-container img { filter: hue-rotate(25deg) saturate(0.8) brightness(1.05); }
    .leaflet-control-zoom { border: none !important; box-shadow: 0 1px 5px rgba(0,0,0,0.2) !important; }
    .leaflet-control-zoom a { border-radius: 10px !important; color: #666 !important; background-color: white !important; }
    .custom-marker { display: flex; align-items: center; justify-content: center; }
    
    #mapContainer {
        position: relative;
        z-index: 1;
    }
    
    .leaflet-container {
        z-index: 1 !important;
    }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
function initMap() {
    const mapLoading = document.getElementById('mapLoading');
    const locationPrompt = document.getElementById('locationPrompt');
    let map, marker;
    let locationSelected = false;

    try {
        map = L.map('map', {
            zoomControl: true,
            scrollWheelZoom: true,
            doubleClickZoom: true,
            touchZoom: true,
            dragging: true
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        const redMarkerIcon = L.divIcon({
            className: 'custom-marker',
            html: '<div style="background-color: #ea4335; width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.3);"></div>',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const userLocation = [position.coords.latitude, position.coords.longitude];
                    map.setView(userLocation, 15);
                    
                    marker = L.marker(userLocation, { icon: redMarkerIcon }).addTo(map);
                    document.getElementById('latitude').value = userLocation[0];
                    document.getElementById('longitude').value = userLocation[1];
                    locationSelected = true;
                    
                    mapLoading.style.display = 'none';
                },
                function(error) {
                    console.error("Geolocation error:", error);
                    const defaultLocation = [13.7563, 100.5018];
                    map.setView(defaultLocation, 10);
                    mapLoading.style.display = 'none';
                    locationPrompt.classList.remove('hidden');
                },
                { 
                    enableHighAccuracy: true, 
                    timeout: 10000, 
                    maximumAge: 300000 
                }
            );
        } else {
            const defaultLocation = [13.7563, 100.5018];
            map.setView(defaultLocation, 10);
            mapLoading.style.display = 'none';
            locationPrompt.classList.remove('hidden');
        }

        map.on('click', function(e) {
            const clickedLocation = [e.latlng.lat, e.latlng.lng];
            
            if (marker) {
                map.removeLayer(marker);
            }
            
            marker = L.marker(clickedLocation, { icon: redMarkerIcon }).addTo(map);
            document.getElementById('latitude').value = clickedLocation[0];
            document.getElementById('longitude').value = clickedLocation[1];
            locationSelected = true;
            
            locationPrompt.classList.add('hidden');
        });

    } catch (error) {
        console.error("Error initializing map:", error);
        if (mapLoading) mapLoading.style.display = 'none';
        document.getElementById('map').innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center bg-gray-200 rounded-lg"><svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg><p class="text-sm text-gray-600 text-center px-4">ไม่สามารถโหลดแผนที่ได้ กรุณาลองใหม่อีกครั้งในภายหลัง</p></div>';
    }
}
</script>