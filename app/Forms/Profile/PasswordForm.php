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

    public function update(User $user): void
    {
        $this->validate([
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($user) {
                if (! Hash::check($value, $user->password)) {
                    $fail('The provided password does not match your current password.');
                }
            }],
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset();
    }
}
