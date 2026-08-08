<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    public function update(User $user, User $targetUser): bool
    {
        if ($user->id === $targetUser->id) {
            return true;
        }

        if ($user->isSuperAdmin()) {
            return ! $targetUser->isSuperAdmin();
        }

        if ($user->isAdmin()) {
            return $targetUser->isStaff();
        }

        return false;
    }

    public function toggleActive(User $user, User $targetUser): bool
    {
        if ($user->id === $targetUser->id) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return ! $targetUser->isSuperAdmin();
        }

        if ($user->isAdmin()) {
            return $targetUser->isStaff();
        }

        return false;
    }

    public function delete(User $user, User $targetUser): bool
    {
        if ($user->id === $targetUser->id) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return ! $targetUser->isSuperAdmin();
        }

        if ($user->isAdmin()) {
            return $targetUser->isStaff();
        }

        return false;
    }
}