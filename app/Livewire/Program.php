<?php

namespace App\Livewire;

use App\Models\ActivityType;
use App\Models\ProgramSlot;
use App\Models\Venue;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Program extends Component
{
    public $selectedActivityType = null;
    public $selectedAccessibility = null;
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
        $cacheKey = 'program_' . md5($this->selectedActivityType . $this->selectedAccessibility . $this->search);

        $slots = Cache::remember($cacheKey, 60, function() {
            $query = ProgramSlot::where('status', 'approved')
                ->with(['stage.venue', 'activityType'])
                ->orderBy('start_time');

            if ($this->selectedActivityType) {
                $query->where('activity_type_id', $this->selectedActivityType);
            }

            if ($this->selectedAccessibility) {
                $query->where('accessibility', $this->selectedAccessibility);
            }

            if ($this->search) {
                $query->where('name', 'like', '%' . $this->search . '%');
            }

            return $query->get()->groupBy(fn($slot) => $slot->start_time->format('Y-m-d'));
        });

        return view('livewire.program', [
            'days' => $slots,
            'activityTypes' => ActivityType::all()
        ]);
    }
}
