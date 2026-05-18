@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Booking Invoice</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Guest Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Name</label>
                                        <p class="mb-0">{{ auth()->user()->name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Email</label>
                                        <p class="mb-0">{{ auth()->user()->email }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Phone</label>
                                        <p class="mb-0">{{ auth()->user()->mobile ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Location</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Country</label>
                                        <p class="mb-0">{{ auth()->user()->country->name ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">State</label>
                                        <p class="mb-0">{{ auth()->user()->state ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">City</label>
                                        <p class="mb-0">{{ auth()->user()->city ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Booking Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Transaction ID</label>
                                        <p class="mb-0">{{ $booking->transaction_id }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Hotel</label>
                                        <p class="mb-0">{{ $hotel->title }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Check-in Date</label>
                                        <p class="mb-0">{{ date('F d, Y', strtotime($booking->check_in)) }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Check-out Date</label>
                                        <p class="mb-0">{{ date('F d, Y', strtotime($booking->check_out)) }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Number of Rooms</label>
                                        <p class="mb-0">{{ $booking->rooms }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Number of Guests</label>
                                        <p class="mb-0">{{ $booking->adults }} Adults, {{ $booking->childrens }} Children</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Total Amount</label>
                                        <p class="mb-0">{{ \App\Helpers\CurrencyHelper::formatPrice($booking->price) }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Payment Method</label>
                                        <p class="mb-0">{{ $booking->payment_via }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('hotels.list') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to Hotels
                        </a>
                        <button onclick="window.print()" class="btn btn-secondary">
                            <i class="fas fa-print"></i> Print Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .btn, .navbar, .footer {
            display: none !important;
        }
        .card {
            border: none !important;
        }
        .card-header {
            background-color: #fff !important;
            border-bottom: 2px solid #000 !important;
        }
    }
</style>
@endpush
@endsection 