<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\PostMediaController;
use App\Livewire\Admin\Posts\Editor as PostEditor;
use App\Livewire\Admin\Posts\Index as PostsIndex;
use App\Livewire\AuditLogs\Index as AuditLogsIndex;
use App\Livewire\Auth\LoginPage;
use App\Livewire\CwtsStudents\Index as CwtsStudentsIndex;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Home;
use App\Livewire\LtsStudents\Index as LtsStudentsIndex;
use App\Livewire\News\Show as NewsShow;
use App\Livewire\Profile\Index as ProfileIndex;
use App\Livewire\RotcStudents\Index as RotcStudentsIndex;
use App\Livewire\Users\Index as UsersIndex;
use App\Services\AuthService;
use App\Livewire\News\Index as NewsIndex;
use App\Livewire\Pages\About as AboutPage;
use App\Livewire\Pages\Services as ServicesPage;
use App\Livewire\Pages\Terms as TermsPage;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/news', NewsIndex::class)->name('news.index');
Route::get('/news/{slug}', NewsShow::class)->name('news.show');
Route::get('/about', AboutPage::class)->name('about');
Route::get('/services', ServicesPage::class)->name('services');
Route::get('/terms', TermsPage::class)->name('terms');

Route::middleware('guest')->group(function () {
    Route::get('/login', LoginPage::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    Route::get('/cwts-students', CwtsStudentsIndex::class)->name('cwts-students.index');
    Route::get('/rotc-students', RotcStudentsIndex::class)->name('rotc-students.index');
    Route::get('/lts-students', LtsStudentsIndex::class)->name('lts-students.index');
    Route::get('/admin/posts', PostsIndex::class)->name('admin.posts.index');
    Route::get('/admin/posts/create', PostEditor::class)->name('admin.posts.create');
    Route::get('/admin/posts/{post}/edit', PostEditor::class)->name('admin.posts.edit');
    Route::get('/profile', ProfileIndex::class)->name('profile.index');
    Route::get('/users', UsersIndex::class)->name('users.index');
    Route::get('/audit-logs', AuditLogsIndex::class)->name('audit-logs.index');
    Route::post('/admin/posts/media/upload', [PostMediaController::class, 'upload'])
        ->name('admin.posts.media.upload')
    ;

    Route::post('/logout', function (AuthService $authService) {
        $authService->logout();

        return redirect()->route('login');
    })->name('logout');
});
