<x-app-layout>
    <!-- Success Hero Section -->
    <section class="success-hero">
        <div class="container">
            <div class="hero-content text-center">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="hero-title">{{ __('frontend.booking_confirmed') }}!</h1>
                <p class="hero-subtitle">{{ __('frontend.tour_booking_success_message') }}</p>
                <div class="booking-reference">
                    <span class="reference-label">{{ __('frontend.booking_reference') }}:</span>
                    <span class="reference-number">{{ $booking->booking_reference }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Details Section -->
    <section class="booking-details-section">
        <div class="container">

         <!-- Action Buttons -->
         <div class="action-buttons">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt"></i>
                            {{ __('frontend.go_to_dashboard') }}
                        </a>
                        <a href="{{ route('tour.bookings') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i>
                            {{ __('frontend.view_all_bookings') }}
                        </a>
                        <a href="{{ route('tour.detail', $booking->tour->slug) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i>
                            {{ __('frontend.back_to_tour') }}
                        </a>
                    </div>


            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <!-- Tour Summary Card -->
                    <div class="tour-summary-card">
                        <div class="summary-header">
                            <h3 class="summary-title">
                                <i class="fas fa-receipt"></i>
                                {{ __('frontend.tour_details') }}
                            </h3>
                        </div>
                        <div class="summary-content">
                            <div class="tour-info">
                                <h4 class="tour-name">{{ $booking->tour->title }}</h4>
                                @if($booking->tour->extra_field_1 && $booking->tour->extra_field_2)
                                    <div class="tour-dates">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>{{ date('d M Y', strtotime($booking->tour->extra_field_1)) }} - {{ date('d M Y', strtotime($booking->tour->extra_field_2)) }}</span>
                                    </div>
                                @endif
                                @if($booking->tour->extra_field_3)
                                    <div class="tour-duration">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $booking->tour->extra_field_3 }} {{ __('frontend.days') }}</span>
                                    </div>
                                @endif
                                <div class="departure-date">
                                    <i class="fas fa-plane-departure"></i>
                                    <span>{{ __('frontend.departure') }}: {{ $booking->formatted_departure_date }}</span>
                                </div>
                            </div>
                            
                            <div class="passenger-summary">
                                <h5 class="summary-subtitle">{{ __('frontend.passenger_breakdown') }}</h5>
                                <div class="passenger-breakdown">
                                    <div class="breakdown-item">
                                        <span class="item-label">{{ __('frontend.adults') }}</span>
                                        <span class="item-count">{{ $booking->adults }}x</span>
                                        <span class="item-price">{{ \App\Helpers\CurrencyHelper::formatPrice($booking->adult_price) }}</span>
                                    </div>
                                    @if($booking->children > 0)
                                        <div class="breakdown-item">
                                            <span class="item-label">{{ __('frontend.children') }}</span>
                                            <span class="item-count">{{ $booking->children }}x</span>
                                            <span class="item-price">{{ \App\Helpers\CurrencyHelper::formatPrice($booking->children_price ?? 0) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="total-section">
                                <div class="total-line">
                                    <span class="total-label">{{ __('frontend.total_amount') }}</span>
                                    <span class="total-amount">{{ $booking->formatted_total }}</span>
                                </div>
                                <p class="total-note">{{ __('frontend.prices_include_taxes') }}</p>
                            </div>
                        </div>
                    </div>

                    

                    <!-- Payment Information Card -->
                    <div class="payment-info-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-credit-card"></i>
                                {{ __('frontend.payment_information') }}
                            </h3>
                        </div>
                        <div class="card-content">
                            <div class="payment-grid">
                                <div class="payment-item">
                                    <span class="payment-label">{{ __('frontend.payment_method') }}:</span>
                                    <span class="payment-value">
                                        @if($booking->payment_method === 'stripe')
                                            <i class="fab fa-stripe"></i> {{ __('frontend.credit_debit_card') }}
                                        @else
                                            <i class="fab fa-paypal"></i> {{ __('frontend.paypal') }}
                                        @endif
                                    </span>
                                </div>
                                <div class="payment-item">
                                    <span class="payment-label">{{ __('frontend.payment_status') }}:</span>
                                    <span class="payment-status {{ $booking->payment_status_badge_class }}">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </div>
                                <div class="payment-item">
                                    <span class="payment-label">{{ __('frontend.transaction_id') }}:</span>
                                    <span class="payment-value">{{ $booking->payment_id ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                   

                    
                </div>
                <div class="col-lg-6">
                    <!-- Passenger Details Card -->
                    <div class="passenger-details-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users"></i>
                                {{ __('frontend.passenger_information') }}
                            </h3>
                        </div>
                        <div class="card-content">
                            @if(is_array($booking->passengers) && count($booking->passengers) > 0)
                                @foreach($booking->passengers as $index => $passenger)
                                    @if(is_array($passenger))
                                        <div class="passenger-item">
                                            <div class="passenger-header">
                                                <h5 class="passenger-name">
                                                    {{ $passenger['title'] ?? 'N/A' }} {{ $passenger['first_name'] ?? 'N/A' }} {{ $passenger['last_name'] ?? 'N/A' }}
                                                </h5>
                                                <span class="passenger-type">
                                                    @if($index < $booking->adults)
                                                        <i class="fas fa-user"></i> {{ __('frontend.adult') }}
                                                    @else
                                                        <i class="fas fa-child"></i> {{ __('frontend.child') }}
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="passenger-details">
                                                <div class="detail-item">
                                                    <span class="detail-label">{{ __('frontend.country') }}:</span>
                                                    <span class="detail-value">
                                                        @php
                                                            $country = \App\Models\Country::find($passenger['country'] ?? null);
                                                        @endphp
                                                        {{ $country ? $country->name : 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr class="passenger-divider">
                                        @endif
                                    @endif
                                @endforeach
                            @else
                                <div class="passenger-item">
                                    <p class="text-muted">{{ __('frontend.passenger_details_not_available') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contact Information Card -->
                    <div class="contact-info-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-address-book"></i>
                                {{ __('frontend.contact_information') }}
                            </h3>
                        </div>
                        <div class="card-content">
                            <div class="contact-grid">
                                <div class="contact-item">
                                    <span class="contact-label">{{ __('frontend.name') }}:</span>
                                    <span class="contact-value">{{ $booking->contact['name'] }}</span>
                                </div>
                                <div class="contact-item">
                                    <span class="contact-label">{{ __('frontend.email') }}:</span>
                                    <span class="contact-value">{{ $booking->contact['email'] }}</span>
                                </div>
                                <div class="contact-item">
                                    <span class="contact-label">{{ __('frontend.phone') }}:</span>
                                    <span class="contact-value">{{ $booking->contact['phone'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div class="important-notes">
                        <div class="note-header">
                            <h4 class="note-title">
                                <i class="fas fa-info-circle"></i>
                                {{ __('frontend.important_information') }}
                            </h4>
                        </div>
                        <div class="note-content">
                            <ul class="note-list">
                                <li>{{ __('frontend.arrive_30_minutes_early') }}</li>
                                <li>{{ __('frontend.bring_valid_photo_id') }}</li>
                                <li>{{ __('frontend.check_email_for_itinerary') }}</li>
                                <li>{{ __('frontend.contact_48_hours_for_changes') }}</li>
                                <li>{{ __('frontend.emergency_support_line') }}: +92 300 1234567</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    @push('css')
    <style>
        /* Success Hero Section */
        .success-hero {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding:40px 0;
            text-align: center;
        }

        .success-icon {
            font-size: 5rem;
            margin-bottom: 0;
            animation: bounceIn 0.8s ease-out;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 30px;
        }

        .booking-reference {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 15px 30px;
            display: inline-block;
            backdrop-filter: blur(10px);
        }

        .reference-label {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-right: 10px;
        }

        .reference-number {
            font-size: 1.1rem;
            font-weight: 700;
            font-family: 'Courier New', monospace;
        }

        /* Main Section */
        .booking-details-section {
            padding: 60px 0;
            background: #f8f9fa;
        }

        /* Tour Summary Card */
        .tour-summary-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .summary-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .summary-title {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .summary-content {
            padding: 25px;
        }

        .tour-info {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f1f3f4;
        }

        .tour-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .tour-dates,
        .tour-duration,
        .departure-date {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .tour-dates i,
        .tour-duration i,
        .departure-date i {
            color: #667eea;
            width: 16px;
        }

        .passenger-summary {
            margin-bottom: 25px;
        }

        .summary-subtitle {
            font-size: 1rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 15px;
        }

        .passenger-breakdown {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .breakdown-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .breakdown-item:last-child {
            border-bottom: none;
        }

        .item-label {
            font-weight: 600;
            color: #495057;
        }

        .item-count {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .item-price {
            font-weight: 700;
            color: #667eea;
        }

        .total-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
        }

        .total-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .total-label {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .total-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }

        .total-note {
            color: #6c757d;
            font-size: 0.8rem;
            margin: 0;
            text-align: center;
        }

        /* Detail Cards */
        .passenger-details-card,
        .contact-info-card,
        .payment-info-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .card-header {
            background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
            color: white !important;
            padding: 20px 25px;
        }

        .card-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-content {
            padding: 25px;
        }

        /* Passenger Details */
        .passenger-item {
            margin-bottom: 20px;
        }

        .passenger-item:last-child {
            margin-bottom: 0;
        }

        .passenger-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .passenger-name {
            font-size: 1rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .passenger-type {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .passenger-details {
            padding-left: 20px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }

        .detail-value {
            color: #2c3e50;
        }

        .passenger-divider {
            border: none;
            border-top: 1px solid #f1f3f4;
            margin: 20px 0;
        }

        /* Contact Information */
        .contact-grid {
            display: grid;
            gap: 15px;
        }

        .contact-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .contact-item:last-child {
            border-bottom: none;
        }

        .contact-label {
            font-weight: 600;
            color: #6c757d;
        }

        .contact-value {
            color: #2c3e50;
            font-weight: 500;
        }

        /* Payment Information */
        .payment-grid {
            display: grid;
            gap: 15px;
        }

        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .payment-item:last-child {
            border-bottom: none;
        }

        .payment-label {
            font-weight: 600;
            color: #6c757d;
        }

        .payment-value {
            color: #2c3e50;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .payment-status {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
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

        /* Action Buttons */
        .action-buttons {
            text-align: center;
            margin-bottom: 40px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 0 10px 10px 0;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
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

        /* Important Notes */
        .important-notes {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .note-header {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
            padding: 20px 25px;
        }

        .note-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .note-content {
            padding: 25px;
        }

        .note-list {
            margin: 0;
            padding-left: 20px;
        }

        .note-list li {
            margin-bottom: 10px;
            color: #495057;
            line-height: 1.6;
        }

        .note-list li:last-child {
            margin-bottom: 0;
        }

        /* Animations */
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .success-icon {
                font-size: 3rem;
            }
            
            .action-buttons .btn {
                display: block;
                margin: 10px auto;
                text-align: center;
                justify-content: center;
            }
            
            .passenger-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
    @endpush
</x-app-layout>
