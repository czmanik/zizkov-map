<?php

namespace App\Livewire;

use App\Models\Venue;
use Livewire\Component;

class VenueDetail extends Component
{
    public Venue $record;

<<<<<<< HEAD
    public function mount($venue)
    {
        // Explicitly find by ID to avoid binding issues if any, then load relationships
        $this->record = Venue::with(['stages.programSlots' => function($q) {
            $q->where('status', 'approved')->orderBy('start_time');
        }, 'venueType'])->findOrFail($venue);
=======
    public function mount($record)
    {
        $this->record = Venue::with(['stages.programSlots' => function($q) {
            $q->where('status', 'approved')->orderBy('start_time');
        }, 'venueType'])->findOrFail($record);
>>>>>>> origin/main
    }

    public function render()
    {
        return view('livewire.venue-detail');
    }
}
