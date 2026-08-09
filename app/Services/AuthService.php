<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(string $email, string $password): bool
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            session()->regenerate();

            if(!Auth::user()->is_active) {
                throw new \Exception('User account is not active.');
            }

            return true;
        }

      

        return false;
    }

    public function logout(): void
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();
    }
}
