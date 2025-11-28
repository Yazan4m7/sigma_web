/**
 * Global double-submit guard for all forms.
 * - Locks a form on first submit (button click or programmatic submit())
 * - Injects a submission id so the backend can reject duplicates
 * - Unlocks after a short timeout in case the page stays on-screen
 */
(function () {
    'use strict';

    var LOCK_ATTR = 'data-submit-locked';
    var TOKEN_FIELD = '_submission_id';
    var LOCK_TIMEOUT_MS = 8000;

    function generateId() {
        if (window.crypto && typeof window.crypto.randomUUID === 'function') {
            return window.crypto.randomUUID();
        }

        return 'sub-' + Math.random().toString(16).slice(2) + Date.now().toString(16);
    }

    function ensureToken(form) {
        var tokenInput = form.querySelector('input[name="' + TOKEN_FIELD + '"]');

        if (!tokenInput) {
            tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = TOKEN_FIELD;
            form.appendChild(tokenInput);
        }

        if (!tokenInput.value) {
            tokenInput.value = generateId();
        }

        return tokenInput.value;
    }

    function markButtonsDisabled(form) {
        var buttons = form.querySelectorAll('button[type="submit"], button:not([type]), input[type="submit"], [data-submit-lock-button]');

        buttons.forEach(function (btn) {
            if (btn.dataset.submitLock === 'locked') {
                return;
            }

            btn.dataset.submitLock = 'locked';
            btn.dataset.submitLockWasDisabled = btn.disabled ? '1' : '0';
            btn.disabled = true;
            btn.setAttribute('aria-busy', 'true');
        });
    }

    function unlockForm(form) {
        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        form.removeAttribute(LOCK_ATTR);
        form.removeAttribute('data-submission-id');

        var buttons = form.querySelectorAll('[data-submit-lock="locked"]');
        buttons.forEach(function (btn) {
            if (btn.dataset.submitLockWasDisabled === '0') {
                btn.disabled = false;
            }
            btn.removeAttribute('aria-busy');
            btn.removeAttribute('data-submit-lock');
            btn.removeAttribute('data-submit-lock-was-disabled');
            delete btn.dataset.submitLock;
            delete btn.dataset.submitLockWasDisabled;
        });
    }

    function prepareFormSubmission(form) {
        if (!(form instanceof HTMLFormElement)) {
            return true;
        }

        if (form.hasAttribute('data-allow-multiple-submits')) {
            return true;
        }

        if (form.getAttribute(LOCK_ATTR) === 'true') {
            return false;
        }

        var requiresToken = submissionTokenRequired(form);
        var submissionId = requiresToken ? ensureToken(form) : '';

        form.setAttribute(LOCK_ATTR, 'true');
        if (requiresToken) {
            form.setAttribute('data-submission-id', submissionId);
        } else {
            form.removeAttribute('data-submission-id');
        }
        markButtonsDisabled(form);

        window.setTimeout(function () {
            unlockForm(form);
        }, LOCK_TIMEOUT_MS);

        return true;
    }

    document.addEventListener('submit', function (event) {
        var form = event.target;

        if (!prepareFormSubmission(form)) {
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    }, true);

    var nativeSubmit = HTMLFormElement.prototype.submit;
    HTMLFormElement.prototype.submit = function submitOverride() {
        if (!prepareFormSubmission(this)) {
            return;
        }

        return nativeSubmit.call(this);
    };

    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            document.querySelectorAll('form[' + LOCK_ATTR + ']').forEach(unlockForm);
        }
    });

    function submissionTokenRequired(form) {
        var method = (form.getAttribute('method') || form.method || 'get').toLowerCase();
        return method !== 'get' && method !== 'head';
    }
})();
