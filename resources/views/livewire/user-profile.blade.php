<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Můj program</h1>
            <p class="text-gray-500 mb-12">Vítejte, {{ Auth::user()->nickname ?? Auth::user()->name }}. Zde najdete vaše uložené programové sloty.</p>

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

        <div class="space-y-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h2 class="text-xl font-bold mb-4">Nastavení profilu</h2>

                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="updateNickname" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Přezdívka</label>
                        <input type="text" wire:model="nickname" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        @error('nickname') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-red-700 transition">
                        Uložit přezdívku
                    </button>
                </form>

                <hr class="my-6">

                <h3 class="font-bold mb-4">Změna hesla</h3>
                <form wire:submit.prevent="updatePassword" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Současné heslo</label>
                        <input type="password" wire:model="current_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nové heslo</label>
                        <input type="password" wire:model="new_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        @error('new_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Potvrzení nového hesla</label>
                        <input type="password" wire:model="new_password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                    </div>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-gray-900 transition">
                        Změnit heslo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
