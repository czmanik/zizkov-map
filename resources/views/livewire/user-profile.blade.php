<div class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Můj program</h1>
    <p class="text-gray-500 mb-12">Vítejte, {{ Auth::user()->name }}. Zde najdete vaše uložené programové sloty.</p>

    @if($favorites->count() > 0)
        <div class="space-y-4">
            @foreach($favorites as $slot)
                <div class="bg-white rounded-lg shadow-sm border p-4 flex flex-col md:flex-row gap-6">
                    <div class="md:w-32 flex-shrink-0">
                        <div class="text-lg font-bold text-red-600">
                            {{ $slot->start_time->format('d.m. H:i') }}
                        </div>
                    </div>

                    <div class="flex-grow">
                        <h3 class="text-xl font-bold mb-1">{{ $slot->name }}</h3>
                        <div class="text-gray-600">
                            {{ $slot->stage->venue->name }} — {{ $slot->stage->name }}
                        </div>
                    </div>

                    <div>
                        <button wire:click="toggleFavorite({{ $slot->id }})" class="text-red-600 text-sm font-medium hover:underline">
                            Odstranit z programu
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-24 bg-gray-50 rounded-lg">
            <p class="text-gray-500 mb-4">Zatím nemáte uloženo žádné vystoupení.</p>
            <a href="/program" class="text-red-600 font-bold">Prohlédnout program &rarr;</a>
        </div>
    @endif
</div>
