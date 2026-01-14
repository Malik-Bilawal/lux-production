<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    public function created(User $user): void
    {
        Cache::forget(User::CACHE_TOTAL_USERS);

    }

    public function updated(User $user): void
    {
        if ($user->isDirty('status')) {
            Cache::forget(User::CACHE_BLOCKED_USERS);
            

        }
    }

    public function deleted(User $user): void
    {
        Cache::forget(User::CACHE_TOTAL_USERS);
        Cache::forget(User::CACHE_ACTIVE_USERS);
        Cache::forget(User::CACHE_BLOCKED_USERS);
    }
}