<?php

declare(strict_types=1);

namespace App\Forms\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateForm extends Form
{
    public ?User $targetUser = null;

    public string $first_name = '';

    public string $last_name = '';

    public string $email = '';

    public string $password = '';

    public string $role = '';

    public bool $is_active = true;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $allowedRoles = array_column(auth()->user()->assignableRoles(), 'value');

        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->targetUser?->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in($allowedRoles)],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function setUser(User $user): void
    {
        $this->targetUser = $user;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->role = $user->role->value;
        $this->is_active = (bool) ($user->is_active ?? true);
        $this->password = '';
    }

    public function update(): void
    {
        $validated = $this->validate();

        if ($this->targetUser->id === auth()->id()) {
            $validated['is_active'] = true;
            $validated['role'] = auth()->user()->role->value;
        }

        if (filled($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $this->targetUser->update($validated);

        $this->reset();
    }
}
