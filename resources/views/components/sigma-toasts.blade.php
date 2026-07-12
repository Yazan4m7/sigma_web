@php
    $sigmaToastMessages = [];

    $pushSigmaToast = function ($type, $message, $fallback = null) use (&$sigmaToastMessages, &$pushSigmaToast) {
        if ($message instanceof \Throwable) {
            $message = $message->getMessage();
        }

        if ($message instanceof \Illuminate\Support\MessageBag) {
            foreach ($message->all() as $bagMessage) {
                $pushSigmaToast($type, $bagMessage, $fallback);
            }
            return;
        }

        if ($message instanceof \Illuminate\Support\Collection) {
            $message = $message->all();
        }

        if (is_array($message)) {
            foreach ($message as $item) {
                $pushSigmaToast($type, $item, $fallback);
            }
            return;
        }

        if (($message === true || $message === 1 || $message === '1') && $fallback) {
            $message = $fallback;
        }

        $text = trim((string) $message);

        if ($text === '' && $fallback) {
            $text = $fallback;
        }

        if ($text !== '') {
            $sigmaToastMessages[] = [
                'type' => $type,
                'message' => $text,
            ];
        }
    };

    $sigmaFlashKeys = [
        'success' => ['type' => 'success'],
        'status' => ['type' => 'success'],
        'password_status' => ['type' => 'success'],
        'resent' => ['type' => 'success', 'fallback' => __('A fresh verification link has been sent to your email address.')],
        'error' => ['type' => 'error'],
        'danger' => ['type' => 'error'],
        'nouser' => ['type' => 'error', 'fallback' => 'Wrong Email or Password'],
        'unauthorized' => ['type' => 'error', 'fallback' => 'Unauthorized to Enter admin panel'],
        'warning' => ['type' => 'warning'],
        'info' => ['type' => 'info'],
        'message' => ['type' => 'info'],
        'deploy_status' => ['type' => 'info'],
    ];

    foreach ($sigmaFlashKeys as $key => $config) {
        if (session()->has($key)) {
            $pushSigmaToast($config['type'], session($key), $config['fallback'] ?? null);
        }
    }

    if (isset($errors)) {
        try {
            if ($errors->any()) {
                foreach ($errors->all() as $error) {
                    $pushSigmaToast('error', $error);
                }
            }
        } catch (\Throwable $e) {
            //
        }
    }

    try {
        $isLoginRoute = request()->routeIs('login') || request()->is('login');
    } catch (\Throwable $e) {
        $isLoginRoute = false;
    }

    if ($isLoginRoute) {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable((new \App\Models\User)->getTable())) {
                $pushSigmaToast('error', __('You did not run the migrations and seeders! The login information will not be available!'));
            }
        } catch (\Throwable $e) {
            //
        }
    }

    $sigmaToastMessages = collect($sigmaToastMessages)
        ->unique(fn ($toast) => $toast['type'] . '|' . $toast['message'])
        ->values()
        ->map(function ($toast, $index) {
            $toast['onceKey'] = hash('sha256', (string) \Illuminate\Support\Str::uuid() . '|' . $index . '|' . $toast['type'] . '|' . $toast['message']);

            return $toast;
        })
        ->all();
@endphp

@once
    <style>
        .toast-alert {
            position: fixed;
            top: 20px;
            left: 50%;
            z-index: 2147483000;
            display: flex;
            align-items: center;
            gap: 12px;
            width: auto;
            min-width: 280px;
            max-width: min(400px, calc(100vw - 32px));
            min-height: 52px;
            padding: 16px 24px;
            color: #374151;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #ef4444;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.08);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", sans-serif;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.4;
            text-align: left;
            opacity: 0;
            overflow-wrap: anywhere;
            white-space: pre-line;
            backdrop-filter: blur(8px);
            cursor: pointer;
            transform: translateX(-50%) translateY(-10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .toast-alert::before {
            content: "!";
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            color: #ffffff;
            background: #ef4444;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            line-height: 1;
        }

        .toast-alert span {
            min-width: 0;
        }

        .toast-alert.warning {
            color: #92400e;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left-color: #f59e0b;
        }

        .toast-alert.warning::before {
            content: "!";
            background: #f59e0b;
        }

        .toast-alert.success {
            color: #065f46;
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-left-color: #10b981;
        }

        .toast-alert.success::before {
            content: "\2713";
            background: #10b981;
        }

        .toast-alert.info {
            color: #1e40af;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-left-color: #3b82f6;
        }

        .toast-alert.info::before {
            content: "i";
            background: #3b82f6;
        }

        .toast-alert.error {
            color: #991b1b;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left-color: #ef4444;
        }

        .toast-alert.error::before {
            content: "\00d7";
            background: #ef4444;
            font-size: 18px;
        }

        @media (max-width: 575.98px) {
            .toast-alert {
                top: max(12px, env(safe-area-inset-top));
                width: calc(100vw - 24px);
                min-width: 0;
                max-width: calc(100vw - 24px);
                padding: 14px 16px;
                border-radius: 10px;
                font-size: 13px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .toast-alert {
                transition: none;
            }
        }
    </style>

    <script>
        (function () {
            var initialToasts = @json($sigmaToastMessages);
            var toastStoragePrefix = 'sigma-toast-shown:';

            function removeToast(item) {
                if (!item) {
                    return;
                }

                if (item.__sigmaToastTimer) {
                    clearTimeout(item.__sigmaToastTimer);
                }

                item.style.top = '-60px';
                item.style.opacity = '0';
                item.style.transform = 'translateX(-50%) translateY(-10px)';

                setTimeout(function () {
                    if (item.parentNode) {
                        item.parentNode.removeChild(item);
                    }
                }, 180);
            }

            function getToastOnceKeys(toast) {
                var keys = [];

                if (toast && toast.onceKey) {
                    keys.push(String(toast.onceKey));
                }

                if (toast && Array.isArray(toast.onceKeys)) {
                    toast.onceKeys.forEach(function (key) {
                        if (key) {
                            keys.push(String(key));
                        }
                    });
                }

                return keys;
            }

            function getToastStorage() {
                try {
                    return window.sessionStorage;
                } catch (error) {
                    return null;
                }
            }

            function toastWasShown(toast) {
                var keys = getToastOnceKeys(toast);
                var storage = getToastStorage();

                if (!keys.length || !storage) {
                    return false;
                }

                try {
                    return keys.some(function (key) {
                        return storage.getItem(toastStoragePrefix + key) === '1';
                    });
                } catch (error) {
                    return false;
                }
            }

            function rememberToast(toast) {
                var keys = getToastOnceKeys(toast);
                var storage = getToastStorage();

                if (!keys.length || !storage) {
                    return;
                }

                try {
                    keys.forEach(function (key) {
                        storage.setItem(toastStoragePrefix + key, '1');
                    });
                } catch (error) {
                    //
                }
            }

            function renderToast(toast) {
                if (!toast || !toast.message) {
                    return;
                }

                if (toastWasShown(toast)) {
                    return;
                }

                var allowedTypes = ['success', 'error', 'warning', 'info'];
                var type = allowedTypes.indexOf(toast.type) === -1 ? 'info' : toast.type;
                var existingToasts = document.querySelectorAll('.sigma-toast, .toast-alert');
                var item = document.createElement('div');
                var message = document.createElement('span');
                var duration = Number(toast.duration || 4000);
                var visibleTop = window.matchMedia && window.matchMedia('(max-width: 575.98px)').matches
                    ? 'max(12px, env(safe-area-inset-top))'
                    : '20px';

                existingToasts.forEach(function (existingToast) {
                    if (existingToast.parentNode) {
                        existingToast.parentNode.removeChild(existingToast);
                    }
                });

                item.className = 'toast-alert ' + type;
                item.setAttribute('role', type === 'error' ? 'alert' : 'status');
                item.setAttribute('aria-live', type === 'error' ? 'assertive' : 'polite');
                item.setAttribute('title', 'Click to dismiss');

                message.textContent = String(toast.message);

                item.appendChild(message);
                item.addEventListener('click', function () {
                    removeToast(item);
                });

                document.body.appendChild(item);
                item.style.opacity = '0';
                item.style.top = '-60px';
                item.style.transform = 'translateX(-50%) translateY(-10px)';

                requestAnimationFrame(function () {
                    item.style.top = visibleTop;
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(-50%) translateY(0)';
                });

                if (duration > 0) {
                    item.__sigmaToastTimer = setTimeout(function () {
                        removeToast(item);
                    }, duration);
                }

                rememberToast(toast);
            }

            function collapseToastBatch(toasts) {
                var normalized = (toasts || []).filter(function (toast) {
                    return toast && toast.message;
                });

                if (normalized.length <= 1) {
                    return normalized;
                }

                return [{
                    type: normalized.some(function (toast) { return toast.type === 'error'; }) ? 'error' : normalized[0].type,
                    message: normalized.map(function (toast) { return toast.message; }).join('\n'),
                    onceKeys: normalized.reduce(function (keys, toast) {
                        return keys.concat(getToastOnceKeys(toast));
                    }, [])
                }];
            }

            function showToast(toast, maybeMessage) {
                if (typeof toast === 'string') {
                    var allowedTypes = ['success', 'error', 'warning', 'info'];
                    var firstArgumentIsType = allowedTypes.indexOf(toast) !== -1 && typeof maybeMessage === 'string';

                    toast = {
                        type: firstArgumentIsType ? toast : (maybeMessage || 'error'),
                        message: firstArgumentIsType ? maybeMessage : toast
                    };
                }

                if (!document.body) {
                    window.__sigmaPendingToasts = window.__sigmaPendingToasts || [];
                    window.__sigmaPendingToasts.push(toast);
                    return;
                }

                renderToast(toast || {});
            }

            function flushToasts() {
                var pendingToasts = window.__sigmaPendingToasts || [];
                window.__sigmaPendingToasts = [];
                collapseToastBatch(pendingToasts.concat(initialToasts)).forEach(showToast);
            }

            window.sigmaShowToast = showToast;

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', flushToasts);
                return;
            }

            flushToasts();
        })();
    </script>
@endonce
