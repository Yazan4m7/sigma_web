<?php

namespace App\Listeners;

use App\Services\AuditLogger;
use Illuminate\Auth\Events\Login;

class LogUserLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        AuditLogger::log(
            'user_logged_in',
            $event->user,
            [
                'guard' => $event->guard,
                'remember' => (bool) $event->remember,
            ],
            sprintf('User %s logged in', $event->user->name ?? $event->user->username ?? $event->user->id)
        );
    }
}
