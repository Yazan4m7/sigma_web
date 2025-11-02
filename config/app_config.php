<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Global Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains global configuration settings for the application.
    */

    // Device Images Configuration
    'device_images' => [
        'width' => '100%',         // Width of device images
        'max_width' => '150px',    // Maximum width constraint
        'height' => 'auto',        // Height (auto to maintain aspect ratio)
        'padding' => '10px',       // Padding around images
        'border_radius' => '8px',  // Rounded corners
        'hover_effect' => false,   // Disable shadow/box effect on hover
        'background' => 'transparent', // Transparent background
        'container_gap' => '15px', // Gap between device items
        'responsive_sizes' => [
            'tablet' => '120px',   // Max width on tablet devices
            'mobile' => '100px',   // Max width on mobile devices
        ],
    ],
    
    // JavaScript Configuration
    'js' => [
        'use_jquery' => true,      // Whether to use jQuery or vanilla JS
        'debug_mode' => false,     // Enable console logging for debugging
        'reset_dialogs' => true,   // Reset dialogs to original state when closed
    ],
    
    // Device Container Configuration
    'device_container' => [
        'gap' => '15px',           // Gap between device items
        'justify' => 'center',     // Flex justify-content value
        'margin_bottom' => '15px', // Bottom margin for device items
    ],
    
    // Operations Dashboard Settings
    'operations' => [
        'enable_stages' => [
            'design' => true,
            'milling' => true,
            '3dprinting' => true,
            'sintering' => true,
            'pressing' => true,
            'finishing' => true,
            'qC' => true,
            'delivery' => true,
        ],
    ],
];
