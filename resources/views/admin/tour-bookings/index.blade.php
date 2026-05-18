<x-admin-layout>

@section('title', 'Tour Bookings Management')



@section('content')
<div class="container-fluid mt-2 px-4">
    <!-- Page Header -->

        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Tour Bookings</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tour Bookings</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.tour-bookings.export', request()->query()) }}" class="btn btn-primary">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
        </div>
    

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-primary">
                            <i class="fas fa-plane"></i>
                        </span>
                        <div class="dash-count">
                            <h3>{{ number_format($stats['total_bookings']) }}</h3>
                        </div>
                    </div>
                    <p class="text-muted">Total Bookings</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-warning">
                            <i class="fas fa-clock"></i>
                        </span>
                        <div class="dash-count">
                            <h3>{{ number_format($stats['pending_payments']) }}</h3>
                        </div>
                    </div>
                    <p class="text-muted">Pending Payments</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-success">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <div class="dash-count">
                            <h3>{{ number_format($stats['confirmed_bookings']) }}</h3>
                        </div>
                    </div>
                    <p class="text-muted">Confirmed Bookings</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-info">
                            <i class="fas fa-dollar-sign"></i>
                        </span>
                                                 <div class="dash-count">
                             <h3>{{ $currencySymbol }} {{ number_format($stats['total_revenue']) }}</h3>
                         </div>
                     </div>
                     <p class="text-muted">Total Revenue</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tour-bookings.index') }}" id="searchForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                                   placeholder="Booking ref, tour, customer...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">All Statuses</option>
                                @foreach($filterOptions['statuses'] as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label">Payment Status</label>
                            <select class="form-select" name="payment_status">
                                <option value="">All Payment Statuses</option>
                                @foreach($filterOptions['payment_statuses'] as $paymentStatus)
                                    <option value="{{ $paymentStatus }}" {{ request('payment_status') == $paymentStatus ? 'selected' : '' }}>
                                        {{ ucfirst($paymentStatus) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label">Sort By</label>
                            <select class="form-select" name="sort_by">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Booking Date</option>
                                <option value="departure_date" {{ request('sort_by') == 'departure_date' ? 'selected' : '' }}>Departure Date</option>
                                <option value="total_amount" {{ request('sort_by') == 'total_amount' ? 'selected' : '' }}>Amount</option>
                                <option value="booking_reference" {{ request('sort_by') == 'booking_reference' ? 'selected' : '' }}>Reference</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <select class="form-select" name="sort_order">
                                <option value="desc" {{ request('sort_by') == 'desc' ? 'selected' : '' }}>Descending</option>
                                <option value="asc" {{ request('sort_by') == 'asc' ? 'selected' : '' }}>Ascending</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="mb-3">
                            <label class="form-label">&nbsp; </label>
                            <button type="submit" class="btn btn-primary btn-block w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

               
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Booking Ref</th>
                            <th>Tour</th>
                            <th>Customer</th>
                            <th>Passengers</th>
                            <th>Departure</th>
                                                         <th>Amount ({{ $currencySymbol }})</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <strong>{{ $booking->booking_reference }}</strong>
                                    @if($booking->payment_id)
                                        <br><small class="text-muted">Txn: {{ $booking->payment_id }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ Str::limit($booking->tour->title ?? 'N/A', 30) }}</strong>
                                        @if($booking->tour)
                                            <br><small class="text-muted">{{ $booking->tour->extra_field_3 ?? 'N/A' }} Days</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $booking->user->name ?? 'N/A' }}</strong>
                                        <br><small class="text-muted">{{ $booking->user->email ?? 'N/A' }}</small>
                                        @if($booking->user && $booking->user->phone)
                                            <br><small class="text-muted">{{ $booking->user->phone }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <div class="badge bg-primary">{{ $booking->adults }} Adults</div>
                                        @if($booking->children > 0)
                                            <br><div class="badge bg-info">{{ $booking->children }} Children</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($booking->departure_date)
                                        <div class="text-center">
                                            <strong>{{ date('d M Y', strtotime($booking->departure_date)) }}</strong>
                                            <br><small class="text-muted">{{ date('D', strtotime($booking->departure_date)) }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-right">
                                        <strong>{{ $currencySymbol }} {{ number_format($booking->total_amount) }}</strong>
                                        <br><small class="text-muted">
                                            @if($booking->adults > 0)
                                                {{ $currencySymbol }} {{ number_format($booking->adult_price) }} × {{ $booking->adults }}
                                            @endif
                                            @if($booking->children > 0)
                                                <br>{{ $currencySymbol }} {{ number_format($booking->children_price ?? 0) }} × {{ $booking->children }}
                                            @endif
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <span class="badge bg-{{ $booking->payment_status == 'completed' ? 'success' : ($booking->payment_status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                        <br><small class="text-muted">{{ ucfirst($booking->payment_method) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong>{{ $booking->created_at->format('d M Y') }}</strong>
                                        <br><small class="text-muted">{{ $booking->created_at->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                                    <div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.tour-bookings.show', $booking->id) }}">
                            <i class="fas fa-eye"></i>  Booking Details
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('tour.detail', $booking->tour->slug ?? '') }}" target="_blank">
                            <i class="fas fa-external-link-alt"></i>   View Tour
                        </a></li>                        
                       
                    </ul>
                </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>No tour bookings found</h5>
                                        <p>Try adjusting your search criteria or filters.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Booking Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Booking Status</label>
                        <select class="form-select" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Status</label>
                        <select class="form-select" name="payment_status" required>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitStatusUpdate()">Update Status</button>
            </div>
        </div>
    </div>
</div>

</x-admin-layout> 

@push('scripts')
<script>
let currentBookingId = null;

function clearFilters() {
    document.getElementById('searchForm').reset();
    document.getElementById('searchForm').submit();
}

function updateStatus(bookingId, status) {
    currentBookingId = bookingId;
    
    // Set default values
    document.querySelector('select[name="status"]').value = status;
    document.querySelector('select[name="payment_status"]').value = 'pending';
    
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

function submitStatusUpdate() {
    if (!currentBookingId) return;
    
    const formData = new FormData(document.getElementById('statusForm'));
    
    fetch(`/admin/tour-bookings/${currentBookingId}/status`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            status: formData.get('status'),
            payment_status: formData.get('payment_status')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
            modal.hide();
            location.reload();
        } else {
            alert('Error updating status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating status. Please try again.');
    });
}

// Auto-submit form when filters change
document.querySelectorAll('select[name="status"], select[name="payment_status"], select[name="payment_method"], select[name="sort_by"], select[name="sort_order"]').forEach(select => {
    select.addEventListener('change', () => {
        document.getElementById('searchForm').submit();
    });
});
</script>
@endpush
