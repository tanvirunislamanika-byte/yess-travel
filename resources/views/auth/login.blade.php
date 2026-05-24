
<!-- FLOAT ANIMATION CSS -->
<style>
@keyframes floatBox {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-12px);
    }
    100% {
        transform: translateY(0px);
    }
}

.login-card {
    max-width: 980px;
    width: 100%;
}

    .login-card .input-group {
        width: 100%;
    }

    .login-card .form-control {
        min-height: calc(1.5em + 1rem);
        width: 100%;
        background: #fff;
        color: #212529;
        border: 1px solid #ced4da;
    }

    .login-card .input-group-text {
        background: #fff;
        border: 1px solid #ced4da;
        border-right: 0;
    }

    .login-card .form-control.border-start-0 {
        border-left: 0;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .remember-row {
        width: 100%;
    }

    .forgot-password {
        white-space: nowrap;
    }

    background: rgba(255, 255, 255, 0.95);
}

.login-hero img {
    max-width: 100%;
    height: auto;
}

@media (max-width: 991.98px) {
    .login-card .row.g-0 {
        flex-wrap: wrap;
    }

    .login-card .p-5 {
        padding: 1.5rem;
    }
}

@media (max-width: 575.98px) {
    .login-card {
        margin: 0 0.5rem;
    }

    .login-card .p-5 {
        padding: 1.25rem;
    }

    .login-card h2 {
        font-size: 1.8rem;
    }

    .remember-row {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 0.75rem;
    }

    .forgot-password {
        align-self: flex-start;
    }
    @media (max-width: 575.98px) {

    /* INPUT FIELD SIZE (UPDATED) */
    .login-card .form-control {
        height: 48px;
        font-size: 14px;
        padding: 0.55rem 0.85rem;
        width: 100%;
        border-radius: 0.375rem !important;
        border: 1px solid #ced4da !important;
    }

    .login-card .input-group {
        width: 100%;
        display: block;
    }

    /* ICON REMOVE ONLY MOBILE */
    .login-card .input-group-text {
        display: none !important;
    }

    /* FIX BROKEN BORDER AFTER ICON REMOVE */
    .login-card .form-control.border-start-0 {
        border-left: 1px solid #ced4da !important;
    }

    .login-card .btn.w-100 {
        height: 46px;
        font-size: 14px;
    }

    /* DEMO LOGIN BOX FIX + PADDING */
    .border.rounded-3.px-3.py-2.bg-white,
    .border.rounded-3.px-3.py-2.mb-2.bg-white {
        flex-wrap: nowrap !important;
        align-items: center !important;
        padding: 16px 18px !important; /* একটু বেশি clean spacing */
        gap: 10px;
    }

    #demo_email,
    #demo_password {
        font-size: 13px;
        line-height: 1.3;
        word-break: break-word;
    }

    .border.rounded-3.px-3.py-2.bg-white button,
    .border.rounded-3.px-3.py-2.mb-2.bg-white button {
        width: auto !important;
        white-space: nowrap;
        padding: 6px 12px;
        font-size: 12px;
        margin-left: 10px;
    }
}
}

</style>

<x-app-layout>
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5">

    <div class="container">
        <div class="row justify-content-center">

            <div class="col-lg-10">

                <div class="card login-card border-0 shadow-lg rounded-4 overflow-hidden">

                    <div class="row g-0 align-items-center">

                        <!-- Left Side -->
                        <div class="col-lg-6">

                            <div class="p-5">

                                <h2 class="fw-bold text-dark mb-2">
                                    Sign in
                                </h2>

                                <p class="text-secondary mb-4">
                                    Welcome back! Please enter your details.
                                </p>

                                <x-validation-errors class="mb-4" />

                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <div class="input-group">

                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-envelope text-secondary"></i>
                                            </span>

                                            <input
                                                id="login_email"
                                                type="email"
                                                name="email"
                                                value="{{ old('email') }}"
                                                class="form-control border-start-0 @error('email') is-invalid @enderror"
                                                placeholder="Email address"
                                                required
                                            >
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <div class="input-group">

                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-lock text-secondary"></i>
                                            </span>

                                            <input
                                                id="login_password"
                                                type="password"
                                                name="password"
                                                class="form-control border-start-0 @error('password') is-invalid @enderror"
                                                placeholder="Password"
                                                required
                                            >
                                        </div>
                                    </div>

                                    <!-- Remember + Forgot -->
                                    <div class="d-flex justify-content-between align-items-center mb-4 flex-column flex-sm-row gap-2 remember-row">

                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="remember"
                                                id="remember_me"
                                                checked
                                            >

                                            <label class="form-check-label" for="remember_me">
                                                Remember me
                                            </label>
                                        </div>

                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}"
                                               class="text-decoration-none forgot-password">
                                                Forgot password?
                                            </a>
                                        @endif
                                    </div>

                                    <!-- Button -->
                                    <button
                                        type="submit"
                                        class="btn btn-primary w-100 py-2 fw-semibold">
                                        SIGN IN TO ACCOUNT
                                    </button>

                                    <!-- Bottom -->
                                    <div class="text-center mt-4 text-secondary">

                                        Don't have an account?

                                        <a href="{{ url('/register') }}"
                                           class="text-decoration-none fw-semibold">
                                            Sign Up
                                        </a>

                                    </div>

                                    <!-- Demo Login Credentials -->
                                    <div class="border rounded-3 p-3 mb-4 bg-light mt-4">

                                        <!-- Demo Email -->
                                        <div class="d-flex justify-content-between align-items-center border rounded-3 px-3 py-2 mb-2 bg-white">

                                            <div>
                                                <small class="text-secondary fw-semibold">
                                                    USER:
                                                </small>

                                                <span id="demo_email" class="ms-2">
                                                    admin@admin.com
                                                </span>
                                            </div>

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary"
                                                onclick="copyText('demo_email','login_email', this)"
                                            >
                                                <i class="fas fa-copy"></i>
                                            </button>

                                        </div>

                                        <!-- Demo Password -->
                                        <div class="d-flex justify-content-between align-items-center border rounded-3 px-3 py-2 bg-white">

                                            <div>
                                                <small class="text-secondary fw-semibold">
                                                    PASS:
                                                </small>

                                                <span id="demo_password" class="ms-2">
                                                    admin
                                                </span>
                                            </div>

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary"
                                                onclick="copyText('demo_password','login_password', this)"
                                            >
                                                <i class="fas fa-copy"></i>
                                            </button>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                        <!-- Right Side -->
                       <div class="col-lg-6 d-none d-lg-flex login-hero">

    <div class="w-100 d-flex flex-column justify-content-center align-items-center text-center p-5 gap-4">

        <!-- Floating Card Wrapper -->
        <div class="w-100 d-flex flex-column align-items-center text-center p-4 rounded-4"
             style="animation: floatBox 3s ease-in-out infinite;">

            <!-- Logo -->
            <img
                src="{{ asset('images/logo/YessTrav.png') }}"
                class="img-fluid mb-3"
                style="width: 130px;"
            >

            <!-- Text -->
            <div class="mb-3">
                <h3 class="fw-bold text-dark fs-2 mb-2">
                    Yess Travel ERP System
                </h3>

                <p class="text-secondary mb-0">
                    Manage your travel ERP efficiently.
                </p>
            </div>

            <!-- Illustration -->
            <img
                src="{{ asset('images/icons/business-trip.png') }}"
                class="img-fluid mt-3 hero-illustration"
                style="width: 280px;"
            >

        </div>

    </div>

</div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

</x-app-layout>

<!-- SCRIPT -->
<script>

function copyText(sourceId, inputId, btn) {

    let text = document.getElementById(sourceId).innerText;

    // copy to clipboard
    navigator.clipboard.writeText(text);

    // auto fill input
    document.getElementById(inputId).value = text;

    // button UI change
    let original = btn.innerHTML;
    btn.innerHTML = "Copied!";
    btn.disabled = true;

    setTimeout(() => {
        btn.innerHTML = original;
        btn.disabled = false;
    }, 1500);
}

</script>
