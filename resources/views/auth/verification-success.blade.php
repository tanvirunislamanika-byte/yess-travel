@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-check text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h2 class="text-success mb-3">Email Verified Successfully!</h2>
                        <p class="text-muted mb-4">Your email address has been verified. You can now access all features of your account.</p>
                    </div>

                    <div class="d-grid gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Go to Dashboard
                        </a>
                        
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Login to Your Account
                        </a>
                    </div>

                    <div class="mt-4">
                        <p class="text-muted small">
                            You will be automatically redirected to the dashboard in <span id="countdown">5</span> seconds.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let seconds = 5;
    const countdown = document.getElementById('countdown');
    
    const timer = setInterval(() => {
        seconds--;
        countdown.textContent = seconds;
        
        if (seconds <= 0) {
            clearInterval(timer);
            window.location.href = '{{ route("dashboard") }}';
        }
    }, 1000);
});
</script>
@endsection
