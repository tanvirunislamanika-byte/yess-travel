<x-admin-layout>

@section('title', 'Tour Booking Details')



@section('content')
<div class="container-fluid mt-2 px-4">
    <!-- Page Header -->

        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Tour Booking Details</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.tour-bookings.index') }}">Tour Bookings</a></li>
                    <li class="breadcrumb-item active">{{ $booking->booking_reference }}</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.tour-bookings.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                
            </div>
        </div>
  

    <div class="row">
        <!-- Main Booking Details -->
        <div class="col-lg-8">
            <!-- Booking Overview Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-plane"></i> Booking Overview
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="font-weight-bold">Booking Reference:</label>
                                <p class="text-primary">{{ $booking->booking_reference }}</p>
                            </div>
                            <div class="info-group">
                                <label class="font-weight-bold">Tour Title:</label>
                                <p>{{ $booking->tour->title ?? 'N/A' }}</p>
                            </div>
                            <div class="info-group">
                                <label class="font-weight-bold">Departure Date:</label>
                                <p>{{ $booking->departure_date ? date('d M Y, D', strtotime($booking->departure_date)) : 'N/A' }}</p>
                            </div>
                            <div class="info-group">
                                <label class="font-weight-bold">Tour Duration:</label>
                                <p>{{ $booking->tour->extra_field_3 ?? 'N/A' }} Days</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                                                         <div class="info-group">
                                 <label class="font-weight-bold">Booking Status:</label>
                                 <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'secondary') }} fs-6">
                                     {{ ucfirst($booking->status) }}
                                 </span>
                             </div>
                             <div class="info-group">
                                 <label class="font-weight-bold">Payment Status:</label>
                                 <span class="badge bg-{{ $booking->payment_status == 'completed' ? 'success' : ($booking->payment_status == 'pending' ? 'warning' : 'danger') }} fs-6">
                                     {{ ucfirst($booking->payment_status) }}
                                 </span>
                             </div>
                            <div class="info-group">
                                <label class="font-weight-bold">Payment Method:</label>
                                <p>
                                    @if($booking->payment_method === 'stripe')
                                        <i class="fab fa-stripe"></i> Credit/Debit Card
                                    @elseif($booking->payment_method === 'paypal')
                                        <i class="fab fa-paypal"></i> PayPal
                                    @else
                                        {{ ucfirst($booking->payment_method) }}
                                    @endif
                                </p>
                            </div>
                            <div class="info-group">
                                <label class="font-weight-bold">Payment ID:</label>
                                <p class="font-monospace">{{ $booking->payment_id ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-user"></i> Customer Information
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="font-weight-bold">Full Name:</label>
                                <p>{{ $booking->user->name ?? 'N/A' }}</p>
                            </div>
                            <div class="info-group">
                                <label class="font-weight-bold">Email:</label>
                                <p>{{ $booking->user->email ?? 'N/A' }}</p>
                            </div>
                            <div class="info-group">
                                <label class="font-weight-bold">Phone:</label>
                                <p>{{ $booking->user->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                                                         <div class="info-group">
                                 <label class="font-weight-bold">Country:</label>
                                 <p>
                                     @if($booking->user->country && $booking->user->countryData)
                                         {{ $booking->user->countryData->name }}
                                     @else
                                         <span class="text-muted">Not specified</span>
                                     @endif
                                 </p>
                             </div>
                             <div class="info-group">
                                 <label class="font-weight-bold">State/Province:</label>
                                 <p>
                                     @if($booking->user->state && $booking->user->stateData)
                                         {{ $booking->user->stateData->name }}
                                     @else
                                         <span class="text-muted">Not specified</span>
                                     @endif
                                 </p>
                             </div>
                             <div class="info-group">
                                 <label class="font-weight-bold">City:</label>
                                 <p>
                                     @if($booking->user->city && $booking->user->cityData)
                                         {{ $booking->user->cityData->name }}
                                     @else
                                         <span class="text-muted">Not specified</span>
                                     @endif
                                 </p>
                             </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Passenger Details Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-users"></i> Passenger Details
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                                                 <div class="col-md-6">
                             <div class="info-group">
                                 <label class="font-weight-bold">Total Adults:</label>
                                 <span class="badge bg-primary fs-6">{{ $booking->adults }}</span>
                             </div>
                         </div>
                         <div class="col-md-6">
                             <div class="info-group">
                                 <label class="font-weight-bold">Total Children:</label>
                                 <span class="badge bg-info fs-6">{{ $booking->children }}</span>
                             </div>
                         </div>
                    </div>

                    @if($booking->passengers)
                        <div class="passenger-list">
                            <h6 class="font-weight-bold mb-3">Passenger Information:</h6>
                            @foreach($booking->passengers as $index => $passenger)
                                <div class="passenger-item border rounded p-3 mb-2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Passenger {{ $index + 1 }}:</strong>
                                        </div>
                                        <div class="col-md-9">
                                            <p class="mb-1">
                                                <strong>{{ $passenger['title'] ?? 'N/A' }} {{ $passenger['first_name'] ?? 'N/A' }} {{ $passenger['last_name'] ?? 'N/A' }}</strong>
                                            </p>
                                                                                         @if(isset($passenger['country']) && $passenger['country'])
                                                 @php
                                                     $country = \App\Models\Country::find($passenger['country']);
                                                 @endphp
                                                 <small class="text-muted">Country: {{ $country ? $country->name : 'Invalid Country ID' }}</small>
                                             @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tour Details Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-map-marked-alt"></i> Tour Information
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="font-weight-bold">Tour Start Date:</label>
                                <p>{{ $booking->tour->extra_field_1 ? date('d M Y', strtotime($booking->tour->extra_field_1)) : 'N/A' }}</p>
                            </div>
                            <div class="info-group">
                                <label class="font-weight-bold">Tour End Date:</label>
                                <p>{{ $booking->tour->extra_field_2 ? date('d M Y', strtotime($booking->tour->extra_field_2)) : 'N/A' }}</p>
                            </div>
                            <div class="info-group">
                                <label class="font-weight-bold">Total Nights:</label>
                                <p>{{ $booking->tour->extra_field_4 ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                                                         <div class="info-group">
                                 <label class="font-weight-bold">Departure Country:</label>
                                 <p>
                                     @if($booking->tour->extra_field_5 && $booking->tour->departureCountry)
                                         {{ $booking->tour->departureCountry->name }}
                                     @else
                                         <span class="text-muted">Not specified</span>
                                     @endif
                                 </p>
                             </div>
                             <div class="info-group">
                                 <label class="font-weight-bold">Departure State:</label>
                                 <p>
                                     @if($booking->tour->extra_field_6 && $booking->tour->departureState)
                                         {{ $booking->tour->departureState->name }}
                                     @else
                                         <span class="text-muted">Not specified</span>
                                     @endif
                                 </p>
                             </div>
                             <div class="info-group">
                                 <label class="font-weight-bold">Departure City:</label>
                                 <p>
                                     @if($booking->tour->extra_field_7 && $booking->tour->departureCity)
                                         {{ $booking->tour->departureCity->name }}
                                     @else
                                         <span class="text-muted">Not specified</span>
                                     @endif
                                 </p>
                             </div>
                        </div>
                    </div>
                    
                    @if($booking->tour->extra_field_12)
                        <div class="info-group mt-3">
                            <label class="font-weight-bold">Terms & Conditions:</label>
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($booking->tour->extra_field_12)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Pricing Summary Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-calculator"></i> Pricing Summary
                    </h4>
                </div>
                <div class="card-body">
                    <div class="pricing-breakdown">
                        <div class="price-item d-flex justify-content-between mb-2">
                            <span>Adult Price:</span>
                            <strong>{{ $currencySymbol }} {{ number_format($booking->adult_price) }} × {{ $booking->adults }}</strong>
                        </div>
                        @if($booking->children > 0)
                            <div class="price-item d-flex justify-content-between mb-2">
                                <span>Children Price:</span>
                                <strong>{{ $currencySymbol }} {{ number_format($booking->children_price ?? 0) }} × {{ $booking->children }}</strong>
                            </div>
                        @endif
                        <hr>
                        <div class="price-item d-flex justify-content-between mb-2">
                            <span class="font-weight-bold">Total Amount:</span>
                            <strong class="text-primary text-lg">{{ $currencySymbol }} {{ number_format($booking->total_amount) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Timeline Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-clock"></i> Booking Timeline
                    </h4>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content mb-3">
                                <h6 class="timeline-title">Booking Created</h6>
                                <p class="timeline-text">{{ $booking->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                        @if($booking->updated_at != $booking->created_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content mb-3">
                                    <h6 class="timeline-title">Last Updated</h6>
                                    <p class="timeline-text">{{ $booking->updated_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                        @endif
                        @if($booking->departure_date)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Departure Date</h6>
                                    <p class="timeline-text">{{ date('d M Y, D', strtotime($booking->departure_date)) }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

             <!-- Quick Actions Card -->
             <div class="card mb-3">
                 <div class="card-header">
                     <h4 class="card-title">
                         <i class="fas fa-tools"></i> Quick Actions
                     </h4>
                 </div>
                 <div class="card-body">
                     <div class="d-grid gap-2">
                         <a href="{{ route('tour.detail', $booking->tour->slug ?? '') }}" target="_blank" class="btn btn-outline-primary">
                             <i class="fas fa-external-link-alt"></i> View Tour Page
                         </a>
                         <button class="btn btn-outline-success" onclick="updateStatus('{{ $booking->id }}', 'confirmed')">
                             <i class="fas fa-check"></i> Mark Confirmed
                         </button>
                         <button class="btn btn-outline-warning" onclick="updateStatus('{{ $booking->id }}', 'pending')">
                             <i class="fas fa-clock"></i> Mark Pending
                         </button>
                         <button class="btn btn-outline-danger" onclick="updateStatus('{{ $booking->id }}', 'cancelled')">
                             <i class="fas fa-times"></i> Mark Cancelled
                         </button>
                     </div>
                 </div>
             </div>
            <!-- Related Bookings Card -->
            @if($relatedBookings->count() > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-link"></i> Other Bookings For This User
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="related-bookings">
                            @foreach($relatedBookings as $related)
                                <div class="related-booking-item border-bottom pb-2 mb-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $related->user->name ?? 'N/A' }}</strong>
                                            <br><small class="text-muted">{{ $related->booking_reference }}</small>
                                        </div>
                                        <div class="text-right">
                                                                                         <span class="badge bg-{{ $related->status == 'confirmed' ? 'success' : 'warning' }}">
                                                 {{ ucfirst($related->status) }}
                                             </span>
                                            <br><small class="text-muted">{{ $related->created_at->format('d M Y') }}</small>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.tour-bookings.show', $related->id) }}" class="btn btn-sm btn-outline-primary">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
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

@push('styles')
<style>
.info-group {
    margin-bottom: 1rem;
}

.info-group label {
    color: #6c757d;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.info-group p {
    margin-bottom: 0;
    font-size: 1rem;
}

.passenger-item {
    background-color: #f8f9fa;
}

.timeline {
    position: relative;
    padding-left: 1.5rem;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -1.5rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    margin-left: 0.5rem;
}

.timeline-title {
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
    font-weight: 600;
}

.timeline-text {
    margin-bottom: 0;
    font-size: 0.8rem;
    color: #6c757d;
}

.related-booking-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

 .badge-lg {
     font-size: 0.875rem;
     padding: 0.5rem 0.75rem;
 }
 
 .fs-6 {
     font-size: 0.875rem !important;
 }

.text-lg {
    font-size: 1.25rem;
}
</style>
@endpush

@push('scripts')
<script>
let currentBookingId = null;

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
</script>
@endpush
