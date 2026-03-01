<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Časová osa programu
        </x-slot>

        <div class="space-y-8">
            @foreach($days as $day)
                <div class="overflow-x-auto">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">
                        {{ $day->translatedFormat('l d.m.Y') }}
                    </h3>

                    <div class="relative min-w-[1200px] bg-gray-50 rounded-lg p-4" style="min-height: {{ count($stages) * 50 + 60 }}px;">
                        {{-- Time markers --}}
                        <div class="flex border-b mb-4" style="margin-left: 12rem;">
                            @foreach(range(0, 24, 2) as $i)
                                <div class="flex-1 text-center text-[10px] text-gray-400 border-l h-4" style="flex-basis: 0; flex-grow: 1;">
                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00
                                </div>
                            @endforeach
                        </div>

                        @foreach($stages as $index => $stage)
                            <div class="flex items-center mb-2 group">
                                <div class="w-48 pr-4 text-xs font-medium" title="{{ $stage->name }} ({{ $stage->venue->name }})">
                                    @if(!$record)
                                        <span class="text-gray-400 text-[9px] block leading-tight truncate">{{ $stage->venue->name }}</span>
                                    @endif
                                    <span class="block truncate leading-tight">{{ $stage->name }}</span>
                                </div>
                                <div class="flex-1 relative h-10 bg-gray-100 rounded-md shadow-inner border border-gray-200">
                                    @foreach($stage->programSlots->filter(fn($slot) => $slot->start_time->isSameDay($day)) as $slot)
                                        @php
                                            $startPercent = (($slot->start_time->hour * 60) + $slot->start_time->minute) / (24 * 60) * 100;
                                            $durationMinutes = $slot->start_time->diffInMinutes($slot->end_time);
                                            $widthPercent = ($durationMinutes / (24 * 60)) * 100;

                                            $color = match($slot->status) {
                                                'approved' => 'bg-green-600',
                                                'pending' => 'bg-amber-500',
                                                default => 'bg-slate-400',
                                            };
                                        @endphp
                                        <div
                                            class="absolute h-full {{ $color }} rounded shadow-sm text-[10px] text-white flex items-center px-2 overflow-hidden cursor-pointer hover:z-10 hover:scale-y-110 transition-all border-x border-white/20"
                                            style="left: {{ $startPercent }}%; width: {{ $widthPercent }}%;"
                                            title="{{ $slot->name }} ({{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }})"
                                        >
                                            <span class="truncate font-bold">{{ $slot->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
