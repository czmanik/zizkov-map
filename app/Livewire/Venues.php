<?php

namespace App\Livewire;

use App\Models\Venue;
use Livewire\Component;

class Venues extends Component
{
    public function render()
    {
        return view('livewire.venues', [
            'venues' => Venue::where('status', 'public')->with('venueType')->get()
        ]);
    }
}
