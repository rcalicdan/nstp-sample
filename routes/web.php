<?php

use App\Livewire\Auth\LoginPage;
use App\Livewire\CwtsStudents\Index as CwtsStudentsIndex;
use App\Services\AuthService;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', LoginPage::class)->name('login');
    Route::get('/login', LoginPage::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('cwts-students.index');
    })->name('dashboard');

    Route::get('/cwts-students', CwtsStudentsIndex::class)->name('cwts-students.index');

    Route::post('/logout', function (AuthService $authService) {
        $authService->logout();
        return redirect()->route('login');
    })->name('logout');
});