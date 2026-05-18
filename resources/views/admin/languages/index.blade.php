<x-admin-layout>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Language Management</h1>
        
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

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-language me-1"></i>
                    Languages
                </div>
                <div>
                    <a href="{{ route('admin.languages.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Add New Language
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Flag</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Direction</th>
                                <th>Status</th>
                                <th>Sort Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($languages as $language)
                                <tr>
                                    <td>{{ $language->id }}</td>
                                    <td>
                                        @if($language->flag_image)
                                            <img src="{{ asset($language->flag_image) }}" alt="{{ $language->name }} Flag" 
                                                 class="language-flag-image" style="max-width: 40px; max-height: 25px;">
                                        @else
                                            <span class="language-flag">{{ $language->flag ?: '🌐' }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $language->name }}</td>
                                    <td><code>{{ $language->code }}</code></td>
                                    <td>
                                        @if($language->is_rtl)
                                            <span class="badge bg-info">RTL</span>
                                        @else
                                            <span class="badge bg-secondary">LTR</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($language->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $language->sort_order }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.languages.translations', $language) }}" 
                                               class="btn btn-info btn-sm" title="Manage Translations">
                                                <i class="fas fa-language"></i>
                                            </a>
                                            <a href="{{ route('admin.languages.edit', $language) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($language->code !== 'en')
                                                <form action="{{ route('admin.languages.destroy', $language) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this language?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No languages found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
    .language-flag {
        font-size: 1.5rem;
    }
    .language-flag-image {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        object-fit: cover;
    }
    </style>
</x-admin-layout>
