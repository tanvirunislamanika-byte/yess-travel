<x-admin-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Content Translations - {{ $module->name }}</h3>
                        <div class="card-tools">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    Switch Module
                                </button>
                                <div class="dropdown-menu">
                                    @foreach($modules as $mod)
                                        <a class="dropdown-item" href="{{ route('admin.content.translations.index', ['module' => $mod->slug]) }}">
                                            {{ $mod->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
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

                        <!-- Bulk Translation Form -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Bulk Translation Setup</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.content.translations.bulk') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="module_id" value="{{ $module->id }}">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="target_locale">Target Language</label>
                                                        <select name="target_locale" id="target_locale" class="form-control" required>
                                                            <option value="">Select Language</option>
                                                            @foreach($languages as $language)
                                                                @if($language->code !== 'en')
                                                                    <option value="{{ $language->code }}">{{ $language->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Fields to Translate</label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="fields[]" value="title" id="field_title" checked>
                                                            <label class="form-check-label" for="field_title">Title</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="fields[]" value="description" id="field_description" checked>
                                                            <label class="form-check-label" for="field_description">Description</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="fields[]" value="meta_title" id="field_meta_title">
                                                            <label class="form-check-label" for="field_meta_title">Meta Title</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="fields[]" value="meta_description" id="field_meta_description">
                                                            <label class="form-check-label" for="field_meta_description">Meta Description</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="fields[]" value="meta_keywords" id="field_meta_keywords">
                                                            <label class="form-check-label" for="field_meta_keywords">Meta Keywords</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="fields[]" value="extra_fields" id="field_extra_fields">
                                                            <label class="form-check-label" for="field_extra_fields">Extra Fields (1-50)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="btn btn-primary btn-block">Create Translation Placeholders</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Items Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Translations</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>
                                                @foreach($languages as $language)
                                                    @if($language->code !== 'en')
                                                        @php
                                                            $translation = $item->translations->where('locale', $language->code)->first();
                                                        @endphp
                                                        <span class="badge bg-{{ $translation ? 'success' : 'secondary' }} mr-1">
                                                            {{ $language->name }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.content.translations.show', $item->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-edit"></i> Manage Translations
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
