<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class AuthenticatedUserCache
{
    public static function key(int $userId): string
    {
        return 'auth_user_snapshot_' . $userId;
    }

    public static function get(int $userId): ?User
    {
        $snapshot = Cache::get(static::key($userId));

        if (!is_array($snapshot) || $snapshot === []) {
            return static::prime($userId);
        }

        return static::fromAttributes($snapshot);
    }

    public static function prime(int $userId): ?User
    {
        $user = User::query()->find($userId);

        if (!$user) {
            Cache::forget(static::key($userId));

            return null;
        }

        Cache::forever(static::key($userId), $user->getAttributes());

        return $user;
    }

    public static function forget(int $userId): void
    {
        Cache::forget(static::key($userId));
    }

    protected static function fromAttributes(array $attributes): User
    {
        $user = new User();
        $user->setRawAttributes($attributes, true);
        $user->exists = true;

        return $user;
    }
}
