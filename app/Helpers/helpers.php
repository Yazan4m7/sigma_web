<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

if (!function_exists('safe_permissions')) {
    function safe_permissions() {
        $user = Auth::user();

        // If user is not logged in, redirect immediately
        if (!$user) {
            redirect()->route('login')->send();
            exit;
        }

        // Safely fetch from cache
        $permissions = Cache::get('user'.$user->id);

        // If permissions not found, force logout and redirect
        if (!$permissions) {
            Auth::logout();
            redirect()->route('login')->send();
            exit;
        }

        return $permissions;
    }
}
