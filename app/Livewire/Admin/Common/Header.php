<?php

namespace App\Livewire\Admin\Common;

use Livewire\Component;

class Header extends Component
{
    public $title;
    public $content;
    public $icon;
    public $term;
    public $slug;
    public $button;
    public function render()
    {
        return view('livewire.admin.common.header');
    }
}
