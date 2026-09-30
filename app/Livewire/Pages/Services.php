<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class Services extends Component
{
    public function render()
    {
        return view('livewire.pages.services', [
            'title' => 'Student Services & Verification',
        ]);
    }
}