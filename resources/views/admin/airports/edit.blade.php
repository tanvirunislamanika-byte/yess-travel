<x-admin-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Airport</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.airports.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.airports.update', $airport->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Airport Name</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $airport->name) }}" 
                                   required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" 
                                   class="form-control @error('city') is-invalid @enderror" 
                                   id="city" 
                                   name="city" 
                                   value="{{ old('city', $airport->city) }}" 
                                   required>
                            @error('city')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" 
                                   class="form-control @error('country') is-invalid @enderror" 
                                   id="country" 
                                   name="country" 
                                   value="{{ old('country', $airport->country) }}" 
                                   required>
                            @error('country')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="iata_code">IATA Code</label>
                            <input type="text" 
                                   class="form-control @error('iata_code') is-invalid @enderror" 
                                   id="iata_code" 
                                   name="iata_code" 
                                   value="{{ old('iata_code', $airport->iata_code) }}" 
                                   maxlength="3"
                                   required>
                            @error('iata_code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Enter the 3-letter IATA airport code (e.g., LHR, JFK)</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Airport</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout> 