<?php

namespace App\Filament\Widgets;

use App\Models\ProgramSlot;
use App\Models\Setting;
use App\Models\Stage;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class ProgramTimeline extends Widget
{
    protected static string $view = 'filament.widgets.program-timeline';

    protected int | string | array $columnSpan = 'full';

    public ?\Illuminate\Database\Eloquent\Model $record = null;

    public function getStages()
    {
        $query = Stage::query();

        if ($this->record && $this->record instanceof \App\Models\Venue) {
            $query->where('venue_id', $this->record->id);
        } elseif (!Auth::user()->isSuperAdmin()) {
            $query->whereHas('venue', fn($q) => $q->where('owner_id', Auth::id()));
        }

        return $query->with(['programSlots' => function($q) {
            $q->with('activityType')->orderBy('start_time');
        }, 'venue'])->get();
    }

    public function getEventDays()
    {
        $start = Setting::get('event_start_date');
        $end = Setting::get('event_end_date');

        if (!$start || !$end) return [];

        $days = CarbonPeriod::create($start, $end)->toArray();
        foreach ($days as $day) {
            $day->setLocale('cs');
        }
        return $days;
    }

    protected function getViewData(): array
    {
        $stages = $this->getStages();
        $days = $this->getEventDays();

        $processedStages = $stages->map(function ($stage) use ($days) {
            $lanesByDay = [];
            foreach ($days as $day) {
                $slotsForDay = $stage->programSlots
                    ->filter(fn($slot) => $slot->start_time->isSameDay($day))
                    ->sortBy('start_time');

                $lanes = [];
                foreach ($slotsForDay as $slot) {
                    $assigned = false;
                    foreach ($lanes as &$lane) {
                        $lastSlot = end($lane);
                        // If current slot starts after last slot in lane ends (or at same time)
                        if ($slot->start_time >= $lastSlot->end_time) {
                            $lane[] = $slot;
                            $assigned = true;
                            break;
                        }
                    }
                    if (!$assigned) {
                        $lanes[] = [$slot];
                    }
                }
                $lanesByDay[$day->format('Y-m-d')] = $lanes;
            }

            return (object) [
                'id' => $stage->id,
                'name' => $stage->name,
                'venue' => $stage->venue,
                'lanesByDay' => $lanesByDay,
            ];
        });

        return [
            'stages' => $processedStages,
            'days' => $days,
        ];
    }
}
