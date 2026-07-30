<?php

declare(strict_types=1);

namespace App\Forms\Profile;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ProfileForm extends Form
{
    public ?User $user = null;

    public string $first_name = '';

    public string $last_name = '';

    public string $email = '';

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->user?->id)],
        ];
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
    }

    public function update(User $user): void
    {
        $this->user = $user;

        $user->update($this->validate());
    }
}
