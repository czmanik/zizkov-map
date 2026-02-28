<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cookie;

class CookieConsent extends Component
{
    public $showBanner = false;
    public $analytics = true;
    public $marketing = false;

    public function mount()
    {
        if (!Cookie::has('cookie_consent')) {
            $this->showBanner = true;
        }
    }

    public function acceptAll()
    {
        $this->saveConsent([
            'essential' => true,
            'analytics' => true,
            'marketing' => true,
        ]);
    }

    public function acceptSelected()
    {
        $this->saveConsent([
            'essential' => true,
            'analytics' => $this->analytics,
            'marketing' => $this->marketing,
        ]);
    }

    protected function saveConsent($consent)
    {
        Cookie::queue('cookie_consent', json_encode($consent), 60 * 24 * 365);
        $this->showBanner = false;
    }

    public function render()
    {
        return view('livewire.cookie-consent');
    }
}
