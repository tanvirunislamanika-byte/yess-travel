<x-app-layout>

    <div class="user-profile-container">
        <div class="container">
            <!-- Back to Dashboard Button -->
            <div class="user-back-button-container text-center">
                <a href="{{ route('dashboard') }}" class="user-btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('frontend.back_to_dashboard') }}
                </a>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="user-profile-card">
                        <!-- Profile Header -->
                        <div class="user-profile-header">
                            <div class="user-profile-avatar">
                                @if(auth()->user()->profile_photo_path)
                                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <h1 class="user-profile-name">{{ auth()->user()->name }}</h1>
                            <div class="user-profile-info">
                                @if(auth()->user()->mobile)
                                    <div class="user-profile-info-item">
                                        <i class="fas fa-phone-alt"></i>
                                        <span>{{ auth()->user()->mobile }}</span>
                                    </div>
                                @endif
                                @if(auth()->user()->countryData || auth()->user()->stateData || auth()->user()->cityData)
                                    <div class="user-profile-info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>
                                            @if(auth()->user()->cityData)
                                                {{ auth()->user()->cityData->name }},
                                            @endif
                                            @if(auth()->user()->stateData)
                                                {{ auth()->user()->stateData->name }},
                                            @endif
                                            @if(auth()->user()->countryData)
                                                {{ auth()->user()->countryData->name }}
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Profile Body -->
                        <div class="user-profile-body">
                            @if(session('success'))
                                <div class="user-alert user-alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            @if($errors->any())
                                <div class="user-alert user-alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- Profile Photo Section -->
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <div class="user-photo-upload-section">
                                        <h5 class="user-section-title">
                                            <i class="fas fa-camera"></i>
                                            {{ __('frontend.profile_photo') }}
                                        </h5>
                                        <div class="user-photo-preview">
                                            <div class="user-photo-input-wrapper">
                                                <label class="user-form-label">{{ __('frontend.upload_new_photo') }}</label>
                                                <input type="file" 
                                                       class="user-form-control" 
                                                       name="photo" 
                                                       accept="image/*">
                                                <small class="user-text-muted">{{ __('frontend.photo_upload_hint') }}</small>
                                                @error('photo')
                                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        @if (auth()->user()->profile_photo_path)
                                            <button type="button" class="user-btn-outline-danger" onclick="deleteProfilePhoto()">
                                                <i class="fas fa-trash me-2"></i>{{ __('frontend.remove_photo') }}
                                            </button>
                                        @endif
                                    </div>
                                @endif
                                
                                <!-- Personal Information -->
                                <h5 class="user-section-title">
                                    <i class="fas fa-user"></i>
                                    {{ __('frontend.personal_information') }}
                                </h5>
                                
                                <div class="user-form-group">
                                    <label for="name" class="user-form-label">{{ __('frontend.full_name') }} *</label>
                                    <input type="text" 
                                           class="user-form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', auth()->user()->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="user-invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="user-form-group">
                                    <label for="email" class="user-form-label">{{ __('frontend.email_address') }} *</label>
                                    <input type="email" 
                                           class="user-form-control" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', auth()->user()->email) }}" 
                                           readonly
                                           required>
                                    <small class="user-text-muted">{{ __('frontend.email_cannot_be_changed') }}</small>
                                </div>
                                
                                <!-- Contact Information -->
                                <h5 class="user-section-title">
                                    <i class="fas fa-phone"></i>
                                    {{ __('frontend.contact_information') }}
                                </h5>
                                
                                <div class="user-form-group">
                                    <label class="user-form-label">{{ __('frontend.mobile_number') }}</label>
                                    <div class="user-phone-input-group">
                                        @php
                                            // Parse current mobile number to extract country code and local number
                                            $currentMobile = $user->mobile ?? '';
                                            $currentCountryCode = '';
                                            $currentLocalNumber = '';
                                            
                                            if ($currentMobile && strpos($currentMobile, '+') === 0) {
                                                // Format: +92 300-123-4567
                                                $parts = explode(' ', $currentMobile, 2);
                                                if (count($parts) == 2) {
                                                    $currentCountryCode = ltrim($parts[0], '+');
                                                    $currentLocalNumber = $parts[1];
                                                }
                                            }
                                        @endphp
                                        <select class="user-form-control @error('country_code') is-invalid @enderror" 
                                                id="country_code" 
                                                name="country_code">
                                            <option value="">{{ __('frontend.select_country_code') }}</option>
                                            @foreach($countries as $id => $name)
                                                @php
                                                    $country = \App\Models\Country::find($id);
                                                    $phoneCode = $country ? $country->phonecode : '';
                                                @endphp
                                                <option value="{{ $phoneCode }}" {{ old('country_code', $currentCountryCode) == $phoneCode ? 'selected' : '' }}>
                                                    {{ $name }} ({{ $phoneCode ? '+' . $phoneCode : 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="text" 
                                               class="user-form-control @error('mobile') is-invalid @enderror" 
                                               id="mobile" 
                                               name="mobile" 
                                               value="{{ old('mobile', $currentLocalNumber) }}"
                                               placeholder="{{ __('frontend.enter_phone_number') }}">
                                    </div>
                                    @error('country_code')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                    @error('mobile')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                    <small class="user-text-muted">{{ __('frontend.country_code_phone_hint') }}</small>
                                </div>

                                <!-- Location Information -->
                                <h5 class="user-section-title">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ __('frontend.location_information') }}
                                </h5>
                                
                                <div class="user-location-grid">
                                    <div class="user-form-group">
                                        <label for="country" class="user-form-label">{{ __('frontend.country') }}</label>
                                        <select class="user-form-control @error('country') is-invalid @enderror" 
                                                id="country" 
                                                name="country">
                                            <option value="">{{ __('frontend.select_country') }}</option>
                                            @foreach($countries as $id => $name)
                                                <option value="{{ $id }}" {{ old('country', auth()->user()->country) == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('country')
                                            <div class="user-invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="user-form-group">
                                        <label for="state" class="user-form-label">{{ __('frontend.state_province') }}</label>
                                        <select class="user-form-control @error('state') is-invalid @enderror" 
                                                id="state" 
                                                name="state"
                                                {{ empty($states) ? 'disabled' : '' }}>
                                            <option value="">{{ __('frontend.select_state_province') }}</option>
                                            @foreach($states as $id => $name)
                                                <option value="{{ $id }}" {{ old('state', auth()->user()->state) == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('state')
                                            <div class="user-invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="user-form-group">
                                        <label for="city" class="user-form-label">{{ __('frontend.city') }}</label>
                                        <select class="user-form-control @error('city') is-invalid @enderror" 
                                                id="city" 
                                                name="city"
                                                {{ empty($cities) ? 'disabled' : '' }}>
                                            <option value="">{{ __('frontend.select_city') }}</option>
                                            @foreach($cities as $id => $name)
                                                <option value="{{ $id }}" {{ old('city', auth()->user()->city) == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('city')
                                            <div class="user-invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="user-form-group text-center">
                                    <button type="submit" class="user-btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>{{ __('frontend.update_profile') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Dependent Dropdowns -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // Function to delete profile photo
    function deleteProfilePhoto() {
        if (confirm('Are you sure you want to remove your profile photo?')) {
            $.ajax({
                url: '{{ route("profile.photo.delete") }}',
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Reload the page to show updated photo
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error deleting profile photo. Please try again.');
                }
            });
        }
    }

    $(document).ready(function() {
        // Base URL for AJAX calls
        var baseUrl = '{{ url("/") }}';
        console.log('Base URL:', baseUrl);
        
        // Setup CSRF token for AJAX calls
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Add search functionality to country code dropdown
        $('#country_code').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $(this).find('option').each(function() {
                var text = $(this).text().toLowerCase();
                if (text.indexOf(value) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        // Auto-format phone number input
        $('#mobile').on('input', function() {
            var value = $(this).val().replace(/\D/g, ''); // Remove non-digits
            if (value.length > 0) {
                // Format as XXX-XXX-XXXX or similar
                if (value.length <= 3) {
                    value = value;
                } else if (value.length <= 6) {
                    value = value.slice(0, 3) + '-' + value.slice(3);
                } else {
                    value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
                }
            }
            $(this).val(value);
        });
        
        // Handle country change
        $('#country').on('change', function() {
            var countryId = $(this).val();
            var $stateSelect = $('#state');
            var $citySelect = $('#city');
            
            console.log('Country changed to:', countryId);
            
            // Reset and disable state and city dropdowns
            $stateSelect.html('<option value="">Select State/Province</option>').prop('disabled', true);
            $citySelect.html('<option value="">Select City</option>').prop('disabled', true);
            
            if (countryId) {
                console.log('Fetching states for country:', countryId);
                // Fetch states for selected country
                $.ajax({
                    url: baseUrl + '/profile/states',
                    method: 'GET',
                    data: { country_id: countryId },
                    dataType: 'json',
                    success: function(data) {
                        console.log('States received:', data);
                        $stateSelect.prop('disabled', false);
                        $.each(data, function(index, state) {
                            $stateSelect.append('<option value="' + state.id + '">' + state.name + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching states:', error);
                        console.error('Response:', xhr.responseText);
                        alert('Error loading states. Please try again.');
                    }
                });
            }
        });
        
        // Handle state change
        $('#state').on('change', function() {
            var stateId = $(this).val();
            var $citySelect = $('#city');
            
            console.log('State changed to:', stateId);
            
            // Reset and disable city dropdown
            $citySelect.html('<option value="">Select City</option>').prop('disabled', true);
            
            if (stateId) {
                console.log('Fetching cities for state:', stateId);
                // Fetch cities for selected state
                $.ajax({
                    url: baseUrl + '/profile/cities',
                    method: 'GET',
                    data: { state_id: stateId },
                    dataType: 'json',
                    success: function(data) {
                        console.log('Cities received:', data);
                        $citySelect.prop('disabled', false);
                        $.each(data, function(index, city) {
                            $citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching cities:', error);
                        console.error('Response:', xhr.responseText);
                        alert('Error loading cities. Please try again.');
                    }
                });
            }
        });
    });
    </script>
</x-app-layout>
