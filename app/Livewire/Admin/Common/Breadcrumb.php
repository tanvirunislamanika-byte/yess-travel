<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Breadcrumb extends Component
{
    public $title;
    public $title2;
    public $term;
    public function render()
    {
        return view('livewire.components.breadcrumb');
    }
}
