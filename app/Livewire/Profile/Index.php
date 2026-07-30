<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use App\Livewire\Forms\PasswordForm;
use App\Livewire\Forms\ProfileForm;
use App\Traits\WithToast;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithToast;

    public ProfileForm $profileForm;

    public PasswordForm $passwordForm;

    public function mount(): void
    {
        $user = auth()->user();
        $this->profileForm->setUser($user);
    }

    public function updateProfile(): void
    {
        $user = auth()->user();

        $this->profileForm->update($user);

        $this->dispatch('profile-updated', name: $user->name);
        $this->toast('success', 'Profile information updated successfully.');
    }

    public function updatePassword(): void
    {
        $user = auth()->user();

        $this->passwordForm->update($user);

        $this->toast('success', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.profile.index');
    }
}
