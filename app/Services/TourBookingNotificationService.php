<?php

namespace App\Services;

use App\Models\TourBooking;
use App\Models\User;
use App\Notifications\TourBookingConfirmation;
use App\Notifications\AdminTourBookingNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class TourBookingNotificationService
{
    /**
     * Send notifications for a new tour booking
     *
     * @param TourBooking $tourBooking
     * @return void
     */
    public function sendBookingNotifications(TourBooking $tourBooking)
    {
        try {
            // Send confirmation email to the user who made the booking
            $this->sendUserConfirmation($tourBooking);
            
            // Send notification to all super admins
            $this->sendAdminNotifications($tourBooking);
            
            Log::info('Tour booking notifications sent successfully', [
                'booking_id' => $tourBooking->id,
                'user_id' => $tourBooking->user_id,
                'tour_id' => $tourBooking->tour_id
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send tour booking notifications', [
                'booking_id' => $tourBooking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Send confirmation email to the user
     *
     * @param TourBooking $tourBooking
     * @return void
     */
    protected function sendUserConfirmation(TourBooking $tourBooking)
    {
        try {
            // Configure SMTP settings from widget
            $this->configureSmtpSettings();
            
            $user = $tourBooking->user;
            
            if ($user && $user->email) {
                $user->notify(new TourBookingConfirmation($tourBooking));
                
                Log::info('User confirmation email sent', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'booking_id' => $tourBooking->id
                ]);
            } else {
                Log::warning('Cannot send user confirmation - user or email not found', [
                    'booking_id' => $tourBooking->id,
                    'user_id' => $tourBooking->user_id
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to send user confirmation email', [
                'booking_id' => $tourBooking->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send notifications to all super admins
     *
     * @param TourBooking $tourBooking
     * @return void
     */
    protected function sendAdminNotifications(TourBooking $tourBooking)
    {
        try {
            Log::info('Starting admin notification process', [
                'booking_id' => $tourBooking->id
            ]);

            // Get all super admin users (you can adjust this based on your user roles)
            $superAdmins = User::where('role', 1)  // role = 1 for admin
                ->get();
            
            Log::info('Found admin users', [
                'booking_id' => $tourBooking->id,
                'admin_count' => $superAdmins->count(),
                'admin_emails' => $superAdmins->pluck('email')->toArray()
            ]);
            
            if ($superAdmins->isEmpty()) {
                Log::info('No admin users found, using fallback emails', [
                    'booking_id' => $tourBooking->id
                ]);
                
                // Fallback: send to specific admin emails
                $adminEmails = config('admin.emails', []);
                
                Log::info('Admin emails from config', [
                    'booking_id' => $tourBooking->id,
                    'admin_emails' => $adminEmails
                ]);
                
                if (!empty($adminEmails)) {
                    Log::info('Processing admin emails', [
                        'booking_id' => $tourBooking->id,
                        'admin_emails' => $adminEmails
                    ]);
                    
                    foreach ($adminEmails as $email) {
                        if ($email && $email !== 'admin@example.com' && $email !== 'support@example.com') {
                            Log::info('Attempting to send admin notification', [
                                'booking_id' => $tourBooking->id,
                                'email' => $email
                            ]);
                            
                            $this->sendNotificationToEmail($tourBooking, $email);
                            
                            Log::info('Admin notification sent to email', [
                                'booking_id' => $tourBooking->id,
                                'email' => $email
                            ]);
                        } else {
                            Log::warning('Skipping default admin email', [
                                'booking_id' => $tourBooking->id,
                                'email' => $email
                            ]);
                        }
                    }
                } else {
                    Log::warning('No admin emails configured, sending to first user as fallback', [
                        'booking_id' => $tourBooking->id
                    ]);
                    
                    // Configure SMTP settings from widget
                    $this->configureSmtpSettings();
                    
                    // Send to the first user found (fallback)
                    $fallbackUser = User::first();
                    if ($fallbackUser) {
                        $fallbackUser->notify(new AdminTourBookingNotification($tourBooking));
                        Log::info('Fallback notification sent to first user', [
                            'booking_id' => $tourBooking->id,
                            'user_email' => $fallbackUser->email
                        ]);
                    }
                }
            } else {
                // Configure SMTP settings from widget
                $this->configureSmtpSettings();
                
                // Send to all super admins
                foreach ($superAdmins as $admin) {
                    if ($admin->email) {
                        $admin->notify(new AdminTourBookingNotification($tourBooking));
                        Log::info('Admin notification sent to user', [
                            'booking_id' => $tourBooking->id,
                            'admin_email' => $admin->email,
                            'admin_role' => $admin->role
                        ]);
                    }
                }
                
                Log::info('Admin notifications sent', [
                    'booking_id' => $tourBooking->id,
                    'admin_count' => $superAdmins->count()
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to send admin notifications', [
                'booking_id' => $tourBooking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Send notification to a specific email address
     *
     * @param TourBooking $tourBooking
     * @param string $email
     * @return void
     */
    protected function sendNotificationToEmail(TourBooking $tourBooking, string $email)
    {
        try {
            // Configure SMTP settings from widget
            $this->configureSmtpSettings();
            
            // Create a temporary user object for the email
            $tempUser = new User();
            $tempUser->email = $email;
            $tempUser->name = 'Admin';
            
            $tempUser->notify(new AdminTourBookingNotification($tourBooking));
            
            Log::info('Admin notification sent to email', [
                'email' => $email,
                'booking_id' => $tourBooking->id
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send admin notification to email', [
                'email' => $email,
                'booking_id' => $tourBooking->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Configure SMTP settings from widget
     */
    protected function configureSmtpSettings()
    {
        try {
            $mailSettings = widget(26);
            
            if ($mailSettings) {
                \Config::set('mail.mailers.smtp.host', $mailSettings->extra_field_1);
                \Config::set('mail.mailers.smtp.port', $mailSettings->extra_field_2);
                \Config::set('mail.mailers.smtp.username', $mailSettings->extra_field_3);
                \Config::set('mail.mailers.smtp.password', $mailSettings->extra_field_4);
                \Config::set('mail.mailers.smtp.encryption', $mailSettings->extra_field_5);
                \Config::set('mail.from.address', $mailSettings->extra_field_6);
                \Config::set('mail.from.name', $mailSettings->extra_field_7);
                
                Log::info('SMTP settings configured from widget', [
                    'host' => $mailSettings->extra_field_1,
                    'port' => $mailSettings->extra_field_2,
                    'username' => $mailSettings->extra_field_3,
                    'from_address' => $mailSettings->extra_field_6,
                    'from_name' => $mailSettings->extra_field_7
                ]);
            } else {
                Log::warning('No mail settings found in widget 26');
            }
        } catch (\Exception $e) {
            Log::error('Failed to configure SMTP settings', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send status update notification to user
     *
     * @param TourBooking $tourBooking
     * @param string $oldStatus
     * @param string $newStatus
     * @return void
     */
    public function sendStatusUpdateNotification(TourBooking $tourBooking, string $oldStatus, string $newStatus)
    {
        try {
            // Configure SMTP settings from widget
            $this->configureSmtpSettings();
            
            $user = $tourBooking->user;
            
            if ($user && $user->email) {
                // You can create a separate notification class for status updates
                // For now, we'll use the existing confirmation notification
                $user->notify(new TourBookingConfirmation($tourBooking));
                
                Log::info('Status update notification sent', [
                    'booking_id' => $tourBooking->id,
                    'user_id' => $user->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to send status update notification', [
                'booking_id' => $tourBooking->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
