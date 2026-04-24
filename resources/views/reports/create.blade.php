<x-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 p-6">
        <div class="bg-white w-full max-w-4xl rounded-2xl shadow-xl p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Add Report</h1>

            <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Street Dropdown -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Street</label>
                    <div class="relative">
                        <select name="street" id="street-select" required
                            class="w-full border px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none bg-white cursor-pointer">
                            <option value="" disabled selected>-- Select a street --</option>
                            <option value="Hernandez St">Hernandez St</option>
                            <option value="Monreal St">Monreal St</option>
                            <option value="Escurel St">Escurel St</option>
                            <option value="Zamora St">Zamora St</option>
                            <option value="Osmeña St">Osmeña St</option>
                            <option value="Del Pilar St">Del Pilar St</option>
                            <option value="Burgos St">Burgos St</option>
                            <option value="Rizal St">Rizal St</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Upload Image -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        Upload Image <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="image" accept="image/*" required
                        class="w-full border px-4 py-2 rounded-lg">
                </div>

                <!-- Location with Map -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Location</label>

                    <button type="button" id="detect-location"
                        class="mb-3 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        📍 Pin My Location
                    </button>

                    <!-- Google Map -->
                    <div id="map" class="w-full h-96 rounded-lg border-2 border-gray-300 mb-3"></div>

                    <input type="hidden" name="location" id="location-input" required>

                    <p class="text-sm text-gray-600" id="location-display">
                        Click on the map to pin your location
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition font-semibold">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let map, marker, selectedLocation = null;

        function initMap() {
            const defaultLocation = { lat: 14.5995, lng: 120.9842 };

            map = new google.maps.Map(document.getElementById('map'), {
                center: defaultLocation,
                zoom: 13,
                mapTypeControl: true,
                streetViewControl: false,
            });

            map.addListener('click', (event) => {
                placeMarker(event.latLng);
            });
        }

        function placeMarker(location) {
            if (marker) { marker.setMap(null); }

            marker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
            });

            marker.addListener('dragend', (event) => updateLocation(event.latLng));
            updateLocation(location);
        }

        function updateLocation(latLng) {
            const lat = latLng.lat();
            const lng = latLng.lng();
            selectedLocation = { lat, lng };
            document.getElementById('location-input').value = `${lat},${lng}`;
            document.getElementById('location-display').innerHTML = `
                <span class="text-green-600 font-semibold">✓ Location selected:</span>
                <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}"
                   target="_blank" class="text-blue-600 hover:underline">
                   ${lat.toFixed(6)}, ${lng.toFixed(6)}
                </a>`;
        }

        document.getElementById('detect-location').addEventListener('click', () => {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                return;
            }
            document.getElementById('detect-location').innerHTML = '⏳ Detecting...';
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const loc = { lat: position.coords.latitude, lng: position.coords.longitude };
                    map.setCenter(loc);
                    map.setZoom(15);
                    placeMarker(loc);
                    document.getElementById('detect-location').innerHTML = '📍 Detect My Location';
                },
                () => {
                    alert('Unable to detect your location. Please click on the map manually.');
                    document.getElementById('detect-location').innerHTML = '📍 Detect My Location';
                }
            );
        });

        document.querySelector('form').addEventListener('submit', (e) => {
            if (!selectedLocation) {
                e.preventDefault();
                alert('Please select a location on the map before submitting.');
            }
        });
    </script>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initMap">
        </script>
</x-layout>