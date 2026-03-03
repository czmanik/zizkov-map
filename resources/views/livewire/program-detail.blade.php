<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ url('/program') }}" class="text-red-600 font-bold flex items-center gap-2 hover:underline">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Zpět na program
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2">
            <div class="flex flex-wrap items-center gap-4 mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider" style="background-color: {{ $programSlot->activityType->color }}20; color: {{ $programSlot->activityType->color }}">
                    {{ $programSlot->activityType->name }}
                </span>
                <span class="text-gray-500 font-medium">
                    {{ $programSlot->start_time->isoFormat('dddd D. MMMM') }}
                </span>
            </div>

            <h1 class="text-5xl font-black text-gray-900 tracking-tighter mb-6">{{ $programSlot->name }}</h1>

            <div class="flex flex-wrap gap-8 mb-12 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="bg-white p-3 rounded-xl shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400 leading-none mb-1">Čas</p>
                        <p class="font-bold text-gray-900">{{ $programSlot->start_time->format('H:i') }} — {{ $programSlot->end_time->format('H:i') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="bg-white p-3 rounded-xl shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400 leading-none mb-1">Místo</p>
                        <a href="{{ route('venue.detail', ['venue' => $programSlot->stage->venue->slug]) }}" class="font-bold text-gray-900 hover:text-red-600 underline decoration-red-200 underline-offset-4">
                            {{ $programSlot->stage->venue->name }}
                        </a>
                        <p class="text-xs text-gray-500">{{ $programSlot->stage->name }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="bg-white p-3 rounded-xl shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a5.971 5.971 0 00-.941 3.197m0 0L6 18.72m12-1.883a3.07 3.07 0 010 3.766M6 16.837a3.07 3.07 0 010 3.766m15-3.988a3.3 3.3 0 01-3.189-3.189 3.3 3.3 0 013.189-3.189m-18 6.378a3.3 3.3 0 013.189-3.189 3.3 3.3 0 01-3.189-3.189m15-3.378a3.3 3.3 0 01-3.189-3.189 3.3 3.3 0 013.189-3.189m-18 6.378a3.3 3.3 0 013.189-3.189 3.3 3.3 0 01-3.189-3.189" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400 leading-none mb-1">Vhodné pro</p>
                        <p class="font-bold text-gray-900">
                            @if($programSlot->accessibility === 'all') Všechny @endif
                            @if($programSlot->accessibility === 'youth') Mládež @endif
                            @if($programSlot->accessibility === 'family') Rodiny @endif
                            @if($programSlot->accessibility === 'adults') Dospělé @endif
                        </p>
                    </div>
                </div>
            </div>

            @if($programSlot->getMedia('gallery')->count() > 0)
                <div class="mb-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($programSlot->getMedia('gallery') as $media)
                            <img src="{{ $media->getUrl('medium') }}" alt="{{ $programSlot->name }}" class="rounded-2xl w-full h-64 object-cover shadow-lg">
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="prose prose-lg max-w-none mb-12">
                {!! $programSlot->description !!}
            </div>

            @if($programSlot->external_url)
                <div class="mb-12">
                    <a href="{{ $programSlot->external_url }}" target="_blank" class="inline-flex items-center gap-2 bg-gray-900 text-white px-8 py-4 rounded-xl font-bold hover:bg-gray-800 transition">
                        Více informací / Vstupenky
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6L21 13.5m0 0L13.5 21m7.5-7.5H3" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>

        <div class="space-y-12">
            {{-- Action Box --}}
            <div class="bg-white p-8 rounded-3xl border shadow-xl sticky top-8">
                <div class="mb-8 flex justify-between items-center">
                    <h3 class="font-bold text-xl">Líbí se vám to?</h3>
                    <button wire:click="toggleFavorite({{ $programSlot->id }})" class="{{ in_array($programSlot->id, $favoriteSlotIds) ? 'text-red-600' : 'text-gray-300 hover:text-red-400' }} transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="{{ in_array($programSlot->id, $favoriteSlotIds) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <p class="text-xs uppercase font-bold text-gray-400 tracking-widest">Sdílet program</p>
                    <div class="flex gap-4">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="flex-1 flex justify-center items-center gap-2 bg-[#1877F2] text-white py-3 rounded-xl font-bold hover:opacity-90 transition">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($programSlot->name) }}" target="_blank" class="flex-1 flex justify-center items-center gap-2 bg-[#1DA1F2] text-white py-3 rounded-xl font-bold hover:opacity-90 transition">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            Twitter
                        </a>
                    </div>
                </div>

                @if($nextOnVenue->count() > 0)
                    <div class="mt-12">
                        <p class="text-xs uppercase font-bold text-gray-400 tracking-widest mb-6">Co následuje v tomto místě</p>
                        <div class="space-y-6">
                            @foreach($nextOnVenue as $next)
                                <a href="{{ route('program.detail', ['venue' => $next->stage->venue->slug, 'programSlot' => $next->slug]) }}" class="group block">
                                    <div class="flex items-start gap-4">
                                        <div class="bg-gray-100 px-3 py-1 rounded text-sm font-bold text-red-600 group-hover:bg-red-600 group-hover:text-white transition">
                                            {{ $next->start_time->format('H:i') }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 leading-tight group-hover:text-red-600 transition">{{ $next->name }}</h4>
                                            <p class="text-xs text-gray-500">{{ $next->stage->name }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
