<?php

namespace App\Support;

use App\UserPermission;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UserPermissionsCache
{
    public static function key(int $userId): string
    {
        return 'user' . $userId;
    }

    public static function get(int $userId): Collection
    {
        $cacheKey = static::key($userId);
        $cached = Cache::get($cacheKey);

        if ($cached instanceof Collection || is_array($cached)) {
            $normalized = static::normalize($cached);

            if (static::shouldRewrite($cached, $normalized)) {
                Cache::forever($cacheKey, $normalized);
            }

            return $normalized;
        }

        return static::prime($userId);
    }

    public static function prime(int $userId): Collection
    {
        $permissions = static::fromDatabase($userId);

        Cache::forever(static::key($userId), $permissions);

        return $permissions;
    }

    public static function normalize($permissions): Collection
    {
        return collect($permissions)
            ->map(function ($permission) {
                $permissionId = data_get($permission, 'permission_id');

                if ($permissionId === null) {
                    return null;
                }

                return [
                    'user_id' => (int) data_get($permission, 'user_id', 0),
                    'permission_id' => (int) $permissionId,
                ];
            })
            ->filter()
            ->values();
    }

    protected static function fromDatabase(int $userId): Collection
    {
        return UserPermission::query()
            ->where('user_id', $userId)
            ->get(['user_id', 'permission_id'])
            ->map(function (UserPermission $permission) {
                return [
                    'user_id' => (int) $permission->user_id,
                    'permission_id' => (int) $permission->permission_id,
                ];
            })
            ->values();
    }

    protected static function shouldRewrite($cached, Collection $normalized): bool
    {
        if (!($cached instanceof Collection)) {
            return true;
        }

        $first = $cached->first();

        if ($first === null) {
            return false;
        }

        if (is_array($first)) {
            return false;
        }

        return true;
    }
}
