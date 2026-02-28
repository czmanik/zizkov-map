<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Místa</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($venues as $venue)
            <a href="/misto/{{ $venue->id }}" class="group bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-md transition">
                <div class="aspect-video bg-gray-200 overflow-hidden relative">
                    @if($venue->hasMedia('gallery'))
                        <img src="{{ $venue->getFirstMediaUrl('gallery', 'thumb') }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400">Bez fotografie</div>
                    @endif
                    <div class="absolute top-4 left-4">
                        <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                            {{ $venue->venueType->name }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-2 group-hover:text-red-600 transition">{{ $venue->name }}</h2>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ strip_tags($venue->description) }}</p>
                    <div class="text-sm text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $venue->address_street }} {{ $venue->address_number }}, {{ $venue->address_city }}
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
