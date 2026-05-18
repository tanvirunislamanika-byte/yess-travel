<x-admin-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Currency Settings</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Currency Settings</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Default Currency</h4>
                        <p class="text-muted mb-0">Configure your default currency for tours and hotels</p>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.currency-settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="default_currency" class="form-label">Select Default Currency</label>
                                        <select class="form-select @error('default_currency') is-invalid @enderror" 
                                                id="default_currency" name="default_currency" required>
                                            <option value="">Choose Currency</option>
                                            @foreach($countries as $country)
                                                @php
                                                    $selected = false;
                                                    if (isset($settings['default_currency'])) {
                                                        $selected = $settings['default_currency']->value == $country->currency;
                                                    } else {
                                                        $selected = $country->currency == 'PKR';
                                                    }
                                                @endphp
                                                <option value="{{ $country->id }}" {{ $selected ? 'selected' : '' }}>
                                                    {{ $country->name }} ({{ $country->currency }} - {{ $country->currency_symbol }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('default_currency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            This currency will be used throughout the site for displaying prices in tours and hotels
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Update Currency
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Currency Display -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Current Currency Settings</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="current-currency-display">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="currency-icon me-3">
                                            <i class="fas fa-money-bill-wave fa-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-1">{{ $settings['default_currency_name']->value ?? 'Pakistani Rupee' }}</h5>
                                            <p class="text-muted mb-0">
                                                <strong>Code:</strong> {{ $settings['default_currency']->value ?? 'PKR' }} |
                                                <strong>Symbol:</strong> {{ $settings['default_currency_symbol']->value ?? 'Rs' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="currency-preview">
                                        <small class="text-muted">Price Display Preview:</small>
                                        <div class="preview-examples mt-2">
                                            <div class="badge bg-light text-dark me-2">
                                                {{ $settings['default_currency_symbol']->value ?? 'Rs' }} 15,000
                                            </div>
                                            <div class="badge bg-light text-dark me-2">
                                                {{ $settings['default_currency_symbol']->value ?? 'Rs' }} 25,500
                                            </div>
                                            <div class="badge bg-light text-dark">
                                                {{ $settings['default_currency_symbol']->value ?? 'Rs' }} 50,000
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <h6><i class="fas fa-info-circle text-info"></i> How It Works</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success"></i> Tours will display prices in selected currency</li>
                                        <li><i class="fas fa-check text-success"></i> Hotels will display prices in selected currency</li>
                                        <li><i class="fas fa-check text-success"></i> All price displays will use the currency symbol</li>
                                        <li><i class="fas fa-check text-success"></i> Changes apply immediately across the site</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .current-currency-display {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid var(--bs-primary);
        }
        
        .currency-preview .preview-examples {
            font-size: 1.1rem;
        }
        
        .info-box {
            background: #e8f4fd;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #0dcaf0;
        }
        
        .info-box ul li {
            padding: 5px 0;
        }
        
        .info-box ul li i {
            width: 20px;
        }
    </style>
</x-admin-layout>