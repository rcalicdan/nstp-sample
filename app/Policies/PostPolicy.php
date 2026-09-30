<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isEditor() || $user->isStaff();
    }

    public function view(User $user, Post $post): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isEditor() || $user->isStaff();
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isEditor();
    }

    public function update(User $user, Post $post): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        return $user->isEditor() && $user->id === $post->user_id;
    }

    public function delete(User $user, Post $post): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return $user->isEditor() && $user->id === $post->user_id;
    }

    public function publish(User $user, Post $post): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isEditor();
    }
}
