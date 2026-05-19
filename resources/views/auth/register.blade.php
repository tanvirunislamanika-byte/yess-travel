<x-app-layout>

<div class="min-vh-100 d-flex align-items-center justify-content-center py-5">

    <div class="container">
        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                    <div class="row g-0 align-items-center">

                        <!-- LEFT SIDE (FORM) -->
                        <div class="col-lg-6">

                            <div class="p-5">

                                <h2 class="fw-bold text-dark mb-2">
                                    Create Account
                                </h2>

                                <p class="text-secondary mb-4">
                                    Sign up to start using your dashboard.
                                </p>

                                <x-validation-errors class="mb-4" />

                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <!-- Name -->
                                    <div class="mb-3">
                                        <div class="input-group">

                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-user text-secondary"></i>
                                            </span>

                                            <input
                                                type="text"
                                                name="name"
                                                value="{{ old('name') }}"
                                                class="form-control border-start-0 @error('name') is-invalid @enderror"
                                                placeholder="Your Name"
                                                required
                                            >
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <div class="input-group">

                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-envelope text-secondary"></i>
                                            </span>

                                            <input
                                                type="email"
                                                name="email"
                                                value="{{ old('email') }}"
                                                class="form-control border-start-0 @error('email') is-invalid @enderror"
                                                placeholder="Email Address"
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
                                                type="password"
                                                name="password"
                                                class="form-control border-start-0 @error('password') is-invalid @enderror"
                                                placeholder="Password"
                                                required
                                            >
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="mb-4">
                                        <div class="input-group">

                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-lock text-secondary"></i>
                                            </span>

                                            <input
                                                type="password"
                                                name="password_confirmation"
                                                class="form-control border-start-0 @error('password_confirmation') is-invalid @enderror"
                                                placeholder="Confirm Password"
                                                required
                                            >
                                        </div>
                                    </div>

                                    <!-- Button -->
                                    <button type="submit"
                                        class="btn btn-primary w-100 py-2 fw-semibold">
                                        CREATE ACCOUNT
                                    </button>

                                    <!-- Bottom -->
                                    <div class="text-center mt-4 text-secondary">
                                        Already have an account?
                                        <a href="{{ url('/login') }}" class="text-decoration-none fw-semibold">
                                            Login
                                        </a>
                                    </div>

                                </form>

                            </div>

                        </div>

                        <!-- RIGHT SIDE (FLOATING INFO) -->
                        <div class="col-lg-6 d-none d-lg-flex">

                            <div class="w-100 d-flex flex-column justify-content-center align-items-center text-center p-5 gap-4">

                                <div class=" rounded-4 p-4 w-100 text-center"
                                     style="animation: floatBox 3s ease-in-out infinite;">

                                    <img
                                        src="{{ asset('images/logo/YessTrav.png') }}"
                                        class="img-fluid mb-3"
                                        style="width: 110px;"
                                    >

                                    <h3 class="fw-bold text-dark fs-3 mb-2">
                                        Yess Travel ERP System
                                    </h3>

                                    <p class="text-secondary mb-4">
                                        Join & manage your garments operations efficiently.
                                    </p>

                                    <img
                                        src="{{ asset('images/icons/business-trip.png') }}"
                                        class="img-fluid"
                                        style="width: 170px;"
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

<!-- FLOAT ANIMATION -->
<style>
@keyframes floatBox {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-12px); }
    100% { transform: translateY(0px); }
}
</style>

</x-app-layout>