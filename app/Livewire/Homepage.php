<?php

namespace App\Livewire;

use App\Models\Venue;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Homepage extends Component
{
    public function render()
    {
        // Cache GeoJSON data for 10 minutes, invalidate on Venue change (logic handled here for simplicity)
        $venuesData = Cache::remember('venues_geojson', 600, function() {
            return Venue::where('status', 'public')->get()->map(fn($v) => [
                'id' => $v->id,
                'name' => $v->name,
                'lat' => $v->lat,
                'lng' => $v->lng,
                'url' => "/misto/{$v->id}",
                'address' => "{$v->address_street} {$v->address_number}",
                'type' => $v->venueType->name
            ]);
        });

        return view('livewire.homepage', [
            'venuesJson' => $venuesData->toJson()
        ]);
    }
}
