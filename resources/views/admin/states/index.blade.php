<x-admin-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mt-5">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Manage States/Provinces</h3>
                        <a href="{{ route('admin.states.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New State
                        </a>
                    </div>
                </div>
                
                <!-- Search Form -->
                <div class="card-body border-bottom">
                    <form action="{{ route('admin.states.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="search_name" class="form-label">State Name</label>
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
                        <div class="col-md-2">
                            <label for="search_state_code" class="form-label">State Code</label>
                            <input type="text" class="form-control" id="search_state_code" name="search_state_code" 
                                   value="{{ request('search_state_code') }}" placeholder="State code...">
                        </div>
                        <div class="col-md-2">
                            <label for="search_type" class="form-label">Type</label>
                            <input type="text" class="form-control" id="search_type" name="search_type" 
                                   value="{{ request('search_type') }}" placeholder="Type...">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    @if(request()->hasAny(['search_name', 'search_country', 'search_state_code', 'search_type']))
                        <div class="mt-2">
                            <a href="{{ route('admin.states.index') }}" class="btn btn-sm btn-outline-secondary">
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
                                    <th>Country</th>
                                    <th>State Code</th>
                                    <th>Type</th>
                                    <th>Coordinates</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($states as $state)
                                    <tr>
                                        <td>{{ $state->id }}</td>
                                        <td>{{ $state->name }}</td>
                                        <td>{{ $state->country->name ?? 'N/A' }}</td>
                                        <td>{{ $state->state_code }}</td>
                                        <td>{{ $state->type }}</td>
                                        <td>
                                            @if($state->latitude && $state->longitude)
                                                {{ $state->latitude }}, {{ $state->longitude }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $state->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('admin.states.edit', $state->id) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.states.destroy', $state->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this state?');">
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
                                        <td colspan="8" class="text-center">No states found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $states->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>
