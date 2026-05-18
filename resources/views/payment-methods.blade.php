<x-app-layout>
    <style>
        .payment-methods-container {
            background: linear-gradient(135deg, #eee 0%, #ddd 100%);
            min-height: 100vh;
            padding: 40px 0;
            position: relative;
        }
        
        .payment-methods-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="payment-methods-pattern" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23payment-methods-pattern)"/></svg>');
            pointer-events: none;
        }
        
        .payment-methods-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }
        
        .payment-methods-card::before {
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
        
        .payment-methods-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .payment-methods-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .payment-methods-icon {
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
        
        .payment-methods-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .payment-methods-subtitle {
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }
        
        .payment-methods-body {
            padding: 40px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
        
        .payment-method-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 20px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .payment-method-card::before {
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
        
        .payment-method-card:hover::before {
            opacity: 1;
        }
        
        .payment-method-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.1);
            border-color: #667eea;
        }
        
        .payment-method-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .payment-method-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .payment-method-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        
        .payment-method-visa {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }
        
        .payment-method-paypal {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
        }
        
        .payment-method-paystack {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        }
        
        .payment-method-stripe {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        }
        
        .payment-method-default {
            background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%);
        }
        
        .payment-method-details h5 {
            font-size: 18px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .payment-method-details p {
            font-size: 14px;
            color: #64748b;
            margin: 0;
        }
        
        .payment-method-status {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .payment-method-last-used {
            text-align: right;
        }
        
        .payment-method-last-used-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .payment-method-last-used-value {
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
        }
        
        .payment-method-actions {
            display: flex;
            gap: 10px;
        }
        
        .payment-method-btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .payment-method-btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .payment-method-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .payment-method-btn-outline {
            background: transparent;
            color: #ef4444;
            border: 2px solid #ef4444;
        }
        
        .payment-method-btn-outline:hover {
            background: #ef4444;
            color: white;
        }
        
        .payment-methods-back-button-container {
            text-align: left;
            margin-bottom: 20px;
            padding: 0 20px;
        }
        
        .payment-methods-btn-secondary {
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
        
        .payment-methods-btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .no-payment-methods-message {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }
        
        .no-payment-methods-icon {
            font-size: 64px;
            color: #cbd5e0;
            margin-bottom: 20px;
        }
        
        .no-payment-methods-title {
            font-size: 24px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 10px;
        }
        
        .no-payment-methods-subtitle {
            font-size: 16px;
            color: #64748b;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .payment-methods-header {
                padding: 30px 20px;
            }
            
            .payment-methods-body {
                padding: 30px 20px;
            }
            
            .payment-method-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }
            
            .payment-method-status {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .payment-method-last-used {
                text-align: left;
            }
        }
        
        @media (max-width: 576px) {
            .payment-methods-container {
                padding: 20px 0;
            }
            
            .payment-methods-card {
                margin: 0 10px 20px;
                border-radius: 15px;
            }
            
            .payment-methods-header {
                padding: 25px 15px;
            }
            
            .payment-methods-body {
                padding: 25px 15px;
            }
        }
        
        /* Animation Classes */
        @keyframes paymentMethodsFadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .payment-methods-card {
            animation: paymentMethodsFadeInUp 0.6s ease-out;
        }
        
        .payment-method-card {
            animation: paymentMethodsFadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }
        
        .payment-method-card:nth-child(1) { animation-delay: 0.1s; }
        .payment-method-card:nth-child(2) { animation-delay: 0.2s; }
        .payment-method-card:nth-child(3) { animation-delay: 0.3s; }
        .payment-method-card:nth-child(4) { animation-delay: 0.4s; }
        .payment-method-card:nth-child(5) { animation-delay: 0.5s; }
    </style>

    <!-- Page title start -->
    
    <section class="bookings-hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">{{ __('frontend.my_payment_methods') }}</h1>
            </div>
        </div>
    </section>
    <!-- Page title end -->
    <!-- Page content start -->
    <div class="innerpagewrap">
        <div class="container">
           
            
            <div class="row">
            <div class="col-lg-3">
                    @include('components.user-sidebar')
                </div>
                <div class="col-lg-9">
                   
                     

                        <!-- Payment Methods Body -->
                    
                            <?php $payments = App\Models\Hotels::where('user_id', auth()->user()->id)->whereNotNull('payment_via')->get(); ?>
                            
                            @if($payments->count() > 0)
                                <div class="row">
                                    @foreach($payments as $key => $payment)
                                        <div class="col-lg-6">
                                            <div class="payment-method-card">
                                                <div class="payment-method-content">
                                                    <div class="payment-method-info">
                                                        <div class="payment-method-icon 
                                                            @if($payment->payment_via == 'Credit Card' || $payment->payment_via == 'stripe') payment-method-visa
                                                            @elseif($payment->payment_via == 'paypal') payment-method-paypal
                                                            @elseif($payment->payment_via == 'paystack' || $payment->payment_via == 'Paystack') payment-method-paystack
                                                            @else payment-method-default
                                                            @endif">
                                                            @if($payment->payment_via == 'Credit Card' || $payment->payment_via == 'stripe')
                                                                <i class="fab fa-cc-visa"></i>
                                                            @elseif($payment->payment_via == 'paypal')
                                                                <i class="fab fa-cc-paypal"></i>
                                                            @elseif($payment->payment_via == 'paystack' || $payment->payment_via == 'Paystack')
                                                                <i class="fas fa-credit-card"></i>
                                                            @else
                                                                <i class="fas fa-credit-card"></i>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="payment-method-details">
                                                            <h5>
                                                                @if($payment->payment_via == 'Credit Card' || $payment->payment_via == 'stripe')
                                                                    Visa Credit Card
                                                                @elseif($payment->payment_via == 'paypal')
                                                                    PayPal Account
                                                                @elseif($payment->payment_via == 'paystack' || $payment->payment_via == 'Paystack')
                                                                    Paystack
                                                                @else
                                                                    {{ ucfirst($payment->payment_via ?: 'Credit Card') }}
                                                                @endif
                                                            </h5>
                                                            <p>Payment Method #{{ $key+1 }}</p>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="payment-method-status">
                                                        <div class="payment-method-last-used">
                                                            <div class="payment-method-last-used-label">Last Used</div>
                                                            <div class="payment-method-last-used-value">
                                                                {{ \Carbon\Carbon::parse($payment->updated_at)->format('M d, Y') }}
                                                            </div>
                                                        </div>
                                                        
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                               
                            @else
                                <div class="no-payment-methods-message">
                                    <div class="no-payment-methods-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="no-payment-methods-title">No Payment Methods</div>
                                    <div class="no-payment-methods-subtitle">You haven't added any payment methods yet. Add one to make future bookings easier.</div>
                                    
                                    <div class="mt-4">
                                        <button class="payment-method-btn payment-method-btn-primary" style="padding: 15px 30px; font-size: 16px;">
                                            <i class="fas fa-plus me-2"></i>Add Your First Payment Method
                                        </button>
                                    </div>
                                </div>
                            @endif
                        


                  
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
