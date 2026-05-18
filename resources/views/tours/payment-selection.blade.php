<x-app-layout>
    <!-- Hero Section -->
    <section class="payment-hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">Complete Your Tour Booking</h1>
                <p class="hero-subtitle">Choose your preferred payment method to secure your spot</p>
            </div>
        </div>
    </section>

    <!-- Payment Selection Section -->
    <section class="payment-selection-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Tour Summary Card -->
                    <div class="tour-summary-card">
                        <div class="summary-header">
                            <h3 class="summary-title">
                                <i class="fas fa-receipt"></i>
                                Tour Summary
                            </h3>
                        </div>
                        <div class="summary-content">
                            <div class="tour-info">
                                <h4 class="tour-name">{{ $tour->title }}</h4>
                                @if($tour->extra_field_1 && $tour->extra_field_2)
                                    <div class="tour-dates">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>{{ date('d M Y', strtotime($tour->extra_field_1)) }} - {{ date('d M Y', strtotime($tour->extra_field_2)) }}</span>
                                    </div>
                                @endif
                                @if($tour->extra_field_3)
                                    <div class="tour-duration">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $tour->extra_field_3 }} Days</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="passenger-summary">
                                <h5 class="summary-subtitle">Passenger Breakdown</h5>
                                <div class="passenger-breakdown">
                                    <div class="breakdown-item">
                                        <span class="item-label">Adults</span>
                                        <span class="item-count">{{ $booking->adults }}x</span>
                                        <span class="item-price">{{ \App\Helpers\CurrencyHelper::formatPrice($tour->extra_field_8) }}</span>
                                    </div>
                                    @if($booking->children > 0)
                                        <div class="breakdown-item">
                                            <span class="item-label">Children</span>
                                            <span class="item-count">{{ $booking->children }}x</span>
                                            <span class="item-price">{{ \App\Helpers\CurrencyHelper::formatPrice($tour->extra_field_9 ?? 0) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="total-section">
                                <div class="total-line">
                                    <span class="total-label">Total Amount</span>
                                    <span class="total-amount">{{ \App\Helpers\CurrencyHelper::formatPrice($booking->total_amount) }}</span>
                                </div>
                                <p class="total-note">* Prices are per person and include all taxes</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods Card -->
                    <div class="payment-methods-card">
                        <div class="methods-header">
                            <h3 class="methods-title">
                                <i class="fas fa-credit-card"></i>
                                Select Payment Method
                            </h3>
                            <p class="methods-subtitle">Choose how you'd like to pay for your tour</p>
                        </div>
                        
                        <div class="payment-options">
                            <!-- Stripe Payment Option -->
                            <div class="payment-option" data-method="stripe">
                                <div class="option-header">
                                    <div class="option-icon">
                                        <i class="fab fa-stripe"></i>
                                    </div>
                                    <div class="option-info">
                                        <h4 class="option-title">Credit/Debit Card</h4>
                                        <p class="option-description">Pay securely with Visa, Mastercard, American Express, or Discover</p>
                                    </div>
                                    <div class="option-radio">
                                        <input type="radio" name="selected_payment_method" id="stripe" value="stripe" checked>
                                        <label for="stripe"></label>
                                    </div>
                                </div>
                                <div class="option-features">
                                    <span class="feature">
                                        <i class="fas fa-shield-alt"></i>
                                        Secure Payment
                                    </span>
                                    <span class="feature">
                                        <i class="fas fa-lock"></i>
                                        Encrypted Data
                                    </span>
                                    <span class="feature">
                                        <i class="fas fa-clock"></i>
                                        Instant Confirmation
                                    </span>
                                </div>
                                <div class="option-action">
                                    <form action="{{ route('tour.payment.stripe', $booking->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="payment-submit-btn stripe-btn">
                                            <i class="fab fa-stripe"></i>
                                            Pay with Credit Card
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- PayPal Payment Option -->
                            <div class="payment-option" data-method="paypal">
                                <div class="option-header">
                                    <div class="option-icon paypal">
                                        <i class="fab fa-paypal"></i>
                                    </div>
                                    <div class="option-info">
                                        <h4 class="option-title">PayPal</h4>
                                        <p class="option-description">Pay with your PayPal account or credit card through PayPal</p>
                                    </div>
                                    <div class="option-radio">
                                        <input type="radio" name="selected_payment_method" id="paypal" value="paypal">
                                        <label for="paypal"></label>
                                    </div>
                                </div>
                                <div class="option-features">
                                    <span class="feature">
                                        <i class="fas fa-user-shield"></i>
                                        Buyer Protection
                                    </span>
                                    <span class="feature">
                                        <i class="fas fa-globe"></i>
                                        Global Acceptance
                                    </span>
                                    <span class="feature">
                                        <i class="fas fa-mobile-alt"></i>
                                        Mobile Friendly
                                    </span>
                                </div>
                                <div class="option-action">
                                    <form action="{{ route('tour.payment.paypal', $booking->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="payment-submit-btn paypal-btn">
                                            <i class="fab fa-paypal"></i>
                                            Pay with PayPal
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="security-note">
                            <p class="note-text">
                                <i class="fas fa-shield-alt"></i>
                                All payments are processed securely and encrypted
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('css')
    <style>
        /* Hero Section */
        .payment-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Main Section */
        .payment-selection-section {
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
        .tour-duration {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .tour-dates i,
        .tour-duration i {
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

        /* Payment Methods Card */
        .payment-methods-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .methods-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .methods-title {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .methods-subtitle {
            opacity: 0.9;
            margin: 0;
            margin-top: 5px;
        }

        /* Payment Options */
        .payment-options {
            padding: 25px;
        }

        .payment-option {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
        }

        .payment-option:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
            background: #f8f9ff;
        }

        .payment-option:active {
            transform: translateY(0);
        }

        .option-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 15px;
        }

        .option-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .option-icon.paypal {
            background: linear-gradient(135deg, #0070ba 0%, #1546a0 100%);
        }

        .option-info {
            flex: 1;
        }

        .option-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .option-description {
            color: #6c757d;
            margin: 0;
            font-size: 0.9rem;
        }

        .option-radio {
            position: relative;
        }

        .option-radio input[type="radio"] {
            display: none;
        }

        .option-radio label {
            width: 24px;
            height: 24px;
            border: 2px solid #e9ecef;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }

        .option-radio input[type="radio"]:checked + label {
            border-color: #667eea;
            background: #667eea;
        }

        .option-radio input[type="radio"]:checked + label::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }

        .option-features {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
            font-size: 0.8rem;
        }

        .feature i {
            color: #667eea;
        }

        /* Payment Submit Buttons */
        .option-action {
            margin-top: 20px;
            text-align: center;
        }

        .payment-submit-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
            min-width: 200px;
            justify-content: center;
        }

        .stripe-btn {
            background: linear-gradient(135deg, #6772e5 0%, #5a67d8 100%);
        }

        .paypal-btn {
            background: linear-gradient(135deg, #0070ba 0%, #1546a0 100%);
        }

        .payment-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .payment-submit-btn:active {
            transform: translateY(0);
        }

        /* Payment Form */
        .payment-form-section {
            padding: 25px;
            border-top: 1px solid #f1f3f4;
        }

        .payment-form {
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .card-input-wrapper {
            position: relative;
        }

        .card-input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .card-input-wrapper .form-input {
            padding-left: 45px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-col {
            display: flex;
            flex-direction: column;
        }

        /* PayPal Form */
        .paypal-info {
            text-align: center;
            padding: 30px;
        }

        .paypal-icon {
            font-size: 4rem;
            color: #0070ba;
            margin-bottom: 20px;
        }

        .paypal-text {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .paypal-features {
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        /* Terms Section */
        .terms-section {
            margin-bottom: 25px;
        }

        .form-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .form-check-input {
            margin-top: 3px;
        }

        .form-check-label {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .terms-link {
            color: #667eea;
            text-decoration: none;
        }

        .terms-link:hover {
            text-decoration: underline;
        }

        /* Submit Section */
        .payment-submit {
            text-align: center;
        }

        .payment-button {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 18px 40px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .payment-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
        }

        .payment-note {
            color: #6c757d;
            margin: 0;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .security-note {
            margin-top: 20px;
            text-align: center;
        }
        
        .note-text {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .note-text i {
            color: #28a745;
            margin-right: 8px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
        }
    </style>
    @endpush

    @push('js')
    <script>
        $(document).ready(function() {
            // Add click effect to payment submit buttons
            $('.payment-submit-btn').on('click', function() {
                // Add a brief visual feedback
                $(this).css('transform', 'scale(0.95)');
                setTimeout(() => {
                    $(this).css('transform', '');
                }, 150);
            });
        });
    </script>
    @endpush
</x-app-layout>
