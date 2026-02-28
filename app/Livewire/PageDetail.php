<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;

class PageDetail extends Component
{
    public Page $page;

    public function mount($slug)
    {
        $this->page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.page-detail');
    }
}
