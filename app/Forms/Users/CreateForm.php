<?php

declare(strict_types=1);

namespace App\Forms\Users;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateForm extends Form
{
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';
    public string $role = Role::STAFF->value;
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
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in($allowedRoles)],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function store(): void
    {
        $validated = $this->validate();

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        $this->reset();
    }
}