(function () {
    var STORAGE_PREFIX = 'sigma_pref_';
    var defaults = {
        locks_enabled: false,
        lock_dialog_fonts: false,
        lock_table_fonts: false,
        lock_other_fonts: false,
        disable_sidebar_expand: false
    };

    function readBool(key) {
        var raw = localStorage.getItem(STORAGE_PREFIX + key);
        if (raw === null || raw === undefined) {
            return !!defaults[key];
        }
        return raw === 'true';
    }

    function writeBool(key, value) {
        localStorage.setItem(STORAGE_PREFIX + key, value ? 'true' : 'false');
    }

    function applyBodyPreferenceClasses() {
        var locksEnabled = readBool('locks_enabled');
        var lockDialogs = locksEnabled && readBool('lock_dialog_fonts');
        var lockTables = locksEnabled && readBool('lock_table_fonts');
        var lockOther = locksEnabled && readBool('lock_other_fonts');
        var disableSidebarExpand = readBool('disable_sidebar_expand');

        document.body.classList.toggle('pref-lock-dialog-fonts', lockDialogs);
        document.body.classList.toggle('pref-lock-table-fonts', lockTables);
        document.body.classList.toggle('pref-lock-other-fonts', lockOther);
        document.body.classList.toggle('pref-disable-sidebar-expand', disableSidebarExpand);
        document.body.classList.toggle('sidebar-expanded-lock', disableSidebarExpand);
    }

    function clearAllSavedWidthKeys() {
        var removed = [];
        for (var i = localStorage.length - 1; i >= 0; i--) {
            var key = localStorage.key(i);
            if (!key) continue;
            if (key.endsWith('_widths') || key === 'dashboard_master_widths') {
                removed.push(key);
                localStorage.removeItem(key);
            }
        }
        document.dispatchEvent(new CustomEvent('sigma:table-widths-reset', { detail: { removedKeys: removed } }));
        return removed;
    }

    function syncSettingsPage() {
        var toggles = document.querySelectorAll('.sigma-pref-toggle[data-pref-key]');
        if (!toggles.length) return;

        toggles.forEach(function (toggle) {
            var key = toggle.getAttribute('data-pref-key');
            if (!key) return;
            toggle.checked = readBool(key);
        });

        var masterToggle = document.querySelector('.sigma-pref-toggle[data-pref-key="locks_enabled"]');
        var childLocks = document.querySelectorAll('.sigma-pref-child-lock');
        function updateChildDisabledState() {
            var disabled = !(masterToggle && masterToggle.checked);
            childLocks.forEach(function (node) {
                node.disabled = disabled;
            });
        }
        updateChildDisabledState();

        toggles.forEach(function (toggle) {
            toggle.addEventListener('change', function () {
                var key = this.getAttribute('data-pref-key');
                writeBool(key, !!this.checked);
                if (key === 'locks_enabled') {
                    updateChildDisabledState();
                }
                if (key === 'disable_sidebar_expand') {
                    document.dispatchEvent(new CustomEvent('sigma:sidebar-pref-changed'));
                }
                applyBodyPreferenceClasses();
            });
        });

        var resetBtn = document.getElementById('resetAllTableWidthsBtn');
        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                var removed = clearAllSavedWidthKeys();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Reset Complete',
                        text: removed.length ? 'Saved table widths were cleared.' : 'No saved table widths found.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    alert(removed.length ? 'Saved table widths were cleared.' : 'No saved table widths found.');
                }
            });
        }
    }

    function enforceInputFontSizeForIOS() {
        var ua = navigator.userAgent || '';
        var isIOS = /iPad|iPhone|iPod/.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
        if (!isIOS) return;

        // Only touch text-capable controls to prevent iOS focus zoom.
        var style = document.createElement('style');
        style.id = 'sigma-ios-input-font-lock';
        style.textContent = [
            'input[type="text"],input[type="search"],input[type="email"],input[type="tel"],input[type="url"],input[type="password"],input[type="number"],textarea,select{',
            'font-size:16px !important;',
            '}'
        ].join('');
        document.head.appendChild(style);
    }

    window.sigmaUserPreferences = {
        readBool: readBool,
        writeBool: writeBool,
        applyBodyPreferenceClasses: applyBodyPreferenceClasses,
        clearAllSavedWidthKeys: clearAllSavedWidthKeys
    };

    document.addEventListener('DOMContentLoaded', function () {
        applyBodyPreferenceClasses();
        syncSettingsPage();
        enforceInputFontSizeForIOS();
    });
})();
