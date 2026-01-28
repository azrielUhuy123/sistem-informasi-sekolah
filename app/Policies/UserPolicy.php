<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function update(User $actor, User $target)
    {
        if ($target->role === 'superadmin') return false;
        if ($actor->id === $target->id) return false;

        return $actor->role === 'admin';
    }

    public function delete(User $actor, User $target)
    {
        if ($target->role === 'superadmin') return false;
        if ($actor->id === $target->id) return false;

        return $actor->role === 'admin';
    }
}
