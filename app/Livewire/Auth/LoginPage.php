<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Services\AuthService;
use App\Traits\WithToast;
use Exception;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class LoginPage extends Component
{
    use WithToast;

    public string $email = '';

    public string $password = '';

    public function login(AuthService $authService)
    {
        /** @var array<string, string> */
        $validationPayload = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $this->validate($validationPayload);


        try {
            if ($authService->login($this->email, $this->password)) {
                session()->flash('success', 'You are successfully logged in.');

                $this->redirectIntended(route('cwts-students.index'));

                return;
            }
        } catch (Exception $e) {
            $this->toast('error', $e->getMessage());
            return;
        }


        $this->toast('error', 'Invalid email address or password.');
        $this->reset('password');
    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
