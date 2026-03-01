<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Časová osa programu
        </x-slot>

        <div class="space-y-12">
            @foreach($days as $day)
                <div class="overflow-x-auto pb-4">
                    <h3 class="text-lg font-bold mb-4 border-b border-gray-100 pb-2 text-gray-800 capitalize">
                        {{ $day->translatedFormat('l d.m.Y') }}
                    </h3>

                    <div class="relative min-w-[1400px]">
                        {{-- Time markers header --}}
                        <div class="flex mb-2 sticky top-0 bg-white z-20">
                            <div class="w-64 flex-shrink-0"></div> {{-- Spacer for labels --}}
                            <div class="flex-1 flex">
                                @foreach(range(0, 24, 2) as $i)
                                    <div class="flex-1 text-center text-[9px] font-medium text-gray-400 border-l border-gray-200 h-6 flex flex-col justify-end pb-1" style="flex-basis: 0; flex-grow: 1;">
                                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-4 relative">
                            {{-- Vertical grid lines --}}
                            <div class="absolute inset-0 flex pointer-events-none" style="left: 16rem;">
                                @foreach(range(0, 24, 2) as $i)
                                    <div class="flex-1 border-l border-gray-100 h-full" style="flex-basis: 0; flex-grow: 1;"></div>
                                @endforeach
                                <div class="border-r border-gray-100 h-full"></div>
                            </div>

                            @foreach($stages as $stage)
                                @php $lanes = $stage->lanesByDay[$day->format('Y-m-d')] ?? []; @endphp
                                @continue(empty($lanes) && $record) {{-- If viewing specific venue, only show stages with program --}}

                                <div class="flex items-start group relative z-10 border-b border-gray-50 last:border-0 pb-2">
                                    <div class="w-64 pr-6 flex-shrink-0" title="{{ $stage->name }} ({{ $stage->venue->name }})">
                                        @if(!$record)
                                            <span class="text-gray-400 text-[9px] font-semibold uppercase tracking-tighter block leading-tight truncate">{{ $stage->venue->name }}</span>
                                        @endif
                                        <span class="block truncate leading-tight font-semibold text-gray-700 text-sm">{{ $stage->name }}</span>
                                    </div>

                                    <div class="flex-1 -space-y-px">
                                        @forelse($lanes as $lane)
                                            <div class="relative h-8 bg-gray-50/20 border border-gray-200/30 group-hover:bg-gray-100/20 transition-colors first:rounded-t last:rounded-b">
                                                @foreach($lane as $slot)
                                                    @php
                                                        $startPercent = (($slot->start_time->hour * 60) + $slot->start_time->minute) / (24 * 60) * 100;
                                                        $durationMinutes = $slot->start_time->diffInMinutes($slot->end_time);
                                                        $widthPercent = ($durationMinutes / (24 * 60)) * 100;

                                                        $bgColor = $slot->activityType->color ?? '#4B5563';
                                                        $isPending = $slot->status === 'pending';
                                                    @endphp
                                                    <div
                                                        class="absolute h-[80%] top-[10%] rounded-sm shadow-sm text-[8px] text-white flex items-center px-1.5 overflow-hidden cursor-pointer hover:z-30 hover:scale-[1.01] transition-all border border-black/10 {{ $isPending ? 'opacity-80 ring-1 ring-amber-400 ring-offset-0' : '' }}"
                                                        style="left: {{ $startPercent }}%; width: {{ $widthPercent }}%; background-color: {{ $bgColor }};"
                                                        title="{{ $slot->name }} ({{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}) [{{ $slot->activityType->name }}]{{ $isPending ? ' - ČEKÁ NA SCHVÁLENÍ' : '' }}"
                                                    >
                                                        <span class="truncate font-bold leading-tight mr-1">{{ $slot->name }}</span>
                                                        <span class="text-[6px] opacity-80 font-medium shrink-0">{{ $slot->start_time->format('H:i') }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @empty
                                            <div class="h-8 bg-gray-50/5 border border-gray-100/10 rounded"></div>
                                        @endforelse
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
