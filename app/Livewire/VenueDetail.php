<?php

namespace App\Livewire;

use App\Models\Venue;
use Livewire\Component;

class VenueDetail extends Component
{
    public Venue $venue;

    public function mount(Venue $venue)
    {
        $this->venue = $venue->load(['stages.programSlots' => function($q) {
            $q->where('status', 'approved')->orderBy('start_time');
        }, 'venueType']);
    }

    public function render()
    {
        return view('livewire.venue-detail', [
            'record' => $this->venue
        ]);
    }
}
