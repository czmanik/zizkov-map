<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4 md:mb-0">Program</h1>

        <div class="flex flex-wrap gap-4">
            <input wire:model.live="search" type="text" placeholder="Hledat..." class="border rounded-md px-4 py-2 text-sm">

            <select wire:model.live="selectedActivityType" class="border rounded-md px-4 py-2 text-sm">
                <option value="">Všechny aktivity</option>
                @foreach($activityTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
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
