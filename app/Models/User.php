<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'is_active',
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
            'is_active' => 'boolean',
        ];
    }

    public function getNameAttribute(): string
    {
        return (string) Str::of("{$this->first_name} {$this->last_name}")->squish();
    }

    public function getInitialsAttribute(): string
    {
        $first = Str::of($this->first_name)->take(1)->upper();
        $last = Str::of($this->last_name)->take(1)->upper();

        return "{$first}{$last}";
    }

    /**
     * @return list<Role>
     */
    public function assignableRoles(): array
    {
        if ($this->isSuperAdmin()) {
            return Role::cases();
        }

        if ($this->isAdmin()) {
            return [Role::STAFF];
        }

        return [];
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
