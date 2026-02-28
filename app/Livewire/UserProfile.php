<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Component
{
    public function toggleFavorite($slotId)
    {
        Auth::user()->favoriteSlots()->detach($slotId);
    }

    public function render()
    {
        return view('livewire.user-profile', [
            'favorites' => Auth::user()->favoriteSlots()->with(['stage.venue', 'activityType'])->orderBy('start_time')->get()
        ]);
    }
}
