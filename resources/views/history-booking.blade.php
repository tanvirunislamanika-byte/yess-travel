<x-app-layout>
    <style>
        .payment-history-container {
            background: linear-gradient(135deg, #eee 0%, #ddd 100%);
            min-height: 100vh;
            padding: 40px 0;
            position: relative;
        }
        
        .payment-history-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="payment-pattern" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23payment-pattern)"/></svg>');
            pointer-events: none;
        }
        
        .payment-history-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }
        
        .payment-history-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 22px;
            z-index: -1;
            opacity: 0.1;
        }
        
        .payment-history-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .payment-history-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .payment-history-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.3);
            margin: 0 auto 20px;
            position: relative;
            z-index: 2;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            backdrop-filter: blur(10px);
        }
        
        .payment-history-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .payment-history-subtitle {
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }
        
        .payment-history-body {
            padding: 40px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
        
        .payment-transaction-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 20px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .payment-transaction-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .payment-transaction-card:hover::before {
            opacity: 1;
        }
        
        .payment-transaction-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.1);
            border-color: #667eea;
        }
        
        .payment-transaction-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .payment-transaction-id {
            font-size: 18px;
            font-weight: 700;
            color: #2d3748;
        }
        
        .payment-transaction-status {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .payment-status-paid {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .payment-status-pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .payment-status-failed {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .payment-transaction-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .payment-detail-item {
            display: flex;
            flex-direction: column;
        }
        
        .payment-detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .payment-detail-value {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
        }
        
        .payment-amount {
            font-size: 24px;
            font-weight: 700;
            color: #10b981;
        }
        
        .payment-method-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            color: #4a5568;
        }
        
        .payment-method-badge i {
            font-size: 18px;
        }
        
        .payment-method-visa {
            border-color: #1e40af;
            color: #1e40af;
            background: #eff6ff;
        }
        
        .payment-method-paypal {
            border-color: #0891b2;
            color: #0891b2;
            background: #ecfeff;
        }
        
        .payment-method-paystack {
            border-color: #059669;
            color: #059669;
            background: #f0fdf4;
        }
        
        .payment-method-stripe {
            border-color: #7c3aed;
            color: #7c3aed;
            background: #faf5ff;
        }
        
        .payment-back-button-container {
            text-align: left;
            margin-bottom: 20px;
            padding: 0 20px;
        }
        
        .payment-btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .payment-btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .no-payments-message {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }
        
        .no-payments-icon {
            font-size: 64px;
            color: #cbd5e0;
            margin-bottom: 20px;
        }
        
        .no-payments-title {
            font-size: 24px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 10px;
        }
        
        .no-payments-subtitle {
            font-size: 16px;
            color: #64748b;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .payment-history-header {
                padding: 30px 20px;
            }
            
            .payment-history-body {
                padding: 30px 20px;
            }
            
            .payment-transaction-details {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .payment-transaction-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
        
        @media (max-width: 576px) {
            .payment-history-container {
                padding: 20px 0;
            }
            
            .payment-history-card {
                margin: 0 10px 20px;
                border-radius: 15px;
            }
            
            .payment-history-header {
                padding: 25px 15px;
            }
            
            .payment-history-body {
                padding: 25px 15px;
            }
        }
        
        /* Animation Classes */
        @keyframes paymentFadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .payment-history-card {
            animation: paymentFadeInUp 0.6s ease-out;
        }
        
        .payment-transaction-card {
            animation: paymentFadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }
        
        .payment-transaction-card:nth-child(1) { animation-delay: 0.1s; }
        .payment-transaction-card:nth-child(2) { animation-delay: 0.2s; }
        .payment-transaction-card:nth-child(3) { animation-delay: 0.3s; }
        .payment-transaction-card:nth-child(4) { animation-delay: 0.4s; }
        .payment-transaction-card:nth-child(5) { animation-delay: 0.5s; }
    </style>

<!-- Page title start -->
     <section class="bookings-hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">{{ __('frontend.payment_history') }}</h1>
                <p class="hero-subtitle">{{ __('frontend.track_payment_and_bookings') }}</p>
            </div>
        </div>
    </section>
    <!-- Page title end -->

    <div class="innerpagewrap">
        <div class="container">
            
            
            <div class="row">
                <div class="col-lg-3">
                    @include('components.user-sidebar')
                </div>

                <div class="col-lg-9">
                   
                      

                        <!-- Payment History Body -->
                      
                            <?php $payments = App\Models\Hotels::where('user_id', auth()->user()->id)->where('status','paid')->get(); ?>
                            
                            @if($payments->count() > 0)
                                <div class="row">
                                    @foreach($payments as $key => $payment)
                                        <div class="col-lg-6 col-xl-6">
                                            <div class="payment-transaction-card">
                                                <div class="payment-transaction-header">
                                                    <div class="payment-transaction-id">
                                                        #{{ $key+1 }} - {{ $payment->transaction_id ?: 'N/A' }}
                                                    </div>
                                                    <div class="payment-transaction-status payment-status-paid">
                                                        {{ __('frontend.paid') }}
                                                    </div>
                                                </div>
                                                
                                                <div class="payment-transaction-details">
                                                    <div class="payment-detail-item">
                                                        <span class="payment-detail-label">{{ __('frontend.date') }}</span>
                                                        <span class="payment-detail-value">
                                                            {{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y') }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="payment-detail-item">
                                                        <span class="payment-detail-label">{{ __('frontend.time') }}</span>
                                                        <span class="payment-detail-value">
                                                            {{ \Carbon\Carbon::parse($payment->created_at)->format('h:i A') }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="payment-detail-item">
                                                        <span class="payment-detail-label">{{ __('frontend.amount') }}</span>
                                                        <span class="payment-amount">
                                                            ${{ number_format($payment->price, 2) }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="payment-detail-item">
                                                        <span class="payment-detail-label">{{ __('frontend.payment_method') }}</span>
                                                        <div class="payment-method-badge 
                                                            @if($payment->payment_via == 'Credit Card' || $payment->payment_via == 'stripe') payment-method-visa
                                                            @elseif($payment->payment_via == 'paypal') payment-method-paypal
                                                            @elseif($payment->payment_via == 'paystack' || $payment->payment_via == 'Paystack') payment-method-paystack
                                                            @else payment-method-stripe
                                                            @endif">
                                                            @if($payment->payment_via == 'Credit Card' || $payment->payment_via == 'stripe')
                                                                <i class="fab fa-cc-visa"></i> {{ __('frontend.visa') }}
                                                            @elseif($payment->payment_via == 'paypal')
                                                                <i class="fab fa-cc-paypal"></i> {{ __('frontend.paypal') }}
                                                            @elseif($payment->payment_via == 'paystack' || $payment->payment_via == 'Paystack')
                                                                <i class="fas fa-credit-card"></i> {{ __('frontend.paystack') }}
                                                            @else
                                                                <i class="fas fa-credit-card"></i> {{ ucfirst($payment->payment_via ?: __('frontend.card')) }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="payment-detail-item">
                                                        <span class="payment-detail-label">{{ __('frontend.booking_type') }}</span>
                                                        <span class="payment-detail-value">
                                                            <i class="fas fa-hotel text-primary"></i> {{ __('frontend.hotel') }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="payment-detail-item">
                                                        <span class="payment-detail-label">{{ __('frontend.status') }}</span>
                                                        <span class="payment-detail-value">
                                                            <i class="fas fa-check-circle text-success"></i> {{ __('frontend.confirmed') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="no-payments-message">
                                    <div class="no-payments-icon">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <div class="no-payments-title">{{ __('frontend.no_payment_history') }}</div>
                                    <div class="no-payments-subtitle">{{ __('frontend.no_payment_history_desc') }}</div>
                                </div>
                            @endif
                       
                   
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
