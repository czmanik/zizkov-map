<?php

namespace App\Livewire;

use App\Models\ProgramSlot;
use App\Models\Venue;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProgramDetail extends Component
{
    public ProgramSlot $programSlot;
    public $favoriteSlotIds = [];

    public function mount(Venue $venue, ProgramSlot $programSlot)
    {
        $this->programSlot = $programSlot;
        $this->refreshFavorites();
    }

    public function refreshFavorites()
    {
        if (Auth::check()) {
            $this->favoriteSlotIds = Auth::user()->favoriteSlots()->pluck('program_slots.id')->toArray();
        } else {
            $this->favoriteSlotIds = [];
        }
    }

    public function toggleFavorite($slotId)
    {
        if (!Auth::check()) {
            return $this->redirect('/login');
        }

        Auth::user()->favoriteSlots()->toggle($slotId);
        $this->refreshFavorites();
    }

    public function render()
    {
        $venue = $this->programSlot->stage->venue;

        $nextOnVenue = ProgramSlot::where('status', 'approved')
            ->whereHas('stage', function ($query) use ($venue) {
                $query->where('venue_id', $venue->id);
            })
            ->where('start_time', '>=', $this->programSlot->end_time)
            ->where('id', '!=', $this->programSlot->id)
            ->orderBy('start_time')
            ->with(['stage', 'activityType'])
            ->take(3)
            ->get();

        return view('livewire.program-detail', [
            'nextOnVenue' => $nextOnVenue
        ]);
    }
}
