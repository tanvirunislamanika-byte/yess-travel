
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
</style>

<x-app-layout>
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5">

    <div class="container">
        <div class="row justify-content-center">

            <div class="col-lg-10">

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

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
                                    <div class="d-flex justify-content-between align-items-center mb-4">

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
                                               class="text-decoration-none">
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
                       <div class="col-lg-6 d-none d-lg-flex">

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
                class="img-fluid mt-3"
                style="width: 180px;"
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
