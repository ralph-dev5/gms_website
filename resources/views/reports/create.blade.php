<x-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 p-4 md:p-10">
        <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="bg-green-600 p-6">
                <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 15.667c-.77 1.333.192 3 1.732 3z"/></svg>
                    Report Garbage Issue
                </h1>
            </div>

            <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="p-8 space-y-8" id="reportForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-black text-gray-700 uppercase tracking-wider mb-2">Target Street</label>
                            <div class="relative">
                                <select name="street" id="street-select" required
                                    class="w-full border-2 border-gray-100 px-4 py-3 rounded-xl focus:border-green-500 focus:ring-0 transition appearance-none bg-gray-50 cursor-pointer text-gray-700 font-medium">
                                    <option value="" disabled selected>Select location...</option>
                                    <option value="Hernandez St">Hernandez St</option>
                                    <option value="Monreal St">Monreal St</option>
                                    <option value="Escurel St">Escurel St</option>
                                    <option value="Zamora St">Zamora St</option>
                                    <option value="Osmeña St">Osmeña St</option>
                                    <option value="Del Pilar St">Del Pilar St</option>
                                    <option value="Burgos St">Burgos St</option>
                                    <option value="Rizal St">Rizal St</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-black text-gray-700 uppercase tracking-wider mb-2">Evidence Photo</label>
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-xs text-gray-500">Tap to upload photo</p>
                                </div>
                                <input type="file" name="image" accept="image/*" required class="hidden">
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Pin Location</label>
                            <button type="button" id="detect-location"
                                class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                <span id="detect-text">📍 Refresh Location</span>
                            </button>
                        </div>

                        <div id="map" class="w-full h-64 md:h-80 rounded-2xl border-2 border-gray-100 shadow-inner"></div>

                        <input type="hidden" name="location" id="location-input" required>

                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100" id="location-display">
                            <p class="text-xs text-gray-500 italic text-center">Finding your coordinates...</p>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-green-600 text-white px-6 py-4 rounded-2xl hover:bg-green-700 transition-all font-black text-lg shadow-xl shadow-green-100 active:scale-95">
                    SUBMIT REPORT
                </button>
            </form>
        </div>
    </div>

    <script>
        let map, marker, selectedLocation = null;

        function initMap() {
            const defaultLocation = { lat: 12.6685, lng: 123.8812 };

            map = new google.maps.Map(document.getElementById('map'), {
                center: defaultLocation,
                zoom: 15,
                disableDefaultUI: true,
                zoomControl: true,
                styles: [{ featureType: "poi", elementType: "labels", stylers: [{ visibility: "off" }] }]
            });

            // Map click listener
            map.addListener('click', (event) => {
                updatePosition(event.latLng);
            });

            autoDetect();
        }

        // Shared function to update marker and internal state
        function updatePosition(latLng) {
            if (marker) { marker.setMap(null); }
            
            marker = new google.maps.Marker({
                position: latLng,
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
            });

            // Handle manual drag of the marker
            marker.addListener('dragend', (event) => {
                saveToInput(event.latLng);
            });

            saveToInput(latLng);
        }

        function saveToInput(latLng) {
            const lat = latLng.lat();
            const lng = latLng.lng();
            
            // CRITICAL: Update both the global variable AND the hidden input field
            selectedLocation = { lat, lng };
            document.getElementById('location-input').value = `${lat},${lng}`;
            
            document.getElementById('location-display').innerHTML = `
                <div class="flex items-center justify-between text-xs">
                    <span class="text-green-600 font-bold uppercase tracking-tighter">✓ Position Locked</span>
                    <span class="text-gray-400 font-mono">${lat.toFixed(5)}, ${lng.toFixed(5)}</span>
                </div>`;
        }

        function autoDetect() {
            if (!navigator.geolocation) return;

            document.getElementById('detect-text').innerText = '⏳ Locating...';

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const loc = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                    map.setCenter(loc);
                    map.setZoom(17);
                    updatePosition(loc);
                    document.getElementById('detect-text').innerText = '📍 Refresh Location';
                },
                (err) => {
                    document.getElementById('detect-text').innerText = '📍 Pin Manually';
                    document.getElementById('location-display').innerHTML = '<p class="text-xs text-red-500 text-center">Auto-location failed. Please tap the map to pin.</p>';
                }, 
                { enableHighAccuracy: true, timeout: 5000 }
            );
        }

        document.getElementById('detect-location').addEventListener('click', autoDetect);

        // Validation before submission
        document.getElementById('reportForm').addEventListener('submit', (e) => {
            const locationValue = document.getElementById('location-input').value;
            if (!selectedLocation || !locationValue) {
                e.preventDefault();
                alert('Please pin the garbage location on the map before submitting.');
            }
        });
    </script>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initMap">
    </script>
</x-layout>