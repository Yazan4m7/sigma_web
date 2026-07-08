@extends('loginLayout.app', [
    'namePage' => 'Login page',
    'class' => 'login-page sidebar-mini ',
    'activePage' => 'login',
    'backgroundImage' => asset('assets') . "/img/bg14.jpg",
])

@section('content')
    <style>
        /* Modern Login Styling */
        .modern-login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;

            background-image:
                linear-gradient(rgb(5 90 0 / 26%), rgb(45 125 43 / 10%)), url(assets/bg_.jpg);
            /* actual image */
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }


        .login-card {
            background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
padding: 2rem;
    max-width: 92vw;
    width: 420px;
    margin: 2rem;
    position: relative;
    z-index: 2;
    animation: slideUp 0.8s
ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-logo {
            margin-top: 1.6em;
            width: 121px;
    height: auto;
    margin-bottom: 2.5rem;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }

        .login-title {
            color: #2d3748;
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #718096;
    font-size: 0.9rem;
    font-weight: 400;
    margin-bottom: 0;
        }

        .form-group-modern {
            position: relative;
            margin-bottom: 1.8rem;
        }

        .login-visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .input-field-wrap {
            position: relative;
            width: 85%;
            margin: 0 auto;
        }

        .form-input-modern {
            width: 100%;
    padding: 0.5rem 0rem 0.5rem 3rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #ffffff;
    color: #2d3748;
        }

        /* iPhone Safari zooms focused inputs below 16px; keep pinch zoom available. */
        @supports (-webkit-touch-callout: none) {
            @media screen and (max-width: 767px) {
                .form-input-modern {
                    font-size: 16px !important;
                }
            }
        }

        .form-input-modern:focus {
            outline: none;
            border-color: #38b44a63;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input-modern::placeholder {
            color: #a0aec0;
        }

        .input-icon {
            position: absolute;
    left: 1rem !important;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    font-size: 0.9rem;
    transition: color 0.3s
ease;
            /* Ensure icon remains visible and doesn't intercept pointer focus */
            pointer-events: none;
            z-index: 2;
            opacity: 1;
            visibility: visible;
        }

        .form-group-modern:focus-within .input-icon {
            color: #38b44a;
        }
        /* Also highlight when JS toggles the 'focused' class */
        .form-group-modern.focused .input-icon,
        .form-group-modern.has-value .input-icon {
            color: #38b44a;
        }

        .password-field {
            padding-right: 2.8rem;
        }

        /* Hide native browser password reveal/clear buttons (Edge/IE) */
        .password-field::-ms-reveal,
        .password-field::-ms-clear {
            display: none;
        }

        .password-toggle-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #a0aec0;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            z-index: 3;
            transition: color 0.3s ease;
        }

        .password-toggle-btn:focus {
            outline: none;
        }

        .form-group-modern:focus-within .password-toggle-btn,
        .form-group-modern.focused .password-toggle-btn,
        .form-group-modern.has-value .password-toggle-btn {
            color: #a0aec0;
        }

        .remember-login-option {
            width: 85%;
            margin: -0.4rem auto 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.55rem;
            color: #4a5568;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            user-select: none;
        }

        .remember-login-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .remember-box {
            width: 20px;
            height: 20px;
            border: 2px solid #cbd5e0;
            border-radius: 6px;
            background: #ffffff;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            transition: all 0.2s ease;
        }

        .remember-login-option input:checked + .remember-box {
            background: #38b449;
            border-color: #38b449;
            box-shadow: 0 0 0 3px rgba(56, 180, 73, 0.16);
        }

        .remember-login-option:focus-within .remember-box {
            border-color: #38b449;
            box-shadow: 0 0 0 3px rgba(56, 180, 73, 0.12);
        }

        .remember-text {
            line-height: 1;
        }

        .login-button {
            text-align: center;
    width: 60%;
    padding: 0.6rem 0.5rem;
    background: linear-gradient(135deg, #38b449 0%, #38b449 100%);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s
ease;
    margin-top: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .login-button:disabled {
            cursor: not-allowed;
            opacity: 0.75;
            transform: none;
            box-shadow: none;
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .error-icon {
            margin-right: 0.5rem;
            font-size: 1rem;
        }

        .has-error .form-input-modern {
            border-color: #e53e3e;
            box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
        }

        .has-error .input-icon {
            color: #e53e3e;
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .floating-elements::before,
        .floating-elements::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .floating-elements::before {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-elements::after {
            bottom: 10%;
            right: 10%;
            animation-delay: 3s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .system-status {
            text-align: center;
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 8px;
            background: rgba(102, 126, 234, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .system-status p {
            margin: 0;
            color: #667eea;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .login-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
                border-radius: 16px;
                width: 90%;
            }

            .login-title {
                font-size: 1.2rem;
            }

            .login-logo {
                width: 100px;
            }
        }
    </style>

    <div class="modern-login-container">
        <div class="floating-elements"></div>

        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('assets/sigma_favico.png') }}" alt="SIGMA Logo" class="login-logo">
                <h1 class="login-title">Welcome Back</h1>
                <p class="login-subtitle">Enter your credentials</p>
            </div>

            <form id="login-form" role="form" method="POST" action="{{ route('login') }}" autocomplete="on" style="text-align: center;  ">
                @csrf

                <div class="form-group-modern {{ $errors->has('username') ? 'has-error' : '' }}">
                    <div class="input-field-wrap">
                        <label for="login-username" class="login-visually-hidden">Username</label>
                        <i class="fas fa-user input-icon"></i>
                        <input id="login-username"
                               type="text"
                               name="username"
                               autocomplete="username"
                               class="form-input-modern"
                               placeholder="Enter your username"
                               value="{{ old('username') }}"
                               required>
                    </div>
                    @if ($errors->has('username'))
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle error-icon"></i>
                            <strong>{{ $errors->first('username') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group-modern {{ $errors->has('password') ? 'has-error' : '' }}">
                    <div class="input-field-wrap">
                        <label for="login-password" class="login-visually-hidden">Password</label>
                        <i class="fas fa-lock input-icon"></i>
                        <input id="login-password"
                               type="password"
                               name="password"
                               autocomplete="current-password"
                               class="form-input-modern password-field"
                               placeholder="Enter your password"
                               required>
                        <button type="button" class="password-toggle-btn" aria-label="Show password">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                    @if ($errors->has('password'))
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle error-icon"></i>
                            <strong>{{ $errors->first('password') }}</strong>
                        </div>
                    @endif
                </div>

                <label class="remember-login-option" for="login-remember">
                    <input id="login-remember"
                           type="checkbox"
                           name="remember"
                           value="1"
                           {{ old('remember', '1') ? 'checked' : '' }}>
                    <span class="remember-box" aria-hidden="true">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="remember-text">Remember me</span>
                </label>

                <button type="submit" class="login-button">
                    <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i>
                    Sign In
                </button>
            </form>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        let isSubmitting = false;

        // Refresh CSRF token every 5 minutes to prevent expiry
        setInterval(function() {
            $.get('{{ route("login") }}', function(data) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, 'text/html');
                const newToken = doc.querySelector('meta[name="csrf-token"]');
                if (newToken) {
                    $('meta[name="csrf-token"]').attr('content', newToken.content);
                    $('input[name="_token"]').val(newToken.content);
                }
            }).fail(function() {
                // If refresh fails, reload the page to get a new token
                console.log('CSRF token refresh failed, will reload on next submit');
            });
        }, 300000); // 5 minutes

        // Add focus/blur effects for modern inputs
        $('.form-input-modern').on('focus', function() {
            $(this).closest('.form-group-modern').addClass('focused');
        });

        $('.form-input-modern').on('blur', function() {
            const group = $(this).closest('.form-group-modern');
            group.removeClass('focused');
            group.toggleClass('has-value', $(this).val().trim().length > 0);
        });

        // Keep value state in sync for autofill/manual edits
        $('.form-input-modern').on('input', function() {
            const group = $(this).closest('.form-group-modern');
            group.toggleClass('has-value', $(this).val().trim().length > 0);
        });

        // Initialize filled state on page load (including old() values)
        $('.form-input-modern').each(function() {
            const group = $(this).closest('.form-group-modern');
            group.toggleClass('has-value', $(this).val().trim().length > 0);
        });

        // Password visibility toggle
        $('.password-toggle-btn').on('click', function() {
            const button = $(this);
            const input = button.siblings('input[name="password"]');
            const icon = button.find('i');
            const isPassword = input.attr('type') === 'password';

            input.attr('type', isPassword ? 'text' : 'password');
            icon.toggleClass('fa-eye', isPassword);
            icon.toggleClass('fa-eye-slash', !isPassword);
            button.attr('aria-label', isPassword ? 'Hide password' : 'Show password');
            input.trigger('focus');
        });

        // Add loading state to login button
        $('#login-form').on('submit', function(e) {
            const form = $(this);
            const passwordInput = form.find('input[name="password"]');
            const button = form.find('.login-button');

            if (isSubmitting) {
                e.preventDefault();
                return false;
            }

            isSubmitting = true;
            passwordInput.attr('type', 'password');
            button.data('original-html', button.html());
            button.html('<i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>Signing In...');
            button.prop('disabled', true);
            button.attr('aria-disabled', 'true');

            // Re-enable after 10 seconds to handle failed logins or network issues
            setTimeout(function() {
                isSubmitting = false;
                button.html(button.data('original-html'));
                button.prop('disabled', false);
                button.removeAttr('aria-disabled');
            }, 10000);
        });

        // Add enter key support
        $('.form-input-modern').on('keypress', function(e) {
            if (e.which === 13) {
                $('#login-form').trigger('submit');
            }
        });

        // Auto-focus username field
        $('input[name="username"]').focus();
    });
</script>
@endpush
