<?php

namespace App\Listeners;

use App\Services\AuditLogger;
use Illuminate\Auth\Events\Logout;

class LogUserLogout
{
    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        AuditLogger::log(
            'user_logged_out',
            $event->user,
            [
                'guard' => $event->guard,
            ],
            sprintf('User %s logged out', $event->user->name ?? $event->user->username ?? $event->user->id)
        );
    }
}
