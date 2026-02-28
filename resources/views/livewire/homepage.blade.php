<div class="relative">
    <div id="map" class="w-full h-[calc(100vh-64px)] z-0"></div>

    <div class="absolute bottom-10 left-10 z-10 bg-white p-4 rounded-lg shadow-lg max-w-sm hidden md:block">
        <h2 class="text-xl font-bold mb-2">Vítejte na {{ \App\Models\Setting::get('event_name') }}</h2>
        <p class="text-gray-600">Prozkoumejte mapu a najděte své oblíbené podniky a program.</p>
        <div class="mt-4">
            <a href="/program" class="bg-red-600 text-white px-4 py-2 rounded-md block text-center">Zobrazit celý program</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const venues = {!! $venuesJson !!};

        const map = L.map('map').setView([50.086, 14.453], 15); // Center on Žižkov

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        venues.forEach(venue => {
            if (venue.lat && venue.lng) {
                const marker = L.marker([venue.lat, venue.lng]).addTo(map);
                marker.bindPopup(`
                    <div class="p-2">
                        <h3 class="font-bold text-lg">${venue.name}</h3>
                        <p class="text-sm text-gray-600">${venue.address}</p>
                        <a href="${venue.url}" class="text-red-600 font-medium mt-2 inline-block">Detail místa &rarr;</a>
                    </div>
                `);
            }
        });
    });
</script>
@endpush
