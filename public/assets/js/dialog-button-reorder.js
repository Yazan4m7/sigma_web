/**
 * SIGMA Dialog Button Reordering System - DISABLED
 * This system has been disabled to revert to original Bootstrap grid layout
 * as requested to match the original button positioning and order.
 */

(function() {
    'use strict';

    // COMPLETELY DISABLED - Original Bootstrap layout restored
    console.log('Dialog button reordering system is disabled - using original Bootstrap layout');

    // No-op class to prevent errors if something tries to use it
    class DialogButtonReorder {
        constructor() {
            console.log('DialogButtonReorder: Disabled - using original layout');
        }
        
        init() { /* disabled */ }
        processAllDialogs() { /* disabled */ }
        processDialog() { /* disabled */ }
        static processDialog() { /* disabled */ }
        resetDialog() { /* disabled */ }
    }

    // Create instances to prevent errors but don't do anything
    window.dialogButtonReorder = new DialogButtonReorder();
    window.DialogButtonReorder = DialogButtonReorder;

    // jQuery integration - no-op
    if (typeof jQuery !== 'undefined') {
        jQuery.fn.reorderDialogButtons = function() {
            return this; // Do nothing, just return for chaining
        };
    }

})();

// Disabled integration with Bootstrap Modal events
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dialog button reordering: Bootstrap modal integration disabled');
    // All event handlers are disabled - using original Bootstrap layout
});