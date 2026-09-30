<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\PostMediaController;
use App\Livewire\Admin\Posts\Editor as PostEditor;
use App\Livewire\Admin\Posts\Index as PostsIndex;
use App\Livewire\AuditLogs\Index as AuditLogsIndex;
use App\Livewire\Auth\LoginPage;
use App\Livewire\CwtsStudents\Index as CwtsStudentsIndex;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\LtsStudents\Index as LtsStudentsIndex;
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
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    Route::get('/cwts-students', CwtsStudentsIndex::class)->name('cwts-students.index');
    Route::get('/rotc-students', RotcStudentsIndex::class)->name('rotc-students.index');
    Route::get('/lts-students', LtsStudentsIndex::class)->name('lts-students.index');
    Route::get('/profile', ProfileIndex::class)->name('profile.index');
    Route::get('/users', UsersIndex::class)->name('users.index');
    Route::get('/audit-logs', AuditLogsIndex::class)->name('audit-logs.index');

    Route::post('/logout', function (AuthService $authService) {
        $authService->logout();

        return redirect()->route('login');
    })->name('logout');

    Route::post('/admin/posts/media/upload', [PostMediaController::class, 'upload'])
        ->name('admin.posts.media.upload')
    ;

    Route::get('/admin/posts', PostsIndex::class)->name('admin.posts.index');
    Route::get('/admin/posts/create', PostEditor::class)->name('admin.posts.create');
    Route::get('/admin/posts/{post}/edit', PostEditor::class)->name('admin.posts.edit');
});
