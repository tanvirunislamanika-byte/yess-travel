<x-admin-layout>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mt-4">Edit Language: {{ $language->name }}</h1>
            <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Languages
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-edit me-1"></i>
                Language Information
            </div>
            <div class="card-body">
                <form action="{{ route('admin.languages.update', $language) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Language Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $language->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Language Code *</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code', $language->code) }}" 
                                       placeholder="e.g., en, es, ar, fr" maxlength="5" required
                                       {{ $language->code === 'en' ? 'readonly' : '' }}>
                                <div class="form-text">
                                    @if($language->code === 'en')
                                        English language code cannot be changed (default language)
                                    @else
                                        Use ISO 639-1 language codes (2-5 characters)
                                    @endif
                                </div>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="flag" class="form-label">Flag Emoji (Optional)</label>
                                <input type="text" class="form-control @error('flag') is-invalid @enderror" 
                                       id="flag" name="flag" value="{{ old('flag', $language->flag) }}" 
                                       placeholder="🇺🇸, 🇪🇸, 🇸🇦" maxlength="10">
                                <div class="form-text">Enter flag emoji as fallback (optional)</div>
                                @error('flag')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="flag_image" class="form-label">Flag Image</label>
                                @if($language->flag_image)
                                    <div class="mb-2">
                                        <img src="{{ asset($language->flag_image) }}" alt="Current Flag" 
                                             class="img-thumbnail" style="max-width: 100px; max-height: 60px;">
                                        <br>
                                        <small class="text-muted">Current flag image</small>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('flag_image') is-invalid @enderror" 
                                       id="flag_image" name="flag_image" accept="image/*">
                                <div class="form-text">Upload new flag image (JPEG, PNG, JPG, GIF, SVG) - Max 2MB</div>
                                @error('flag_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" name="sort_order" value="{{ old('sort_order', $language->sort_order) }}" 
                                       min="0" step="1">
                                <div class="form-text">Lower numbers appear first</div>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Text Direction *</label>
                        <div class="form-check">
                            <input class="form-check-input @error('is_rtl') is-invalid @enderror" 
                                   type="radio" name="is_rtl" id="ltr" value="0" 
                                   {{ old('is_rtl', $language->is_rtl ? '1' : '0') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="ltr">
                                <strong>Left-to-Right (LTR)</strong> - For languages like English, Spanish, French
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input @error('is_rtl') is-invalid @enderror" 
                                   type="radio" name="is_rtl" id="rtl" value="1" 
                                   {{ old('is_rtl', $language->is_rtl ? '1' : '0') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="rtl">
                                <strong>Right-to-Left (RTL)</strong> - For languages like Arabic, Hebrew, Urdu
                            </label>
                        </div>
                        @error('is_rtl')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input @error('is_active') is-invalid @enderror" 
                                   type="checkbox" name="is_active" id="is_active" value="1" 
                                   {{ old('is_active', $language->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                                   {{ $language->code === 'en' ? 'disabled' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>Active</strong> - Language will be available for users
                                @if($language->code === 'en')
                                    <br><small class="text-muted">English language must always be active (default language)</small>
                                @endif
                            </label>
                            @error('is_active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Language
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
