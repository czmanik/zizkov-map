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
            $q->orderBy('start_time');
        }])->get();
    }

    public function getEventDays()
    {
        $start = Setting::get('event_start_date');
        $end = Setting::get('event_end_date');

        if (!$start || !$end) return [];

        return CarbonPeriod::create($start, $end)->toArray();
    }

    protected function getViewData(): array
    {
        return [
            'stages' => $this->getStages(),
            'days' => $this->getEventDays(),
        ];
    }
}
