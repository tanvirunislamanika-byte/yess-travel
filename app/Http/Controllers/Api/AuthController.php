<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRules;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', PasswordRules::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Generate and send verification code
            try {
                $user->generateAndSendVerificationCode();

                // Add verification info to response
                $verificationSent = true;
                $verificationMessage = 'Verification code sent successfully to your email';

            } catch (\Exception $e) {
                // Continue with registration even if verification fails
                $verificationSent = false;
                $verificationMessage = 'Registration successful but verification code failed to send';
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'verification' => [
                        'sent' => $verificationSent ?? false,
                        'message' => $verificationMessage ?? 'Verification status unknown',
                        'type' => 'otp'
                    ]
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed'
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => ['required', 'confirmed', PasswordRules::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Do not reveal that the email does not exist
            return response()->json([
                'success' => true,
                'message' => 'If a user with that email address exists, we have sent a verification code.'
            ]);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(15);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($otp),
                'created_at' => Carbon::now()
            ]
        );

        try {
            Mail::to($user->email)->send(new PasswordResetMail($user, $otp, $expiresAt));

            return response()->json([
                'success' => true,
                'message' => 'A verification code has been sent to your email.',
                'expires_in' => $expiresAt->diffInMinutes(Carbon::now())
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification code. Please try again later.',
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
            'password' => ['required', 'confirmed', PasswordRules::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $resetRecord = DB::table('password_resets')->where('email', $request->email)->first();

        if (!$resetRecord) {
            return response()->json([
                'success' => false,
                'message' => 'No verification code found. Please request a new reset code.'
            ], 400);
        }

        if (!Hash::check($request->otp, $resetRecord->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.'
            ], 400);
        }

        if (Carbon::parse($resetRecord->created_at)->addMinutes(15)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Verification code has expired. Please request a new one.'
            ], 400);
        }

        $user->forceFill([
            'password' => Hash::make($request->password)
        ])->setRememberToken(Str::random(60));

        $user->save();

        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ]);
    }

    /**
     * Verify registration OTP
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        if ($user->is_verified) {
            return response()->json([
                'success' => true,
                'message' => 'Email is already verified.'
            ]);
        }

        if ($user->verification_code !== $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.'
            ], 400);
        }

        if ($user->verification_code_expires_at && Carbon::now()->isAfter($user->verification_code_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Verification code has expired. Please request a new one.'
            ], 400);
        }

        $user->update([
            'email_verified_at' => Carbon::now(),
            'verification_code' => null,
            'verification_code_expires_at' => null,
            'is_verified' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully.'
        ]);
    }

    /**
     * Resend registration OTP
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        if ($user->is_verified) {
            return response()->json([
                'success' => true,
                'message' => 'Email is already verified.'
            ]);
        }

        $user->generateAndSendVerificationCode();

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent successfully.',
            'expires_in' => 15
        ]);
    }

    /**
     * Verify forgot password OTP
     */
    public function verifyForgotPasswordOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $record = DB::table('password_resets')->where('email', $request->email)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'No verification code found. Please request a new reset code.'
            ], 400);
        }

        if (!Hash::check($request->otp, $record->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.'
            ], 400);
        }

        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Verification code has expired. Please request a new one.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verification code is valid.',
            'expires_in' => Carbon::parse($record->created_at)->addMinutes(15)->diffInMinutes(Carbon::now())
        ]);
    }

    /**
     * Resend email verification
     */
    public function resendVerificationEmail(Request $request)
    {
        try {
            $user = $request->user();
            
            if ($user->is_verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email is already verified'
                ], 400);
            }

            $user->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'message' => 'Verification email sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email',
            ], 500);
        }
    }

    /**
     * Verify email address
     */
    public function verifyEmail(Request $request)
    {
        try {
            $user = User::find($request->id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            if ($user->is_verified) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email is already verified'
                ]);
            }

            // Verify the hash
            $hash = sha1($user->getEmailForVerification());

            if (!hash_equals($hash, $request->hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid verification link'
                ], 400);
            }

            $user->markEmailAsVerified();

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify email',
            ], 500);
        }
    }

    /**
     * Check email verification status
     */
    public function checkEmailVerification(Request $request)
    {
        try {
            $user = $request->user();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'email_verified' => $user->is_verified,
                    'email_verified_at' => $user->email_verified_at,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check email verification status'
            ], 500);
        }
    }

    /**
     * Test email verification (for debugging)
     */
    public function testEmailVerification(Request $request)
    {
        try {
            $user = $request->user();

            // Send verification email
            $user->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'message' => 'Test verification email sent',
                'data' => [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'email_verified' => $user->is_verified,
                    'test_sent_at' => now()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test email verification failed'
            ], 500);
        }
    }

    /**
     * Test basic email functionality (for debugging)
     */
    public function testBasicEmail(Request $request)
    {
        try {
            $user = $request->user();

            // Send a simple test email
            Mail::raw('This is a test email from TravelIn API', function($message) use ($user) {
                $message->to($user->email)
                        ->subject('TravelIn API Email Test')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully',
                'data' => [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'test_sent_at' => now(),
                    'mail_config' => [
                        'from_address' => config('mail.from.address'),
                        'from_name' => config('mail.from.name'),
                        'mailer' => config('mail.default')
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Basic email test failed'
            ], 500);
        }
    }

    /**
     * Manually send verification email for testing
     */
    public function sendVerificationEmailForUser(Request $request)
    {
        try {
            $email = $request->email;
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Send verification email
            $user->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'message' => 'Verification email sent successfully',
                'data' => [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'email_verified' => $user->is_verified,
                    'sent_at' => now()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email'
            ], 500);
        }
    }
} 