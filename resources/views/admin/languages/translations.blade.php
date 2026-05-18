<x-admin-layout>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mt-4">Manage Translations: {{ $language->name }}</h1>
            <div>
                <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i> Back to Languages
                </a>
                <a href="{{ route('admin.languages.export', [$language, 'frontend']) }}" class="btn btn-success me-2">
                    <i class="fas fa-download me-1"></i> Export
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Import Section -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-upload me-1"></i>
                Import Translations
            </div>
            <div class="card-body">
                <form action="{{ route('admin.languages.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="language_code" value="{{ $language->code }}">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="language_file" class="form-label">JSON Translation File</label>
                                <input type="file" class="form-control @error('language_file') is-invalid @enderror" 
                                       id="language_file" name="language_file" accept=".json" required>
                                <div class="form-text">Upload a JSON file with key-value pairs for translations</div>
                                @error('language_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-upload me-1"></i> Import
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Translations Form -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-language me-1"></i>
                Frontend Translations
            </div>
            <div class="card-body">
                <form action="{{ route('admin.languages.updateTranslations', $language) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Language: {{ $language->name }} ({{ $language->code }})</h5>
                            <p class="text-muted">
                                @if($language->is_rtl)
                                    <span class="badge bg-info">RTL Language</span>
                                @else
                                    <span class="badge bg-secondary">LTR Language</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save All Translations
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 30%;">Translation Key</th>
                                    <th style="width: 70%;">Translation Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($keys as $key)
                                    <tr>
                                        <td>
                                            <strong>{{ $key }}</strong>
                                            <br>
                                            <small class="text-muted">Key: <code>{{ $key }}</code></small>
                                        </td>
                                        <td>
                                            <textarea class="form-control @error('translations.' . $key) is-invalid @enderror" 
                                                      name="translations[{{ $key }}]" rows="2" 
                                                      placeholder="Enter translation for '{{ $key }}'">{{ $translations->get($key)->first()->value ?? '' }}</textarea>
                                            @error('translations.' . $key)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            <p class="text-muted my-3">No translation keys found.</p>
                                            <p class="text-muted">Add some translations to get started.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($keys->count() > 0)
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save All Translations
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Sample JSON Format -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle me-1"></i>
                JSON Import Format
            </div>
            <div class="card-body">
                <p>Use this format for your JSON import file:</p>
                <pre class="bg-light p-3 rounded"><code>{
    "home": "Home",
    "dashboard": "Dashboard",
    "login": "Login",
    "register": "Register",
    "book_now": "Book Now",
    "tour_details": "Tour Details"
}</code></pre>
                <p class="text-muted mb-0">
                    <strong>Note:</strong> The system will automatically create or update translations based on the keys in your JSON file.
                </p>
            </div>
        </div>
    </div>
</x-admin-layout>
