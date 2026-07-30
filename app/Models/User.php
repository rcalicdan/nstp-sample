<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === Role::SUPERADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->role === Role::STAFF;
    }

    public function canView(): bool
    {
        return true;
    }

    public function canCreate(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    public function canUpdate(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    public function canDelete(): bool
    {
        return $this->isSuperAdmin();
    }
}
