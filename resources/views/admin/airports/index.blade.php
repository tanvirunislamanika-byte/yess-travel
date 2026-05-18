<x-admin-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mt-5">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                    <h3 class="card-title">Manage Airports</h3>
                    <a href="{{ route('admin.airports.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Airport
                        </a>
                        </div>

                    <div class="card-tools">
                        <form action="{{ route('admin.airports.index') }}" method="GET" class="form-inline float-left mr-2">
                            <div class="input-group input-group-sm" style="width: 300px;">
                                <input type="text" name="search" class="form-control float-right" placeholder="Search" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                        
                    </div>
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
                                    <th>Airport Name</th>
                                    <th>City</th>
                                    <th>Country</th>
                                    <th>IATA Code</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($airports as $airport)
                                    <tr>
                                        <td>{{ $airport->id }}</td>
                                        <td>{{ $airport->name }}</td>
                                        <td>{{ $airport->city }}</td>
                                        <td>{{ $airport->country }}</td>
                                        <td>{{ $airport->iata_code }}</td>
                                        <td>{{ $airport->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('admin.airports.edit', $airport->id) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.airports.destroy', $airport->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this airport?');">
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
                                        <td colspan="7" class="text-center">No airports found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $airports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout> 