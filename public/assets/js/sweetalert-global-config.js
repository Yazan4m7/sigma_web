/**
 * ============================================================================
 * GLOBAL SWEETALERT2 CONFIGURATION
 * Prevents accidental confirmation when clicking outside or pressing ESC
 * ============================================================================
 */

(function() {
    'use strict';

    // Store the original Swal.fire method
    const originalSwalFire = Swal.fire;

    // Override Swal.fire to inject safe defaults
    Swal.fire = function(...args) {
        // Handle different call signatures
        let config;

        if (args.length === 1 && typeof args[0] === 'object') {
            // Swal.fire({...})
            config = args[0];
        } else if (args.length >= 2) {
            // Swal.fire('title', 'text', 'icon')
            config = {
                title: args[0],
                text: args[1],
                icon: args[2] || undefined
            };
        } else {
            config = {};
        }

        // Apply safe defaults - PREVENT closing on backdrop click or ESC
        const safeConfig = {
            allowOutsideClick: false,  // Don't close when clicking outside
            allowEscapeKey: false,      // Don't close when pressing ESC
            allowEnterKey: true,        // Allow Enter key (for confirmation)
            ...config,  // User's config can still override if needed
        };

        // If it's a confirmation dialog (has showCancelButton), ensure backdrop doesn't confirm
        if (safeConfig.showCancelButton) {
            safeConfig.allowOutsideClick = false;
            safeConfig.allowEscapeKey = false;
        }

        // Call the original method with safe config
        return originalSwalFire.call(this, safeConfig);
    };

    // Also handle the mixin method
    if (Swal.mixin) {
        const originalMixin = Swal.mixin;
        Swal.mixin = function(mixinConfig) {
            const safeMixinConfig = {
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: true,
                ...mixinConfig
            };
            return originalMixin.call(this, safeMixinConfig);
        };
    }

    console.log('✓ SweetAlert2 global safety configuration loaded');
})();
