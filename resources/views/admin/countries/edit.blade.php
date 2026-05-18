<x-admin-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Country: {{ $country->name }}</h5>
                    <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left"></i> Back to Countries
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.countries.update', $country->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Country Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $country->name) }}" maxlength="100" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="iso3" class="form-label">ISO3 Code</label>
                                    <input type="text" class="form-control @error('iso3') is-invalid @enderror" 
                                           id="iso3" name="iso3" value="{{ old('iso3', $country->iso3) }}" maxlength="3">
                                    @error('iso3')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="iso2" class="form-label">ISO2 Code</label>
                                    <input type="text" class="form-control @error('iso2') is-invalid @enderror" 
                                           id="iso2" name="iso2" value="{{ old('iso2', $country->iso2) }}" maxlength="2">
                                    @error('iso2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phonecode" class="form-label">Phone Code</label>
                                    <input type="text" class="form-control @error('phonecode') is-invalid @enderror" 
                                           id="phonecode" name="phonecode" value="{{ old('phonecode', $country->phonecode) }}" maxlength="20">
                                    @error('phonecode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="capital" class="form-label">Capital</label>
                                    <input type="text" class="form-control @error('capital') is-invalid @enderror" 
                                           id="capital" name="capital" value="{{ old('capital', $country->capital) }}">
                                    @error('capital')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Currency</label>
                                    <input type="text" class="form-control @error('currency') is-invalid @enderror" 
                                           id="currency" name="currency" value="{{ old('currency', $country->currency) }}" maxlength="10">
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="currency_symbol" class="form-label">Currency Symbol</label>
                                    <input type="text" class="form-control @error('currency_symbol') is-invalid @enderror" 
                                           id="currency_symbol" name="currency_symbol" value="{{ old('currency_symbol', $country->currency_symbol) }}" maxlength="10">
                                    @error('currency_symbol')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="region" class="form-label">Region</label>
                                    <input type="text" class="form-control @error('region') is-invalid @enderror" 
                                           id="region" name="region" value="{{ old('region', $country->region) }}">
                                    @error('region')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subregion" class="form-label">Sub Region</label>
                                    <input type="text" class="form-control @error('subregion') is-invalid @enderror" 
                                           id="subregion" name="subregion" value="{{ old('subregion', $country->subregion) }}">
                                    @error('subregion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emoji" class="form-label">Flag Emoji</label>
                                    <input type="text" class="form-control @error('emoji') is-invalid @enderror" 
                                           id="emoji" name="emoji" value="{{ old('emoji', $country->emoji) }}" maxlength="191">
                                    @error('emoji')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save"></i> Update Country
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>
