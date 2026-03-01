<?php

namespace App\Livewire;

use App\Models\Venue;
use App\Models\ActivityType;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Homepage extends Component
{
    public function render()
    {
        // Cache GeoJSON data for 10 minutes, invalidate on Venue change (logic handled here for simplicity)
        $venuesData = Cache::remember('venues_geojson_v2', 600, function() {
            return Venue::where('status', 'public')->with(['venueType', 'stages.programSlots' => function($q) {
                $q->where('status', 'approved')->where('end_time', '>', now());
            }, 'stages.programSlots.activityType'])->get()->map(fn($v) => [
                'id' => $v->id,
                'name' => $v->name,
                'lat' => $v->lat,
                'lng' => $v->lng,
                'url' => "/misto/{$v->id}",
                'address' => "{$v->address_street} {$v->address_number}",
                'type' => $v->venueType->name,
                'activity_types' => $v->stages->flatMap->programSlots->pluck('activityType.name')->unique()->values()->all()
            ]);
        });

        return view('livewire.homepage', [
            'venuesJson' => $venuesData->toJson(),
            'activityTypes' => ActivityType::orderBy('name')->get()
        ]);
    }
}
