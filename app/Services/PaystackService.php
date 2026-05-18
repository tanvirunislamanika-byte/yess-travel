<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    private $secretKey;
    private $publicKey;
    private $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key');
        $this->publicKey = config('services.paystack.public_key');
        $this->baseUrl = config('services.paystack.url', 'https://api.paystack.co');
        
        Log::info('PaystackService initialized', [
            'secret_key_exists' => !empty($this->secretKey),
            'public_key_exists' => !empty($this->publicKey),
            'base_url' => $this->baseUrl,
        ]);
    }

    /**
     * Initialize a transaction
     */
    public function initializeTransaction($data)
    {
        try {
            Log::info('Initializing Paystack transaction', [
                'email' => $data['email'],
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'reference' => $data['reference'],
            ]);

            if (empty($this->secretKey)) {
                Log::error('Paystack secret key not configured');
                return [
                    'success' => false,
                    'message' => 'Payment gateway not configured',
                ];
            }

            // Ensure amount is in kobo (smallest currency unit)
            $amountInKobo = $data['amount'] * 100;
            
            // Validate currency
            if ($data['currency'] !== 'NGN') {
                Log::warning('Non-NGN currency detected, converting to NGN', [
                    'original_currency' => $data['currency'],
                    'amount' => $data['amount'],
                ]);
            }
            
            $payload = [
                'email' => $data['email'],
                'amount' => $amountInKobo,
                'currency' => $data['currency'] ?? 'NGN',
                'reference' => $data['reference'],
                'callback_url' => $data['callback_url'],
                'metadata' => $data['metadata'] ?? [],
            ];

            Log::info('Paystack API payload', $payload);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/transaction/initialize', $payload);

            Log::info('Paystack API response', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if ($responseData['status']) {
                    return [
                        'success' => true,
                        'authorization_url' => $responseData['data']['authorization_url'],
                        'reference' => $responseData['data']['reference'],
                        'access_code' => $responseData['data']['access_code'],
                    ];
                } else {
                    Log::error('Paystack API returned false status', $responseData);
                    return [
                        'success' => false,
                        'message' => $responseData['message'] ?? 'Failed to initialize transaction',
                    ];
                }
            }

            $responseData = $response->json();
            $errorMessage = $responseData['message'] ?? 'Failed to initialize transaction';
            
            // Handle specific currency errors
            if (str_contains(strtolower($errorMessage), 'currency') || str_contains(strtolower($errorMessage), 'not supported')) {
                $errorMessage = 'Currency not supported. Please contact support or try a different payment method.';
            }
            
            Log::error('Paystack initialization failed', [
                'status' => $response->status(),
                'response' => $responseData,
                'data' => $data,
                'error_message' => $errorMessage
            ]);

            return [
                'success' => false,
                'message' => $errorMessage,
            ];
        } catch (\Exception $e) {
            Log::error('Paystack initialization exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while initializing payment',
            ];
        }
    }

    /**
     * Verify a transaction
     */
    public function verifyTransaction($reference)
    {
        try {
            Log::info('Verifying Paystack transaction', ['reference' => $reference]);

            if (empty($this->secretKey)) {
                Log::error('Paystack secret key not configured for verification');
                return [
                    'success' => false,
                    'message' => 'Payment gateway not configured',
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/transaction/verify/' . $reference);

            Log::info('Paystack verification response', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if ($responseData['status']) {
                    $transactionData = $responseData['data'];
                    
                    Log::info('Paystack transaction verified successfully', [
                        'reference' => $reference,
                        'status' => $transactionData['status'],
                        'amount' => $transactionData['amount'],
                        'currency' => $transactionData['currency'],
                    ]);

                    return [
                        'success' => true,
                        'status' => $transactionData['status'],
                        'data' => $transactionData,
                        'amount' => $transactionData['amount'] / 100, // Convert from kobo
                        'currency' => $transactionData['currency'],
                        'gateway_ref' => $transactionData['reference'],
                        'paid_at' => $transactionData['paid_at'],
                    ];
                } else {
                    Log::error('Paystack verification returned false status', $responseData);
                    return [
                        'success' => false,
                        'message' => $responseData['message'] ?? 'Transaction verification failed',
                    ];
                }
            }

            $responseData = $response->json();
            Log::error('Paystack verification failed', [
                'status' => $response->status(),
                'response' => $responseData,
                'reference' => $reference,
            ]);

            return [
                'success' => false,
                'message' => $responseData['message'] ?? 'Transaction verification failed',
            ];
        } catch (\Exception $e) {
            Log::error('Paystack verification exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'reference' => $reference,
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while verifying payment',
            ];
        }
    }

    /**
     * Get transaction details
     */
    public function getTransaction($reference)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/transaction/' . $reference);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()['data'],
                ];
            }

            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Failed to get transaction details',
            ];
        } catch (\Exception $e) {
            Log::error('Paystack get transaction error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while getting transaction details',
            ];
        }
    }

    /**
     * Generate a unique reference
     */
    public function generateReference($prefix = 'PSK')
    {
        return $prefix . '_' . uniqid() . '_' . time();
    }

    /**
     * Get public key for frontend
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function getSupportedCurrencies()
    {
        return ['NGN']; // Paystack primarily supports NGN
    }

    public function convertCurrency($amount, $fromCurrency, $toCurrency = 'NGN')
    {
        // Simple conversion rates (in production, use a real-time exchange rate API)
        $rates = [
            'USD' => 1500, // 1 USD = 1500 NGN
            'EUR' => 1650, // 1 EUR = 1650 NGN
            'GBP' => 1900, // 1 GBP = 1900 NGN
        ];

        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        if (isset($rates[$fromCurrency])) {
            return $amount * $rates[$fromCurrency];
        }

        // Default fallback
        return $amount * 1500; // Assume USD rate
    }
} 