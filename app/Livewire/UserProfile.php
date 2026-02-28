<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserProfile extends Component
{
    public $nickname;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $this->nickname = Auth::user()->nickname;
    }

    public function updateNickname()
    {
        $this->validate([
            'nickname' => ['required', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        $user->nickname = $this->nickname;
        $user->name = $this->nickname; // Keep name in sync for now
        $user->save();

        session()->flash('message', 'Přezdívka byla úspěšně změněna.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(5)],
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('message', 'Heslo bylo úspěšně změněno.');
    }

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
