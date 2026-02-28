<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ $record->name }}</h1>
            <div class="flex items-center gap-4 mb-8">
                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-bold uppercase tracking-wider">
                    {{ $record->venueType->name }}
                </span>
                <span class="text-gray-500">
                    {{ $record->address_street }} {{ $record->address_number }}, {{ $record->address_city }}
                </span>
            </div>

            <div class="prose max-w-none mb-12">
                {!! $record->description !!}
            </div>

            @if($record->hasMedia('gallery'))
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-12">
                    @foreach($record->getMedia('gallery') as $media)
                        <a href="{{ $media->getUrl('large') }}" target="_blank" class="aspect-square rounded-lg overflow-hidden border">
                            <img src="{{ $media->getUrl('thumb') }}" class="w-full h-full object-cover hover:scale-105 transition">
                        </a>
                    @endforeach
                </div>
            @endif

            <h2 class="text-2xl font-bold mb-6">Program na tomto místě</h2>
            <div class="space-y-10">
                @foreach($record->stages as $stage)
                    <div>
                        <h3 class="text-xl font-bold text-red-600 mb-4">{{ $stage->name }}</h3>
                        <div class="border-l-2 border-gray-100 ml-4 space-y-6">
                            @forelse($stage->programSlots as $slot)
                                <div class="relative pl-8">
                                    <div class="absolute -left-[9px] top-2 w-4 h-4 rounded-full bg-gray-200 border-4 border-white"></div>
                                    <div class="text-sm font-bold text-gray-400">{{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}</div>
                                    <h4 class="font-bold text-lg">{{ $slot->name }}</h4>
                                    <p class="text-gray-600 text-sm">{{ strip_tags($slot->description) }}</p>
                                </div>
                            @empty
                                <p class="text-gray-400 italic ml-8">Pro tuto stage zatím není schválený žádný program.</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-gray-50 rounded-xl p-6 sticky top-24">
                <h3 class="font-bold text-lg mb-4">Informace</h3>
                <div class="space-y-4 text-sm">
                    @if($record->web_url)
                        <a href="{{ $record->web_url }}" target="_blank" class="flex items-center text-gray-600 hover:text-red-600">
                            <span class="w-8">🌐</span> Webové stránky
                        </a>
                    @endif
                    @if($record->facebook_url)
                        <a href="{{ $record->facebook_url }}" target="_blank" class="flex items-center text-gray-600 hover:text-red-600">
                            <span class="w-8">FB</span> Facebook
                        </a>
                    @endif
                    @if($record->instagram_url)
                        <a href="{{ $record->instagram_url }}" target="_blank" class="flex items-center text-gray-600 hover:text-red-600">
                            <span class="w-8">IG</span> Instagram
                        </a>
                    @endif
                </div>

                @if($record->lat && $record->lng)
                    <div class="mt-8">
                        <div id="mini-map" class="h-48 rounded-lg border bg-gray-200"></div>
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $record->lat }},{{ $record->lng }}" target="_blank" class="block text-center mt-4 text-sm text-red-600 font-bold uppercase tracking-wider">Navigovat na místo</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
@if($record->lat && $record->lng)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('mini-map', {zoomControl: false}).setView([{{ $record->lat }}, {{ $record->lng }}], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.marker([{{ $record->lat }}, {{ $record->lng }}]).addTo(map);
    });
</script>
@endif
@endpush
