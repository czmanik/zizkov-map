<?php

namespace App\Livewire;

use App\Models\ActivityType;
use App\Models\ProgramSlot;
use App\Models\Venue;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Program extends Component
{
    #[Url]
    public $selectedActivityTypes = [];

    #[Url]
    public $selectedVenue = null;

    #[Url]
    public $selectedAccessibility = null;

    #[Url]
    public $search = '';

    public function toggleFavorite($slotId)
    {
        if (!Auth::check()) {
            return $this->redirect('/login');
        }

        $user = Auth::user();
        if ($user->favoriteSlots()->where('program_slot_id', $slotId)->exists()) {
            $user->favoriteSlots()->detach($slotId);
        } else {
            $user->favoriteSlots()->attach($slotId);
        }
    }

    public function render()
    {
        $query = ProgramSlot::where('status', 'approved')
            ->with(['stage.venue', 'activityType'])
            ->orderBy('start_time');

        if (!empty($this->selectedActivityTypes)) {
            $query->whereIn('activity_type_id', $this->selectedActivityTypes);
        }

        if ($this->selectedVenue) {
            $query->whereHas('stage', fn($q) => $q->where('venue_id', $this->selectedVenue));
        }

        if ($this->selectedAccessibility) {
            $query->where('accessibility', $this->selectedAccessibility);
        }

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $slots = $query->get()->groupBy(fn($slot) => $slot->start_time->format('Y-m-d'));

        return view('livewire.program', [
            'days' => $slots,
            'activityTypes' => ActivityType::all(),
            'venues' => Venue::where('status', 'public')->orderBy('name')->get(),
            'eventStartDate' => Setting::get('event_start_date'),
            'eventEndDate' => Setting::get('event_end_date'),
        ]);
    }
}
