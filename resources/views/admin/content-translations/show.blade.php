<x-admin-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Translations for: {{ $item->title }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.content.translations.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('message.added'))
                            <div class="alert alert-{{ session('message.added') }} alert-dismissible fade show" role="alert">
                                {{ session('message.content') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Original Content -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Original Content (English)</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @if(!empty($availableFields))
                                                @foreach($availableFields as $fieldKey => $fieldLabel)
                                                    <div class="col-md-6 mb-3">
                                                        <strong>{{ ucfirst(str_replace('_', ' ', $fieldKey)) }}:</strong><br>
                                                        @if(in_array($fieldKey, ['description', 'meta_description']))
                                                            <div class="border p-2 bg-white" style="max-height: 100px; overflow-y: auto;">
                                                                {{ $item->$fieldKey }}
                                                            </div>
                                                        @else
                                                            <div class="border p-2 bg-white">
                                                                {{ $item->$fieldKey }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-12">
                                                    <p class="text-muted">No content available for this item.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add New Translation Form -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Add New Translation</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.content.translations.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="translatable_id" value="{{ $item->id }}">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="locale">Language</label>
                                                        <select name="locale" id="locale" class="form-control" required>
                                                            <option value="">Select Language</option>
                                                            @foreach($languages as $language)
                                                                @if($language->code !== 'en')
                                                                    <option value="{{ $language->code }}">{{ $language->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="field_name">Field</label>
                                                        <select name="field_name" id="field_name" class="form-control" required onchange="showFieldPreview()">
                                                            <option value="">Select Field</option>
                                                            @if(!empty($availableFields))
                                                                @foreach($availableFields as $fieldKey => $fieldLabel)
                                                                    <option value="{{ $fieldKey }}" data-value="{{ $item->$fieldKey ?? '' }}">{{ $fieldLabel }}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="" disabled>No fields available for this item</option>
                                                            @endif
                                                        </select>
                                                        <small id="field_preview" class="form-text text-muted" style="display: none;"></small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="field_value">Translation</label>
                                                        <input type="text" name="field_value" id="field_value" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="btn btn-primary btn-block">Add Translation</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Existing Translations -->
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Existing Translations</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Language</th>
                                                <th>Field</th>
                                                <th>Translation</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($item->translations as $translation)
                                                <tr>
                                                    <td>
                                                        @php
                                                            $language = $languages->where('code', $translation->locale)->first();
                                                        @endphp
                                                        {{ $language ? $language->name : $translation->locale }}
                                                    </td>
                                                    <td>{{ ucfirst(str_replace('_', ' ', $translation->field_name)) }}</td>
                                                    <td>
                                                        <form action="{{ route('admin.content.translations.update') }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $translation->id }}">
                                                            <div class="input-group">
                                                                @if($translation->field_name === 'description' || $translation->field_name === 'meta_description')
                                                                    <textarea name="field_value" class="form-control form-control-sm" rows="2">{{ $translation->field_value }}</textarea>
                                                                @else
                                                                    <input type="text" name="field_value" value="{{ $translation->field_value }}" class="form-control form-control-sm">
                                                                @endif
                                                                <div class="input-group-append">
                                                                    <button type="submit" class="btn btn-success btn-sm">
                                                                        <i class="fas fa-save"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.content.translations.delete', $translation->id) }}" 
                                                           class="btn btn-danger btn-sm"
                                                           onclick="return confirm('Are you sure you want to delete this translation?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
function showFieldPreview() {
    const select = document.getElementById('field_name');
    const preview = document.getElementById('field_preview');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        const currentValue = selectedOption.getAttribute('data-value');
        if (currentValue) {
            preview.textContent = 'Current value: ' + currentValue.substring(0, 100) + (currentValue.length > 100 ? '...' : '');
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    } else {
        preview.style.display = 'none';
    }
}
</script>
</x-admin-layout>
