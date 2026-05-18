<x-app-layout>
@push('css')
<style>
    body.nav-fixed {
        background: #2196f3;
    }

    .header-wrap,
    footer {
        display: none;
    }

    .erp-login-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 32px 16px;
        background:
            radial-gradient(circle at 18% 12%, rgba(255, 255, 255, 0.82), transparent 30%),
            radial-gradient(circle at 82% 85%, rgba(148, 171, 182, 0.32), transparent 34%),
            linear-gradient(135deg, #eef4f6 0%, #d0dde2 50%, #b8cbd4 100%);
        font-family: "Inter", "Segoe UI", Arial, sans-serif;
    }

    .erp-login-shell {
        width: 100%;
        max-width: 960px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 46px;
        padding: 36px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 22px 60px rgba(67, 92, 105, 0.22);
    }

    .erp-login-card {
        width: 100%;
        max-width: 430px;
        padding: 10px 8px;
        background: transparent;
        border-radius: 0;
        box-shadow: none;
    }

    .erp-login-logo {
        text-align: center;
        margin-bottom: 24px;
    }

    .erp-login-logo img {
        max-width: 178px;
        height: auto;
    }

    .erp-login-title {
        margin: 0 0 6px;
        color: #4b5563;
        font-size: 24px;
        font-weight: 600;
        text-align: center;
    }

    .erp-login-subtitle {
        margin: 0 0 24px;
        color: #8a96a8;
        font-size: 14px;
        text-align: center;
    }

    .erp-form-group {
        margin-bottom: 18px;
    }

    .erp-input-wrap {
        position: relative;
    }

    .erp-input-icon {
        position: absolute;
        top: 50%;
        left: 15px;
        color: #9aa8b8;
        font-size: 15px;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .erp-login-card .form-control {
        width: 100%;
        height: 48px;
        padding: 11px 14px 11px 43px;
        border: 1px solid #d9e2ec;
        border-radius: 4px;
        color: #344054;
        font-size: 14px;
        background: #fff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .erp-login-card .form-control:focus {
        border-color: #2196f3;
        box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.14);
    }

    .erp-login-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin: 2px 0 22px;
        color: #6b7280;
        font-size: 14px;
    }

    .erp-login-options .form-check {
        margin: 0;
        min-height: auto;
    }

    .erp-login-options .form-check-input {
        margin-top: 0.18rem;
        border-color: #cbd5e1;
    }

    .erp-login-options .form-check-input:checked {
        background-color: #2196f3;
        border-color: #2196f3;
    }

    .erp-login-options a {
        color: #2196f3;
        text-decoration: none;
        white-space: nowrap;
    }

    .erp-login-options a:hover {
        color: #176fbb;
    }

    .erp-login-submit {
        width: 100%;
        height: 48px;
        border: 0;
        border-radius: 4px;
        background: #2196f3;
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 0;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .erp-login-submit:hover {
        background: #177dd0;
        color: #fff;
        transform: translateY(-1px);
    }

    .erp-login-bottom {
        margin-top: 24px;
        color: #7b8794;
        font-size: 14px;
        text-align: center;
    }

    .erp-login-bottom a {
        color: #2196f3;
        font-weight: 600;
        text-decoration: none;
    }

    .erp-status {
        margin-bottom: 18px;
        padding: 11px 14px;
        border-radius: 4px;
        background: #ecfdf3;
        color: #027a48;
        font-size: 14px;
    }

    .erp-login-visual {
        position: relative;
        width: min(42vw, 380px);
        min-height: 390px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: erpFloat 4.5s ease-in-out infinite;
    }

    .erp-login-visual::before {
        content: "";
        position: absolute;
        inset: 52px 18px 24px;
        border-radius: 50%;
    
        filter: blur(2px);
    }

    .erp-login-visual img {
        position: relative;
        z-index: 1;
        width: 200px;
        height: auto;
        object-fit: contain;
        filter: drop-shadow(0 22px 28px rgba(79, 101, 112, 0.22));
    }

    @keyframes erpFloat {
        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-18px);
        }
    }

    @media (max-width: 991px) {
        .erp-login-shell {
            max-width: 430px;
            padding: 28px 22px;
        }

        .erp-login-visual {
            display: none;
        }
    }

    @media (max-width: 575px) {
        .erp-login-page {
            align-items: flex-start;
            padding-top: 42px;
        }

        .erp-login-card {
            padding: 28px 22px 24px;
        }

        .erp-login-options {
            align-items: flex-start;
            flex-direction: column;
            gap: 10px;
        }
    }
</style>
@endpush

<div class="erp-login-page">
    <div class="erp-login-shell">
        <div class="erp-login-card">
            <div class="erp-login-logo">
                <img src="{{ asset('images/logo/YessTrav.png') }}" alt="Yess Travel">
            </div>

            <h1 class="erp-login-title">Login</h1>
            <p class="erp-login-subtitle">Sign in to your account</p>

            <x-validation-errors class="mb-4" />

            @if (session('status'))
                <div class="erp-status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                @csrf

                <div class="erp-form-group">
                    <label class="visually-hidden" for="email">Email address</label>
                    <div class="erp-input-wrap">
                        <i class="fas fa-envelope erp-input-icon"></i>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="form-control @if($errors->has('email')) is-invalid @endif"
                            placeholder="Email address"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                        <div class="invalid-feedback">{{ $errors->first('email') ?? 'Please enter a valid email address.' }}</div>
                    </div>
                </div>

                <div class="erp-form-group">
                    <label class="visually-hidden" for="password">Password</label>
                    <div class="erp-input-wrap">
                        <i class="fas fa-lock erp-input-icon"></i>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-control @if($errors->has('password')) is-invalid @endif"
                            placeholder="Password"
                            required
                        >
                        <div class="invalid-feedback">{{ $errors->first('password') ?? 'Please enter your password.' }}</div>
                    </div>
                </div>

                <div class="erp-login-options">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember_me" checked>
                        <label class="form-check-label" for="remember_me">Remember me</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="erp-login-submit">Login</button>

                <div class="erp-login-bottom">
                    Don't have an account? <a href="{{ url('/register') }}">Sign Up</a>
                </div>
            </form>
        </div>

        <div class="erp-login-visual" aria-hidden="true">
            <img src="{{ asset('images/icons/business-trip.png') }}" alt="Business Trip Illustration" class="w-[120px] h-auto">
        </div>
    </div>
</div>
</x-app-layout>
