<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class ConfigHelper
{
    /**
     * Get a configuration value
     */
    public static function get($key, $default = null)
    {
        $config = Cache::remember('site_config', 3600, function () {
            if (Storage::exists('config/site_config.json')) {
                return json_decode(Storage::get('config/site_config.json'), true);
            }
            return [];
        });
        
        return $config[$key] ?? $default;
    }
    
    /**
     * Check if a feature is enabled
     */
    public static function isEnabled($feature)
    {
        return self::get($feature, false) === true;
    }
    
    /**
     * Get all configuration values
     */
    public static function all()
    {
        return Cache::remember('site_config', 3600, function () {
            if (Storage::exists('config/site_config.json')) {
                return json_decode(Storage::get('config/site_config.json'), true);
            }
            return [];
        });
    }
}

// Global helper function
if (!function_exists('site_config')) {
    function site_config($key = null, $default = null)
    {
        if ($key === null) {
            return \App\Helpers\ConfigHelper::all();
        }
        
        return \App\Helpers\ConfigHelper::get($key, $default);
    }
}

if (!function_exists('feature_enabled')) {
    function feature_enabled($feature)
    {
        return \App\Helpers\ConfigHelper::isEnabled($feature);
    }
}