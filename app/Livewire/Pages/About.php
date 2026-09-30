<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class About extends Component
{
    public function render()
    {
        return view('livewire.pages.about', [
            'title' => 'About the Program',
        ]);
    }
}