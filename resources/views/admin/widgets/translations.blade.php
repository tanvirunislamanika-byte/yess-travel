<x-admin-layout>

@section('content')
<div class="container-fluid mt-4 p-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Widget Translations</h3>
                    
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

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Widget Title</th>
                                    <th>Translations Languages</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($widgets as $widget)
                                <tr>
                                    <td>{{ $widget->id }}</td>
                                    <td>{{ $widget->title }}</td>
                                    <td>
                                        @foreach($languages as $language)
                                            @php
                                                $translation = $widget->translations->where('locale', $language->code)->first();
                                            @endphp
                                            <span class="badge bg-{{ $translation ? 'success' : 'warning' }}">
                                                {{ $language->name }} {{ $translation ? '✓' : '✗' }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.widget.translations.show', $widget->id) }}" 
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-language"></i> Manage Translations
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No widgets found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $widgets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>
