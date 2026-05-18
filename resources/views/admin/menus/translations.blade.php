<x-admin-layout>   

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Menu Translations</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.menus') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Menus
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

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Add New Translation</h5>
                            <form action="{{ route('admin.menus.translations.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="menu_id">Menu</label>
                                    <select name="menu_id" id="menu_id" class="form-control" required>
                                        <option value="">Select Menu</option>
                                        @foreach($menus as $menu)
                                            <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                                <div class="form-group">
                                    <label for="title">Translated Title</label>
                                    <input type="text" name="title" id="title" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Translation</button>
                            </form>
                        </div>
                    </div>

                    <hr>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Language</th>
                                    <th>Original Title</th>
                                    <th>Translated Title</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($menus as $menu)
                                    @foreach($menu->translations as $translation)
                                        <tr>
                                            <td>{{ $menu->title }}</td>
                                            <td>
                                                @php
                                                    $language = $languages->where('code', $translation->locale)->first();
                                                @endphp
                                                {{ $language ? $language->name : $translation->locale }}
                                            </td>
                                            <td>{{ $menu->title }}</td>
                                            <td>
                                                <form action="{{ route('admin.menus.translations.update') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $translation->id }}">
                                                    <div class="input-group">
                                                        <input type="text" name="title" value="{{ $translation->title }}" class="form-control form-control-sm">
                                                        <div class="input-group-append">
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="fas fa-save"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.menus.translations.delete', $translation->id) }}" 
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Are you sure you want to delete this translation?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>
