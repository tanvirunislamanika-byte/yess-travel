<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $resetUrl = url(
                route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false)
            );

            return (new MailMessage)
                ->subject(__('Reset Your :app Password', ['app' => config('app.name')]))
                ->view('emails.password-reset-link', [
                    'user' => $notifiable,
                    'resetUrl' => $resetUrl,
                    'appName' => config('app.name'),
                    'supportEmail' => config('mail.from.address'),
                    'expiryMinutes' => config('auth.passwords.' . config('fortify.passwords') . '.expire', 60),
                ]);
        });
    }
}
