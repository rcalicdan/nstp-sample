<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ProfileForm extends Form
{
    public string $first_name = '';

    public string $last_name = '';

    public string $email = '';

    public function setUser(User $user): void
    {
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
    }

    public function update(User $user): void
    {
        $validated = $this->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);
    }
}
