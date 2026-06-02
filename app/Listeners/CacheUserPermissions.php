<?php

namespace App\Listeners;

use App\Support\AuthenticatedUserCache;
use App\Support\UserPermissionsCache;
use Illuminate\Auth\Events\Login;

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
        UserPermissionsCache::prime($user->id);
        AuthenticatedUserCache::prime($user->id);
    }
}
