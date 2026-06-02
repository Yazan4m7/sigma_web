<?php

use Illuminate\Support\Facades\Auth;
use App\Support\UserPermissionsCache;

if (!function_exists('safe_permissions')) {
    function safe_permissions() {
        $user = Auth::user();

        // If user is not logged in, redirect immediately
        if (!$user) {
            redirect()->route('login')->send();
            exit;
        }

        $request = request();
        $permissions = $request ? $request->attributes->get('user_permissions') : null;

        // Safely fetch from cache
        if (!$permissions) {
            $permissions = UserPermissionsCache::get($user->id);
            if ($request) {
                $request->attributes->set('user_permissions', $permissions);
            }
        }

        // If permissions not found, force logout and redirect
        if (!$permissions) {
            Auth::logout();
            redirect()->route('login')->send();
            exit;
        }

        return $permissions;
    }
}

if (!function_exists('ui_view_date_format')) {
    function ui_view_date_format(): string
    {
        return (string) config('ui.date_format_view', 'j-M');
    }
}

if (!function_exists('ui_dialog_date_format')) {
    function ui_dialog_date_format(): string
    {
        return (string) config('ui.date_format_dialog', 'j-M');
    }
}

if (!function_exists('ui_format_date')) {
    function ui_format_date($value, string $format): string
    {
        if ($value === null) {
            return '-';
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format($format);
        }

        $raw = trim((string) $value);
        if ($raw === '') {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse($raw)->format($format);
        } catch (\Throwable $e) {
            try {
                return \Carbon\Carbon::parse(str_replace('T', ' ', $raw))->format($format);
            } catch (\Throwable $e2) {
                return $raw;
            }
        }
    }
}

if (!function_exists('ui_view_date')) {
    function ui_view_date($value): string
    {
        return ui_format_date($value, ui_view_date_format());
    }
}

if (!function_exists('ui_dialog_date')) {
    function ui_dialog_date($value): string
    {
        return ui_format_date($value, ui_dialog_date_format());
    }
}
