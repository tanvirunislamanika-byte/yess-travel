<x-app-layout>
    <!-- Hero Section -->
    <section class="bookings-hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">{{ __('frontend.my_tour_bookings') }}</h1>
                <p class="hero-subtitle">{{ __('frontend.view_manage_tour_reservations') }}</p>
            </div>
        </div>
    </section>

    <!-- Bookings Section -->
    <section class="bookings-section">
        <div class="container">

        <div class="row">
        <div class="col-lg-3">
                              @include('components.user-sidebar')
              
            </div>
            <div class="col-lg-9">

            

            @if($bookings->count() > 0) 
                <div class="bookings-grid">
                    @foreach($bookings as $booking)
                        <div class="booking-card">
                            <div class="booking-header">
                                <div class="booking-status">
                                    <span class="status-badge {{ $booking->status_badge_class }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    <span class="payment-badge {{ $booking->payment_status_badge_class }}">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </div>
                                <div class="booking-reference">
                                    <span class="reference-label">{{ __('frontend.reference_short') }}:</span>
                                    <span class="reference-number">{{ $booking->booking_reference }}</span>
                                </div>
                            </div>

                            <div class="booking-content">
                                <div class="row">
                                    <div class="col-lg-6">
                                    <div class="tour-info">
                                    <h3 class="tour-title">{{ $booking->tour->title }}</h3>
                                    <div class="tour-meta">
                                        @if($booking->tour->extra_field_1 && $booking->tour->extra_field_2)
                                            <span class="meta-item">
                                                <i class="fas fa-calendar-alt"></i>
                                                {{ date('d M Y', strtotime($booking->tour->extra_field_1)) }} - {{ date('d M Y', strtotime($booking->tour->extra_field_2)) }}
                                            </span>
                                        @endif
                                        @if($booking->tour->extra_field_3)
                                            <span class="meta-item">
                                                <i class="fas fa-clock"></i>
                                                {{ $booking->tour->extra_field_3 }} {{ __('frontend.days') }}
                                            </span>
                                        @endif
                                        <span class="meta-item">
                                            <i class="fas fa-plane-departure"></i>
                                            {{ __('frontend.departure') }}: {{ $booking->formatted_departure_date }}
                                        </span>
                                    </div>
                                </div>

                                <div class="booking-actions">
                                    <a href="{{ route('tour.detail', $booking->tour->slug) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                        {{ __('frontend.view_tour') }}
                                    </a>
                                    <a href="{{ route('tour.booking.success', $booking->id) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-receipt"></i>
                                        {{ __('frontend.view_details') }}
                                    </a>
                                    @if($booking->payment_status === 'pending')
                                        <button class="btn btn-warning" disabled>
                                            <i class="fas fa-clock"></i>
                                            {{ __('frontend.payment_pending') }}
                                        </button>
                                    @elseif($booking->payment_status === 'failed')
                                        <button class="btn btn-danger" disabled>
                                            <i class="fas fa-times"></i>
                                            {{ __('frontend.payment_failed') }}
                                        </button>
                                    @else
                                        <button class="btn btn-success" disabled>
                                            <i class="fas fa-check"></i>
                                            {{ __('frontend.confirmed') }}
                                        </button>
                                    @endif
                                </div>
                                    </div>
                                    <div class="col-lg-6">
                                    <div class="passenger-info">
                                        <h4 class="info-title">{{ __('frontend.total_passengers') }}</h4>
                                        <div class="passenger-counts">
                                            <span class="count-item">
                                                <i class="fas fa-user"></i>
                                                {{ $booking->adults }} {{ __('frontend.adults') }}
                                            </span>
                                            @if($booking->children > 0)
                                                <span class="count-item">
                                                    <i class="fas fa-child"></i>
                                                    {{ $booking->children }} {{ __('frontend.children') }}
                                                </span>
                                            @endif
                                        </div>
                                </div>

                                <div class="payment-info">
                                    <div class="amount-breakdown">
                                        <div class="breakdown-item">
                                            <span class="item-label">{{ __('frontend.adults') }}:</span>
                                            <span class="item-price">{{ \App\Helpers\CurrencyHelper::formatPrice($booking->adult_price) }} × {{ $booking->adults }}</span>
                                        </div>
                                        @if($booking->children > 0)
                                            <div class="breakdown-item">
                                                <span class="item-label">{{ __('frontend.children') }}:</span>
                                                <span class="item-price">{{ \App\Helpers\CurrencyHelper::formatPrice($booking->children_price ?? 0) }} × {{ $booking->children }}</span>
                                            </div>
                                        @endif
                                        <div class="total-line">
                                            <span class="total-label">{{ __('frontend.total') }}:</span>
                                            <span class="total-amount">{{ $booking->formatted_total }}</span>
                                        </div>
                                    </div>
                                    <div class="payment-method">
                                        <span class="method-label">{{ __('frontend.payment_by') }}:</span>
                                        <span class="method-value">
                                            @if($booking->payment_method === 'stripe')
                                                <i class="fab fa-stripe"></i> {{ __('frontend.credit_debit_card') }}
                                            @else
                                                <i class="fab fa-paypal"></i> {{ __('frontend.paypal') }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                        
                                    </div>
                                </div>
                                

                                

                                
                            </div>

                            <div class="booking-footer">
                                <div class="booking-date">
                                    <i class="fas fa-calendar-plus"></i>
                                    {{ __('frontend.booked_on') }} {{ $booking->created_at->format('d M Y, h:i A') }}
                                </div>
                                @if($booking->payment_id)
                                    <div class="transaction-id">
                                        <i class="fas fa-receipt"></i>
                                        {{ __('frontend.txn') }}: {{ $booking->payment_id }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($bookings->hasPages())
                    <div class="pagination-wrapper">
                        {{ $bookings->links() }}
                    </div>
                @endif

            @else
                <div class="no-bookings">
                    <div class="no-bookings-icon">
                    <i class="fas fa-tree"></i>
                    </div>
                    <h3 class="no-bookings-title">{{ __('frontend.no_tour_bookings_title') }}</h3>
                    <p class="no-bookings-subtitle">{{ __('frontend.no_tour_bookings_desc') }}</p>
                    <div class="no-bookings-actions">
                        <a href="{{ route('tours.list') }}" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            {{ __('frontend.browse_tours') }}
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-tachometer-alt"></i>
                            {{ __('frontend.back_to_dashboard') }}
                        </a>
                    </div>
                </div>
            @endif

            </div>
            </div>

        </div>
    </section>

    @push('css')
    <style>
        /* Hero Section */
       

        /* Main Section */
        .bookings-section {
            padding: 40px 0;
            background: #f8f9fa;
            min-height: 50vh;
        }

        /* Bookings Grid */
        .bookings-grid {
            display: grid;
            gap: 20px;
            margin-bottom: 30px;
        }

        /* Booking Card */
        .booking-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .booking-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .booking-header {
            background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
            color: white;
            padding: 12px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .booking-status {
            display: flex;
            gap: 8px;
        }

        .status-badge,
        .payment-badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-secondary {
            background: #e2e3e5;
            color: #383d41;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .booking-reference {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .reference-label {
            font-size: 0.7rem;
            opacity: 0.8;
        }

        .reference-number {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .booking-content {
            padding: 16px;
        }

        .tour-info {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f1f3f4;
        }

        .tour-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .tour-meta {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
            font-size: 0.8rem;
        }

        .meta-item i {
            color: #667eea;
            width: 16px;
        }

        .passenger-info {
            margin-bottom: 15px;
            padding-bottom: 15px;
            display:flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .passenger-counts {
            display: flex;
            gap: 15px;
        }

        .count-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #6c757d;
            font-size: 0.8rem;
        }

        .count-item i {
            color: #667eea;
        }

        .payment-info {
            margin-bottom: 18px;
        }

        .amount-breakdown {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 12px;
        }

        .breakdown-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
            border-bottom:none;
            padding:0;
        }

        .breakdown-item:last-child {
            margin-bottom: 0;
        }

        .item-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
        }

        .item-price {
            color: #667eea;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .total-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            border-top: 1px solid #e9ecef;
            margin-top: 10px;
        }

        .total-label {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1rem;
        }

        .total-amount {
            font-size: 1.1rem;
            font-weight: 700;
            color: #667eea;
        }

        .payment-method {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .method-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
        }

        .method-value {
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
        }

        .method-value i {
            color: #667eea;
        }

        .booking-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-outline-primary {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-outline-primary:hover {
            background: #667eea;
            color: white;
        }

        .btn-outline-secondary {
            background: transparent;
            color: #6c757d;
            border: 2px solid #6c757d;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .booking-footer {
            background: #f8f9fa;
            padding: 10px 16px;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: #6c757d;
        }

        .booking-date,
        .transaction-id {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .transaction-id {
            font-family: 'Courier New', monospace;
        }

      

        /* Pagination */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 5px;
        }

        .page-item {
            margin: 0;
        }

        .page-link {
            display: block;
            padding: 10px 15px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .page-link:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .page-item.active .page-link {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .page-item.disabled .page-link {
            background: #f8f9fa;
            color: #6c757d;
            border-color: #e9ecef;
            cursor: not-allowed;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .booking-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .booking-actions {
                justify-content: center;
            }
            
            .booking-footer {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            
            .passenger-counts {
                flex-direction: column;
                gap: 10px;
            }
            
            .no-bookings-actions {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
    @endpush
</x-app-layout>
