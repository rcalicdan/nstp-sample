<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use App\Traits\WithToast;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithToast;

    public string $first_name = '';

    public string $last_name = '';

    public string $email = '';

    public string $current_password = '';

    public string $new_password = '';

    public string $new_password_confirmation = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->email = $user->email;

        $parts = explode(' ', $user->name, 2);
        $this->first_name = $parts[0] ?? '';
        $this->last_name = $parts[1] ?? '';
    }

    public function updateProfile(): void
    {
        $user = auth()->user();

        $validated = $this->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $newName = trim("{$this->first_name} {$this->last_name}");

        $user->update([
            'name' => $newName,
            'email' => $validated['email'],
        ]);

        $this->dispatch('profile-updated', name: $newName);
        $this->toast('success', 'Profile information updated successfully.');
    }

    public function updatePassword(): void
    {
        $user = auth()->user();

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

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->toast('success', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.profile.index');
    }
}
