<x-app-layout>
    <style>
        
    </style>

    <div class="password-change-container">
        <div class="container">
            <!-- Back to Dashboard Button -->
            <div class="password-back-button-container text-center">
                <a href="{{ route('dashboard') }}" class="password-btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('frontend.back_to_dashboard') }}
                </a>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="password-change-card">
                        <!-- Password Change Header -->
                        <div class="password-change-header">
                            <div class="password-change-icon">
                                <i class="fas fa-key"></i>
                            </div>
                            <h1 class="password-change-title">{{ __('frontend.change_password') }}</h1>
                            <p class="password-change-subtitle">{{ __('frontend.update_password_to_keep_secure') }}</p>
                        </div>

                        <!-- Password Change Body -->
                        <div class="password-change-body">
                            @if(session('success'))
                                <div class="password-alert password-alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            @if($errors->any())
                                <div class="password-alert password-alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <div class="row">
                                <div class="col-md-5">
                                    <!-- Password Requirements -->
                            <div class="password-requirements">
                                <h6><i class="fas fa-info-circle"></i>{{ __('frontend.password_requirements') }}</h6>
                                <ul>
                                    <li><i class="fas fa-check"></i>{{ __('frontend.password_rule_8_chars') }}</li>
                                    <li><i class="fas fa-check"></i>{{ __('frontend.password_rule_one_letter') }}</li>
                                    <li><i class="fas fa-check"></i>{{ __('frontend.password_rule_one_number') }}</li>
                                    <li><i class="fas fa-check"></i>{{ __('frontend.password_rule_one_symbol') }}</li>
                                </ul>
                            </div>
                                </div>
                                <div class="col-md-7">
                                <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                
                                <h5 class="password-section-title">
                                    <i class="fas fa-lock"></i>
                                    {{ __('frontend.update_password') }}
                                </h5>
                                
                                <!-- Current Password -->
                                <div class="password-form-group">
                                    <label for="current_password" class="password-form-label">{{ __('frontend.current_password') }} *</label>
                                    <input type="password" 
                                           class="password-form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password" 
                                           required
                                           placeholder="{{ __('frontend.enter_current_password') }}">
                                    @error('current_password')
                                        <div class="password-invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- New Password -->
                                <div class="password-form-group">
                                    <label for="password" class="password-form-label">{{ __('frontend.new_password') }} *</label>
                                    <input type="password" 
                                           class="password-form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required
                                           placeholder="{{ __('frontend.enter_new_password') }}">
                                    @error('password')
                                        <div class="password-invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Confirm New Password -->
                                <div class="password-form-group">
                                    <label for="password_confirmation" class="password-form-label">{{ __('frontend.confirm_new_password') }} *</label>
                                    <input type="password" 
                                           class="password-form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required
                                           placeholder="{{ __('frontend.confirm_new_password_placeholder') }}">
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="password-form-group">
                                    <button type="submit" class="password-btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>{{ __('frontend.change_password') }}
                                    </button>
                                </div>
                            </form>
                                </div>
                            </div>
                            
                            
                         



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
