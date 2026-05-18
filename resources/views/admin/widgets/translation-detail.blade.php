<x-admin-layout>

@section('content')
<div class="container-fluid mt-4 p-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Widget Data Translation Management - {{ $widget->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.widget.translations.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif



                    <!-- Widget Data Translations -->
                    @if($widget->widget_data)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Translate Widget Content</h4>
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.widget.data.translations.store') }}">
                                        @csrf
                                        <input type="hidden" name="widget_data_id" value="{{ $widget->widget_data->id }}">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="data_locale">Language</label>
                                                    <select name="locale" id="data_locale" class="form-control" required>
                                                        <option value="">Select Language</option>
                                                        @foreach($languages as $language)
                                                            <option value="{{ $language->code }}">{{ $language->name }}</option>
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
                                                                <option value="{{ $fieldKey }}" data-value="{{ $widget->widget_data->$fieldKey ?? '' }}">{{ $fieldLabel }}</option>
                                                            @endforeach
                                                        @else
                                                            <option value="" disabled>No fields available for this widget</option>
                                                        @endif
                                                    </select>
                                                    <small id="field_preview" class="form-text text-muted" style="display: none;"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="field_value">Translated Value</label>
                                                    <textarea name="field_value" id="field_value" class="form-control" 
                                                              rows="1" placeholder="Enter translated value" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group d-block">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary btn-block">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Widget Data Translations -->
                    <div class="row">
                        <div class="col-12">
                            <h5>Existing Translations</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Language</th>
                                            <th>Field</th>
                                            <th>Value</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($widget->widget_data->translations as $translation)
                                        <tr>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $translation->locale }}
                                                </span>
                                            </td>
                                            <td>{{ $translation->field_name }}</td>
                                            <td>{{ Str::limit($translation->field_value, 50) }}</td>
                                            <td>
                                                <button type="button" class="btn btn-outline-warning btn-sm" 
                                                        onclick="editWidgetDataTranslation({{ $translation->id }}, '{{ $translation->field_value }}')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="{{ route('admin.widget.data.translations.delete', $translation->id) }}" 
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Are you sure you want to delete this translation?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No translations found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Edit Widget Data Translation Modal -->
<div class="modal fade" id="editWidgetDataTranslationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Widget Data Translation</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.widget.data.translations.update') }}">
                @csrf
                <input type="hidden" name="translation_id" id="edit_widget_data_translation_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_widget_data_value">Value</label>
                        <textarea name="field_value" id="edit_widget_data_value" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Translation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>


function editWidgetDataTranslation(id, value) {
    document.getElementById('edit_widget_data_translation_id').value = id;
    document.getElementById('edit_widget_data_value').value = value;
    $('#editWidgetDataTranslationModal').modal('show');
}

// Widget data for showing current values
const widgetData = @json($widget->widget_data);

function showCurrentValue() {
    const fieldName = document.getElementById('field_name').value;
    if (!fieldName) {
        alert('Please select a field first');
        return;
    }
    
    const currentValue = widgetData[fieldName] || 'No value set';
    alert('Current value for ' + fieldName + ':\n\n' + currentValue);
}

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
