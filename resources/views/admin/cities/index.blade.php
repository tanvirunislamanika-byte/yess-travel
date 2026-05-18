<x-admin-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mt-5">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Manage Cities</h3>
                        <a href="{{ route('admin.cities.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New City
                        </a>
                    </div>
                </div>
                
                <!-- Search Form -->
                <div class="card-body border-bottom">
                    <form action="{{ route('admin.cities.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="search_name" class="form-label">City Name</label>
                            <input type="text" class="form-control" id="search_name" name="search_name" 
                                   value="{{ request('search_name') }}" placeholder="Search by name...">
                        </div>
                        <div class="col-md-3">
                            <label for="search_country" class="form-label">Country</label>
                            <select class="form-select" id="search_country" name="search_country">
                                <option value="">All Countries</option>
                                @foreach($countries as $id => $name)
                                    <option value="{{ $id }}" {{ request('search_country') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search_state" class="form-label">State/Province</label>
                            <select class="form-select" id="search_state" name="search_state">
                                <option value="">All States</option>
                                @foreach($states as $id => $name)
                                    <option value="{{ $id }}" {{ request('search_state') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    @if(request()->hasAny(['search_name', 'search_country', 'search_state']))
                        <div class="mt-2">
                            <a href="{{ route('admin.cities.index') }}" class="btn btn-sm btn-outline-secondary">
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
                                    <th>State/Province</th>
                                    <th>Country</th>
                                    <th>Coordinates</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cities as $city)
                                    <tr>
                                        <td>{{ $city->id }}</td>
                                        <td>{{ $city->name }}</td>
                                        <td>{{ $city->state->name ?? 'N/A' }}</td>
                                        <td>{{ $city->country->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($city->latitude && $city->longitude)
                                                {{ $city->latitude }}, {{ $city->longitude }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $city->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('admin.cities.edit', $city->id) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.cities.destroy', $city->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this city?');">
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
                                        <td colspan="7" class="text-center">No cities found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $cities->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>
