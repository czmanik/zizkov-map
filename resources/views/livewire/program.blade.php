<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{
    now: new Date(),
    startDate: new Date('{{ $eventStartDate }}T00:00:00'),
    endDate: new Date('{{ $eventEndDate }}T23:59:59'),
    getTimeRemaining() {
        const total = this.startDate - this.now;
        const seconds = Math.floor((total / 1000) % 60);
        const minutes = Math.floor((total / 1000 / 60) % 60);
        const hours = Math.floor((total / (1000 * 60 * 60)) % 24);
        const days = Math.floor(total / (1000 * 60 * 60 * 24));
        return { total, days, hours, minutes, seconds };
    }
}" x-init="setInterval(() => now = new Date(), 1000)">

    {{-- Countdown Section --}}
    <div class="mb-12 text-center bg-gray-900 text-white p-8 rounded-2xl shadow-xl">
        <template x-if="now < startDate">
            <div>
                <p class="text-gray-400 uppercase tracking-widest text-sm mb-4">Program začíná za</p>
                <div class="flex justify-center gap-4 md:gap-8">
                    <div class="flex flex-col">
                        <span class="text-3xl md:text-5xl font-black" x-text="getTimeRemaining().days"></span>
                        <span class="text-[10px] md:text-xs uppercase text-gray-500">Dní</span>
                    </div>
                    <div class="text-3xl md:text-5xl font-light text-gray-700">:</div>
                    <div class="flex flex-col">
                        <span class="text-3xl md:text-5xl font-black" x-text="String(getTimeRemaining().hours).padStart(2, '0')"></span>
                        <span class="text-[10px] md:text-xs uppercase text-gray-500">Hodin</span>
                    </div>
                    <div class="text-3xl md:text-5xl font-light text-gray-700">:</div>
                    <div class="flex flex-col">
                        <span class="text-3xl md:text-5xl font-black" x-text="String(getTimeRemaining().minutes).padStart(2, '0')"></span>
                        <span class="text-[10px] md:text-xs uppercase text-gray-500">Minut</span>
                    </div>
                    <div class="text-3xl md:text-5xl font-light text-gray-700">:</div>
                    <div class="flex flex-col">
                        <span class="text-3xl md:text-5xl font-black" x-text="String(getTimeRemaining().seconds).padStart(2, '0')"></span>
                        <span class="text-[10px] md:text-xs uppercase text-gray-500">Sekund</span>
                    </div>
                </div>
            </div>
        </template>
        <template x-if="now >= startDate && now <= endDate">
            <h2 class="text-4xl md:text-6xl font-black tracking-tighter italic uppercase">Program běží!</h2>
        </template>
        <template x-if="now > endDate">
            <h2 class="text-2xl font-bold text-gray-500">Akce již skončila</h2>
        </template>
    </div>

    <div class="flex flex-col lg:flex-row lg:items-start justify-between mb-8 gap-8">
        <div>
            <h1 class="text-5xl font-black text-gray-900 tracking-tighter mb-2">Program</h1>
            <p class="text-gray-500">Kompletní časový harmonogram festivalu.</p>
        </div>

        <div class="flex flex-col gap-4 bg-white p-6 rounded-xl shadow-sm border">
            <div class="flex flex-wrap gap-4">
                <div class="w-full md:w-64">
                    <label class="text-[10px] uppercase font-bold text-gray-400 mb-1 block">Hledat</label>
                    <input wire:model.live="search" type="text" placeholder="Název kapely, divadla..." class="w-full border-gray-200 rounded-lg px-4 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                </div>

                <div class="w-full md:w-64">
                    <label class="text-[10px] uppercase font-bold text-gray-400 mb-1 block">Místo konání</label>
                    <select wire:model.live="selectedVenue" class="w-full border-gray-200 rounded-lg px-4 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                        <option value="">Všechna místa</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="text-[10px] uppercase font-bold text-gray-400 mb-2 block">Kategorie</label>
                <div class="flex flex-wrap gap-x-6 gap-y-2">
                    @foreach($activityTypes as $type)
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="checkbox" wire:model.live="selectedActivityTypes" value="{{ $type->id }}" class="rounded border-gray-300 text-red-600 focus:ring-red-500 h-4 w-4">
                            <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-900 transition">{{ $type->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @forelse($days as $date => $slots)
        <div class="mb-12 overflow-x-auto">
            <h2 class="text-2xl font-bold mb-6">
                {{ \Carbon\Carbon::parse($date)->isoFormat('dddd D. MMMM') }}
            </h2>

            <div class="min-w-[800px] border rounded-xl overflow-hidden bg-white shadow-sm">
                <div class="grid grid-cols-12 bg-gray-100 border-b font-bold text-sm uppercase tracking-wider text-gray-600">
                    <div class="col-span-2 p-4">Čas</div>
                    <div class="col-span-3 p-4">Interpret / Akce</div>
                    <div class="col-span-3 p-4">Místo / Stage</div>
                    <div class="col-span-2 p-4">Typ</div>
                    <div class="col-span-2 p-4"></div>
                </div>

                @foreach($slots as $slot)
                    <div class="grid grid-cols-12 border-b last:border-0 hover:bg-gray-50 transition items-center">
                        <div class="col-span-2 p-4 font-bold text-red-600">
                            {{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}
                        </div>
                        <div class="col-span-3 p-4">
                            <div class="font-bold text-lg leading-tight">{{ $slot->name }}</div>
                            <div class="text-xs text-gray-500 mt-1 line-clamp-1">{{ strip_tags($slot->description) }}</div>
                        </div>
                        <div class="col-span-3 p-4">
                            <a href="/misto/{{ $slot->stage->venue->id }}" class="font-medium hover:text-red-600 block">
                                {{ $slot->stage->venue->name }}
                            </a>
                            <div class="text-xs text-gray-500">{{ $slot->stage->name }}</div>
                        </div>
                        <div class="col-span-2 p-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $slot->activityType->name }}
                            </span>
                        </div>
                        <div class="col-span-2 p-4 text-right flex justify-end gap-4">
                            @auth
                                <button wire:click="toggleFavorite({{ $slot->id }})" class="{{ Auth::user()->favoriteSlots->contains($slot->id) ? 'text-red-600' : 'text-gray-300 hover:text-red-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="{{ Auth::user()->favoriteSlots->contains($slot->id) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </button>
                            @endauth
                            <a href="/misto/{{ $slot->stage->venue->id }}" class="text-gray-400 hover:text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-24 bg-gray-100 rounded-lg">
            <p class="text-gray-500">Žádný program neodpovídá vybraným filtrům.</p>
        </div>
    @endforelse
</div>
