<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\VerificationCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VerificationController extends Controller
{
    /**
     * Show the email verification page with 6-digit code input.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $user = $request->user();
        
        if ($user->is_verified) {
            return redirect()->route('dashboard');
        }

        return view('auth.verify-email', compact('user'));
    }

    /**
     * Verify the user's email with the 6-digit code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'code' => 'required|string|size:6'
        ]);

        $user = User::findOrFail($request->user_id);
        
        // Check if user is already verified
        if ($user->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'Email is already verified.'
            ]);
        }

        // Check if verification code matches and is not expired
        if ($user->verification_code !== $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.'
            ]);
        }

        if ($user->verification_code_expires_at && Carbon::now()->isAfter($user->verification_code_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Verification code has expired. Please request a new one.'
            ]);
        }

        // Mark email as verified
        $user->update([
            'email_verified_at' => Carbon::now(),
            'verification_code' => null,
            'verification_code_expires_at' => null,
            'is_verified' => 1
        ]);

        // Log the verification
        Log::info('User email verified successfully', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully! Redirecting to dashboard...',
            'redirect_url' => route('dashboard')
        ]);
    }

    /**
     * Resend the verification code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->user_id);
        
        // Check if user is already verified
        if ($user->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'Email is already verified.'
            ]);
        }

        // Generate new verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(15);

        // Update user with new verification code
        $user->update([
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => $expiresAt
        ]);

        try {
            // Send new verification code
            $user->notify(new VerificationCodeNotification($verificationCode));
            
            Log::info('Verification code resent successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send verification code', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification code. Please try again.'
            ]);
        }
    }
}
