<x-app-layout>

@section('content')
<div class="innerpagewrap">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                
                <div class="card-body p-4">
                <div class="account-title text-center mb-3">
                    <h3>Check your email</h3>
                </div>
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-envelope text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="mb-2">Enter the verification code sent to</h5>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
        </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
            </div>
        @endif

                    <form method="POST" action="{{ route('verification.verify') }}" id="verificationForm">
                @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        
                        <div class="verification-inputs text-center mb-4">
                            <div class="d-flex justify-content-center gap-2">
                                @for ($i = 1; $i <= 6; $i++)
                                    <input type="text" 
                                           class="form-control verification-digit text-center" 
                                           name="code[]" 
                                           maxlength="1" 
                                           data-index="{{ $i }}"
                                           style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold;"
                                           autocomplete="off">
                                @endfor
                            </div>
                            <div class="text-danger mt-2" id="codeError" style="display: none;"></div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg" id="verifyBtn" disabled>
                                Verify Email
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="text-muted mb-2">Didn't get a code?</p>
                            <button type="button" class="btn btn-link p-0" id="resendBtn">
                                Resend
                            </button>
                            <div class="text-muted small mt-1" id="resendTimer" style="display: none;">
                                Resend available in <span id="countdown">60</span> seconds
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<style>
.verification-digit:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.verification-digit.filled {
    background-color: #e3f2fd;
    border-color: #2196f3;
}

.verification-digit.error {
    border-color: #dc3545;
    background-color: #fff5f5;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.verification-digit');
    const form = document.getElementById('verificationForm');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendBtn = document.getElementById('resendBtn');
    const resendTimer = document.getElementById('resendTimer');
    const countdown = document.getElementById('countdown');
    const codeError = document.getElementById('codeError');

    let countdownInterval;

    // Auto-focus and auto-tab functionality
    inputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const value = e.target.value;
            
            // Only allow numbers
            if (!/^\d*$/.test(value)) {
                e.target.value = '';
                return;
            }

            if (value.length === 1) {
                e.target.classList.add('filled');
                e.target.classList.remove('error');
                
                // Move to next input
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            } else {
                e.target.classList.remove('filled');
            }

            checkFormCompletion();
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text');
            const numbers = pastedData.replace(/\D/g, '').slice(0, 6);
            
            inputs.forEach((input, i) => {
                if (i < numbers.length) {
                    input.value = numbers[i];
                    input.classList.add('filled');
                }
            });
            
            checkFormCompletion();
        });
    });

    function checkFormCompletion() {
        const filledInputs = Array.from(inputs).filter(input => input.value.length === 1);
        verifyBtn.disabled = filledInputs.length !== 6;
        
        if (filledInputs.length === 6) {
            codeError.style.display = 'none';
        }
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const code = Array.from(inputs).map(input => input.value).join('');
        
        if (code.length !== 6) {
            showError('Please enter the complete 6-digit code');
            return;
        }

        // Disable button and show loading
        verifyBtn.disabled = true;
        verifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verifying...';

        // Submit form
        fetch('{{ route("verification.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: '{{ $user->id }}',
                code: code
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message and redirect
                showSuccess(data.message);
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 2000);
            } else {
                showError(data.message);
                verifyBtn.disabled = false;
                verifyBtn.innerHTML = 'Verify Email';
            }
        })
        .catch(error => {
            showError('An error occurred. Please try again.');
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = 'Verify Email';
        });
    });

    // Resend functionality
    resendBtn.addEventListener('click', function() {
        resendBtn.disabled = true;
        resendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
        
        fetch('{{ route("verification.resend") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: '{{ $user->id }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Verification code sent successfully!');
                startResendTimer();
            } else {
                showError(data.message);
                resendBtn.disabled = false;
                resendBtn.innerHTML = 'Resend';
            }
        })
        .catch(error => {
            showError('An error occurred. Please try again.');
            resendBtn.disabled = false;
            resendBtn.innerHTML = 'Resend';
        });
    });

    function startResendTimer() {
        let seconds = 60;
        resendTimer.style.display = 'block';
        resendBtn.style.display = 'none';
        
        countdownInterval = setInterval(() => {
            seconds--;
            countdown.textContent = seconds;
            
            if (seconds <= 0) {
                clearInterval(countdownInterval);
                resendTimer.style.display = 'none';
                resendBtn.style.display = 'inline-block';
                resendBtn.disabled = false;
                resendBtn.innerHTML = 'Resend';
            }
        }, 1000);
    }

    function showError(message) {
        codeError.textContent = message;
        codeError.style.display = 'block';
        inputs.forEach(input => input.classList.add('error'));
    }

    function showSuccess(message) {
        // Create success alert
        const alert = document.createElement('div');
        alert.className = 'alert alert-success';
        alert.innerHTML = message;
        
        const cardBody = document.querySelector('.card-body');
        cardBody.insertBefore(alert, cardBody.firstChild);
    }

    // Start resend timer on page load
    startResendTimer();
});
</script>
</x-app-layout>
