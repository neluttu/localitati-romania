<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\User;

class SitePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Site $site): bool
    {
        return $user->id === $site->user_id || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Site $site): bool
    {
        return $user->id === $site->user_id || $user->isAdmin();
    }

    public function delete(User $user, Site $site): bool
    {
        return $user->id === $site->user_id || $user->isAdmin();
    }

    public function restore(User $user, Site $site): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Site $site): bool
    {
        return $user->isAdmin();
    }
}
