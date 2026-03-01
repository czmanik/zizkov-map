<?php

namespace App\Livewire;

use App\Models\Venue;
use Livewire\Component;

class VenueDetail extends Component
{
    public Venue $record;
    public ?string $filterActivityType = null;

    public function mount($venue)
    {
        // Explicitly find by ID to avoid binding issues if any, then load relationships
        $this->record = Venue::with(['stages.programSlots' => function($q) {
            $q->where('status', 'approved')->with('activityType')->orderBy('start_time');
        }, 'venueType'])->findOrFail($venue);
    }

    public function setFilter($type)
    {
        $this->filterActivityType = $type;
    }

    public function render()
    {
        $activityTypes = $this->record->stages->flatMap->programSlots->pluck('activityType.name')->unique()->sort();

        return view('livewire.venue-detail', [
            'availableActivityTypes' => $activityTypes
        ]);
    }
}
