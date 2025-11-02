<?php

namespace App\Listeners;

use App\UserPermission;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Cache;

class CacheUserPermissions
{
    /**
     * Handle the event.
     * Cache user permissions when they log in.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;

        // Fetch user permissions from database
        $permissions = UserPermission::where('user_id', $user->id)->get();

        // Cache permissions forever (until explicitly cleared)
        Cache::forever('user' . $user->id, $permissions);
    }
}
