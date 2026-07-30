<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canView();
    }

    public function view(User $user, ?Student $student = null): bool
    {
        return $user->canView();
    }

    public function create(User $user): bool
    {
        return $user->canCreate();
    }

    public function update(User $user, ?Student $student = null): bool
    {
        return $user->canUpdate();
    }

    public function delete(User $user, ?Student $student = null): bool
    {
        return $user->canDelete();
    }
}
