<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class Terms extends Component
{
    public function render()
    {
        return view('livewire.pages.terms', [
            'title' => 'Terms of Service & Data Privacy',
        ]);
    }
}