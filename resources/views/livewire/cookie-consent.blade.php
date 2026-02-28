<div>
    @if($showBanner)
        <div class="fixed bottom-0 inset-x-0 pb-2 sm:pb-5 z-50">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="p-4 rounded-lg bg-gray-900 shadow-2xl border border-gray-700">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <h3 class="text-white font-bold text-lg mb-1">Nastavení cookies</h3>
                            <p class="text-gray-300 text-sm">
                                Tento web používá cookies pro zajištění nejlepší uživatelské zkušenosti. Můžete si zvolit, které kategorie cookies chcete povolit.
                            </p>

                            <div class="mt-4 flex flex-wrap gap-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" checked disabled class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 opacity-50">
                                    <span class="ml-2 text-sm text-gray-300">Nezbytné (vždy zapnuto)</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="analytics" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-300">Analytické</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="marketing" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-300">Marketingové</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                            <button wire:click="acceptSelected" class="flex-1 px-4 py-2 border border-gray-600 rounded-md text-sm font-medium text-white hover:bg-gray-800 focus:outline-none transition">
                                Přijmout vybrané
                            </button>
                            <button wire:click="acceptAll" class="flex-1 px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none transition">
                                Přijmout vše
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
