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
                linear-gradient(rgba(63, 139, 56, 0.7), rgba(6, 255, 0, 0.1)),  /* gradient overlay */
                url('assets/bg_.jpg');                                   /* actual image */
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }

        .modern-login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('{{ asset('assets/images/general/login.jpg') }}') center/cover;
            opacity: 0.1;
            z-index: 1;
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
    font-size: 1.7em;
    font-weight: 700;
    margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #718096;
    font-size: 1rem;
    font-weight: 400;
    margin-bottom: 0;
        }

        .form-group-modern {
            position: relative;
            margin-bottom: 1.8rem;
        }

        .form-input-modern {
            width: 85%;
    padding: 0.5rem 0rem 0.5rem 3rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #ffffff;
    color: #2d3748;
        }

        .form-input-modern:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-input-modern::placeholder {
            color: #a0aec0;
        }

        .input-icon {
            position: absolute;
    left: 2.5rem;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    font-size: 1.0rem;
    transition: color 0.3s
ease;
        }

        .form-group-modern:focus-within .input-icon {
            color: #667eea;
        }

        .login-button {
            text-align: center;
    width: 60%;
    padding: 0.6rem 0.5rem;
    background: linear-gradient(135deg, #38b449 0%, #38b449 100%);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 1.12rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s
ease;
    margin-top: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .login-button:active {
            transform: translateY(0);
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
                font-size: 1.5rem;
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

            @if($errors->any() || isset($migrations_check))

                    @include('alerts.migrations_check')

            @endif

            <form role="form" method="POST" action="{{ route('login') }}" autocomplete="on" style="text-align: center;  ">
                @csrf

                <div class="form-group-modern {{ $errors->has('username') ? 'has-error' : '' }}">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text"
                           name="username"
                           autocomplete="username"
                           class="form-input-modern"
                           placeholder="Enter your username"
                           value="{{ old('username') }}"
                           required>
                    @if ($errors->has('username'))
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle error-icon"></i>
                            <strong>{{ $errors->first('username') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group-modern {{ $errors->has('password') ? 'has-error' : '' }}">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password"
                           name="password"
                           autocomplete="current-password"
                           class="form-input-modern"
                           placeholder="Enter your password"
                           required>
                    @if ($errors->has('password'))
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle error-icon"></i>
                            <strong>{{ $errors->first('password') }}</strong>
                        </div>
                    @endif
                </div>

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
            $(this).closest('.form-group-modern').removeClass('focused');
        });

        // Add loading state to login button
        $('form').on('submit', function(e) {
            const button = $('.login-button');
            const originalText = button.html();

            button.html('<i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>Signing In...');
            button.prop('disabled', true);

            // Re-enable after 10 seconds to handle failed logins or network issues
            setTimeout(function() {
                button.html(originalText);
                button.prop('disabled', false);
            }, 10000);
        });

        // Add enter key support
        $('.form-input-modern').on('keypress', function(e) {
            if (e.which === 13) {
                $('form').submit();
            }
        });

        // Auto-focus username field
        $('input[name="username"]').focus();
    });
</script>
@endpush
