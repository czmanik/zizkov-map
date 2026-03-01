<div class="relative">
    <div id="map" class="w-full h-[calc(100vh-64px)] z-0"></div>

    <div class="absolute bottom-10 left-10 z-10 bg-white p-4 rounded-lg shadow-lg max-w-sm hidden md:block">
        <h2 class="text-xl font-bold mb-2">Vítejte na {{ \App\Models\Setting::get('event_name') }}</h2>
        <p class="text-gray-600 mb-4">Prozkoumejte mapu a najděte své oblíbené podniky a program.</p>

        <div class="mb-4">
            <h3 class="font-bold text-sm uppercase tracking-wider text-gray-400 mb-2">Filtr aktivit</h3>
            <div class="flex flex-wrap gap-2">
                <button onclick="filterVenues(null)" class="filter-btn bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold active" data-type="all">Vše</button>
                @foreach($activityTypes as $type)
                    <button onclick="filterVenues('{{ $type->name }}')" class="filter-btn bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold hover:bg-gray-200" data-type="{{ $type->name }}">{{ $type->name }}</button>
                @endforeach
            </div>
        </div>

        <div class="mt-4">
            <a href="/program" class="bg-red-600 text-white px-4 py-2 rounded-md block text-center font-bold">Zobrazit celý program</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let map;
    let markers = [];
    const venues = {!! $venuesJson !!};

    function filterVenues(activityType) {
        // Update UI
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('bg-red-600', 'text-white', 'active');
            btn.classList.add('bg-gray-100', 'text-gray-600');
            if ((activityType === null && btn.dataset.type === 'all') || btn.dataset.type === activityType) {
                btn.classList.remove('bg-gray-100', 'text-gray-600');
                btn.classList.add('bg-red-600', 'text-white', 'active');
            }
        });

        // Clear markers
        markers.forEach(m => map.removeLayer(m));
        markers = [];

        // Add filtered markers
        venues.forEach(venue => {
            if (!venue.lat || !venue.lng) return;

            if (activityType === null || (venue.activity_types && venue.activity_types.includes(activityType))) {
                const marker = L.marker([venue.lat, venue.lng]).addTo(map);

                let activityTypesHtml = '';
                if (venue.activity_types && venue.activity_types.length > 0) {
                    activityTypesHtml = `
                        <div class="mt-2 flex flex-wrap gap-1">
                            ${venue.activity_types.map(t => `<span class="bg-gray-100 text-[10px] px-1.5 py-0.5 rounded font-bold text-gray-600 uppercase">${t}</span>`).join('')}
                        </div>
                    `;
                }

                marker.bindPopup(`
                    <div class="p-2 min-w-[200px]">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-red-600">${venue.type}</span>
                        <h3 class="font-bold text-lg leading-tight mb-1">${venue.name}</h3>
                        <p class="text-xs text-gray-600">${venue.address}</p>
                        ${activityTypesHtml}
                        <a href="${venue.url}" class="text-red-600 font-bold mt-3 inline-block text-sm">Detail místa &rarr;</a>
                    </div>
                `);
                markers.push(marker);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        map = L.map('map').setView([50.086, 14.453], 15); // Center on Žižkov

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        filterVenues(null);
    });
</script>
@endpush
