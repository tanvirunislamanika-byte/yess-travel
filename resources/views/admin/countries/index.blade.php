<x-admin-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mt-5">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Manage Countries</h3>
                        <a href="{{ route('admin.countries.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Country
                        </a>
                    </div>
                </div>
                
                <!-- Search Form -->
                <div class="card-body border-bottom">
                    <form action="{{ route('admin.countries.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="search_name" class="form-label">Country Name</label>
                            <input type="text" class="form-control" id="search_name" name="search_name" 
                                   value="{{ request('search_name') }}" placeholder="Search by name...">
                        </div>
                        <div class="col-md-2">
                            <label for="search_iso3" class="form-label">ISO3 Code</label>
                            <input type="text" class="form-control" id="search_iso3" name="search_iso3" 
                                   value="{{ request('search_iso3') }}" placeholder="ISO3...">
                        </div>
                        <div class="col-md-2">
                            <label for="search_capital" class="form-label">Capital</label>
                            <input type="text" class="form-control" id="search_capital" name="search_capital" 
                                   value="{{ request('search_capital') }}" placeholder="Capital...">
                        </div>
                        <div class="col-md-2">
                            <label for="search_currency" class="form-label">Currency</label>
                            <input type="text" class="form-control" id="search_currency" name="search_currency" 
                                   value="{{ request('search_currency') }}" placeholder="Currency...">
                        </div>
                        <div class="col-md-2">
                            <label for="search_region" class="form-label">Region</label>
                            <input type="text" class="form-control" id="search_region" name="search_region" 
                                   value="{{ request('search_region') }}" placeholder="Region...">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    @if(request()->hasAny(['search_name', 'search_iso3', 'search_capital', 'search_currency', 'search_region']))
                        <div class="mt-2">
                            <a href="{{ route('admin.countries.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times"></i> Clear Search
                            </a>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>ISO3</th>
                                    <th>ISO2</th>
                                    <th>Capital</th>
                                    <th>Currency</th>
                                    <th>Region</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($countries as $country)
                                    <tr>
                                        <td>{{ $country->id }}</td>
                                        <td>{{ $country->name }}</td>
                                        <td>{{ $country->iso3 }}</td>
                                        <td>{{ $country->iso2 }}</td>
                                        <td>{{ $country->capital }}</td>
                                        <td>{{ $country->currency }}</td>
                                        <td>{{ $country->region }}</td>
                                        <td>{{ $country->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('admin.countries.edit', $country->id) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.countries.destroy', $country->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this country?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No countries found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $countries->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>
