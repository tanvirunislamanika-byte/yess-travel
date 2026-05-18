<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $siteSettings = widget(1);
        View::share([
            'siteSettings' => $siteSettings
        ]);

        $mailSettings = widget(26);
        
        if ($mailSettings) {
            Config::set('mail.mailers.smtp.host', $mailSettings->extra_field_1);
            Config::set('mail.mailers.smtp.port', $mailSettings->extra_field_2);
            Config::set('mail.mailers.smtp.username', $mailSettings->extra_field_3);
            Config::set('mail.mailers.smtp.password', $mailSettings->extra_field_4);
            Config::set('mail.mailers.smtp.encryption', $mailSettings->extra_field_5);
            Config::set('mail.from.address', $mailSettings->extra_field_6);
            Config::set('mail.from.name', $mailSettings->extra_field_7);
        }


        // Stripe config from admin (e.g., widget 28)
        $stripeSettings = widget(28);
        if ($stripeSettings) {
            Config::set('services.stripe.key', $stripeSettings->extra_field_1);     // Stripe Key
            Config::set('services.stripe.secret', $stripeSettings->extra_field_2);  // Stripe Secret
        }

        $paypalSettings = widget(27);
        if ($paypalSettings) {
            Config::set('services.paypal.mode', $paypalSettings->extra_field_3);       
            Config::set('services.paypal.client_id', $paypalSettings->extra_field_1);  
            Config::set('services.paypal.client_secret', $paypalSettings->extra_field_2);
        }

        // Paystack config from admin (widget 32)
        $paystackSettings = widget(32);
        if ($paystackSettings) {
            Config::set('services.paystack.public_key', $paystackSettings->extra_field_1);     // Paystack Public Key
            Config::set('services.paystack.secret_key', $paystackSettings->extra_field_2);    // Paystack Secret Key
            Config::set('services.paystack.merchant_email', $paystackSettings->extra_field_3); // Paystack Merchant Email
        }



    }
}
