<?php

declare(strict_types=1);

use App\Livewire\AuditLogs\Index as AuditLogsIndex;
use App\Livewire\Auth\LoginPage;
use App\Livewire\CwtsStudents\Index as CwtsStudentsIndex;
use App\Livewire\Profile\Index as ProfileIndex;
use App\Livewire\RotcStudents\Index as RotcStudentsIndex;
use App\Livewire\Users\Index as UsersIndex;
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
    Route::get('/rotc-students', RotcStudentsIndex::class)->name('rotc-students.index');
    Route::get('/profile', ProfileIndex::class)->name('profile.index');
    Route::get('/users', UsersIndex::class)->name('users.index');
    Route::get('/audit-logs', AuditLogsIndex::class)->name('audit-logs.index');

    Route::post('/logout', function (AuthService $authService) {
        $authService->logout();

        return redirect()->route('login');
    })->name('logout');
});
