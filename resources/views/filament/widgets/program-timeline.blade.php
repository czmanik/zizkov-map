<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Časová osa programu
        </x-slot>

        <div class="space-y-12">
            @foreach($days as $day)
                <div class="overflow-x-auto pb-4">
                    <h3 class="text-xl font-black mb-6 border-b-2 border-gray-100 pb-2 text-gray-800">
                        {{ $day->translatedFormat('l d.m.Y') }}
                    </h3>

                    <div class="relative min-w-[1400px]">
                        {{-- Time markers header --}}
                        <div class="flex mb-4 sticky top-0 bg-white z-20">
                            <div class="w-64 flex-shrink-0"></div> {{-- Spacer for labels --}}
                            <div class="flex-1 flex">
                                @foreach(range(0, 24, 2) as $i)
                                    <div class="flex-1 text-center text-[10px] font-bold text-gray-400 border-l border-gray-200 h-6 flex flex-col justify-end pb-1" style="flex-basis: 0; flex-grow: 1;">
                                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-1 relative">
                            {{-- Vertical grid lines --}}
                            <div class="absolute inset-0 flex pointer-events-none" style="left: 16rem;">
                                @foreach(range(0, 24, 2) as $i)
                                    <div class="flex-1 border-l border-gray-100 h-full" style="flex-basis: 0; flex-grow: 1;"></div>
                                @endforeach
                                <div class="border-r border-gray-100 h-full"></div>
                            </div>

                            @foreach($stages as $stage)
                                <div class="flex items-center group relative z-10">
                                    <div class="w-64 pr-6 flex-shrink-0" title="{{ $stage->name }} ({{ $stage->venue->name }})">
                                        @if(!$record)
                                            <span class="text-gray-400 text-[9px] font-bold uppercase tracking-tighter block leading-tight truncate">{{ $stage->venue->name }}</span>
                                        @endif
                                        <span class="block truncate leading-tight font-bold text-gray-700 text-sm">{{ $stage->name }}</span>
                                    </div>

                                    <div class="flex-1 relative h-12 bg-gray-50/50 rounded-lg shadow-inner border border-gray-100 group-hover:bg-gray-100 transition-colors">
                                        @foreach($stage->programSlots->filter(fn($slot) => $slot->start_time->isSameDay($day)) as $slot)
                                            @php
                                                $startPercent = (($slot->start_time->hour * 60) + $slot->start_time->minute) / (24 * 60) * 100;
                                                $durationMinutes = $slot->start_time->diffInMinutes($slot->end_time);
                                                $widthPercent = ($durationMinutes / (24 * 60)) * 100;

                                                $bgColor = $slot->activityType->color ?? '#4B5563';
                                                $isPending = $slot->status === 'pending';
                                            @endphp
                                            <div
                                                class="absolute h-[80%] top-[10%] rounded-md shadow-sm text-[10px] text-white flex flex-col justify-center px-2 overflow-hidden cursor-pointer hover:z-30 hover:scale-105 transition-all border border-black/10 {{ $isPending ? 'opacity-75 ring-2 ring-amber-400 ring-offset-1' : '' }}"
                                                style="left: {{ $startPercent }}%; width: {{ $widthPercent }}%; background-color: {{ $bgColor }};"
                                                title="{{ $slot->name }} ({{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}) [{{ $slot->activityType->name }}]{{ $isPending ? ' - ČEKÁ NA SCHVÁLENÍ' : '' }}"
                                            >
                                                <span class="truncate font-black leading-none">{{ $slot->name }}</span>
                                                <span class="text-[8px] opacity-80 font-medium">{{ $slot->start_time->format('H:i') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex flex-wrap gap-4 pt-6 border-t border-gray-100">
            <div class="text-[10px] font-bold uppercase text-gray-400 w-full mb-1">Legenda (Typy aktivit):</div>
            @foreach(\App\Models\ActivityType::all() as $type)
                <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-full border border-gray-200">
                    <div class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $type->color }}"></div>
                    <span class="text-[11px] font-bold text-gray-600 uppercase tracking-tight">{{ $type->name }}</span>
                </div>
            @endforeach
            <div class="flex items-center gap-2 bg-amber-50 px-3 py-1.5 rounded-full border border-amber-200 ml-auto">
                <div class="w-3 h-3 rounded-full shadow-sm ring-2 ring-amber-400 ring-offset-1 bg-gray-400"></div>
                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-tight italic">Čeká na schválení</span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
