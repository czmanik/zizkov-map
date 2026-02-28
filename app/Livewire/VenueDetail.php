<?php

namespace App\Livewire;

use App\Models\Venue;
use Livewire\Component;

class VenueDetail extends Component
{
    public Venue $record;

    public function mount($record)
    {
        $this->record = Venue::with(['stages.programSlots' => function($q) {
            $q->where('status', 'approved')->orderBy('start_time');
        }, 'venueType'])->findOrFail($record);
    }

    public function render()
    {
        return view('livewire.venue-detail');
    }
}
