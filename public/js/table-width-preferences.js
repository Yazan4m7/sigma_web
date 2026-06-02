(function () {
    'use strict';

    function ensureObject(value) {
        return value && typeof value === 'object' ? value : {};
    }

    var prefs = ensureObject(window.__sigmaTableWidthPrefs);
    var defaults = ensureObject(window.__sigmaTableWidthDefaults);
    var pendingSaves = {};
    var saveDelayMs = 350;

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function fetchJson(url, options) {
        return fetch(url, options || {}).then(function (response) {
            if (!response.ok) {
                throw new Error('Request failed');
            }
            return response.json();
        });
    }

    function postJson(url, payload) {
        return fetchJson(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload || {})
        });
    }

    var readyPromise = Promise.resolve();
    if (!window.__sigmaTableWidthPrefs) {
        readyPromise = fetchJson('/user-preferences/table-widths', {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function (data) {
            prefs = ensureObject(data.data);
            defaults = ensureObject(data.defaults);
            return { prefs: prefs, defaults: defaults };
        }).catch(function () {
            prefs = ensureObject(prefs);
            defaults = ensureObject(defaults);
            return { prefs: prefs, defaults: defaults };
        });
    }

    function get(scope) {
        if (!scope) {
            return null;
        }
        if (Object.prototype.hasOwnProperty.call(prefs, scope)) {
            return prefs[scope];
        }
        if (Object.prototype.hasOwnProperty.call(defaults, scope)) {
            return defaults[scope];
        }
        return null;
    }

    function scheduleSave(scope, widths) {
        if (!scope) {
            return;
        }
        if (pendingSaves[scope]) {
            clearTimeout(pendingSaves[scope]);
        }
        pendingSaves[scope] = setTimeout(function () {
            pendingSaves[scope] = null;
            postJson('/user-preferences/table-widths', { scope: scope, widths: widths }).catch(function () {});
        }, saveDelayMs);
    }

    function set(scope, widths) {
        if (!scope) {
            return;
        }
        prefs[scope] = widths;
        scheduleSave(scope, widths);
    }

    function reset(scope) {
        return readyPromise.then(function () {
            if (scope) {
                delete prefs[scope];
                return postJson('/user-preferences/table-widths/reset', { scope: scope })
                    .catch(function () {})
                    .then(function () {
                        document.dispatchEvent(new CustomEvent('sigma:table-widths-reset', { detail: { removedKeys: [scope] } }));
                        return [scope];
                    });
            }
            return clearAll();
        });
    }

    function clearAll() {
        return readyPromise.then(function () {
            var removed = Object.keys(prefs || {});
            prefs = {};
            return postJson('/user-preferences/table-widths/reset', {})
                .catch(function () {})
                .then(function () {
                    document.dispatchEvent(new CustomEvent('sigma:table-widths-reset', { detail: { removedKeys: removed } }));
                    return removed;
                });
        });
    }

    function whenReady(callback) {
        if (typeof callback === 'function') {
            readyPromise.then(callback);
        }
        return readyPromise;
    }

    window.sigmaTableWidthStore = {
        get: get,
        set: set,
        reset: reset,
        clearAll: clearAll,
        whenReady: whenReady
    };
})();
