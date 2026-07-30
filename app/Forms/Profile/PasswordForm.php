<?php

declare(strict_types=1);

namespace App\Forms\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Form;

class PasswordForm extends Form
{
    public string $current_password = '';

    public string $new_password = '';

    public string $new_password_confirmation = '';

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function update(User $user): void
    {
        $this->validate();

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset();
    }
}
