<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PhoneOtpController extends Controller
{
    /**
     * Show phone verification page.
     */
    public function show()
    {
        $user = Auth::user();

        if ($user->phone_verified_at) {
            return redirect()->route('account.dashboard');
        }

        return view('auth.verify-phone-otp');
    }

    /**
     * Save phone number and send/resend OTP.
     */


    public function send(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'phone' => [
                'required',
                'string',
                'max:30',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
        ], [
            'phone.unique' => 'This phone number is already registered with another account.',
        ]);

        // If phone number changed, it must be verified again.
        if ($user->phone !== $request->phone) {
            $user->phone_verified_at = null;
        }

        // Generate secure 6-digit OTP.
        $otp = (string) random_int(100000, 999999);

        $user->phone = $request->phone;
        $user->phone_otp = $otp;
        $user->phone_otp_expires_at = now()->addMinutes(10);
        $user->save();

        /*
         * LOCAL TESTING:
         * Write OTP to storage/logs/laravel.log.
         *
         * Later we will replace this with a real SMS provider.
         */
        Log::info('Budlume Phone OTP', [
            'user_id' => $user->id,
            'phone' => $user->phone,
            'otp' => $otp,
        ]);

        return redirect()
            ->route('phone.otp.show')
            ->with(
                'success',
                'A 6-digit verification code has been generated for your phone.'
            );
    }

    /**
     * Verify submitted phone OTP.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = Auth::user();

        if (!$user->phone_otp || !$user->phone_otp_expires_at) {
            return back()->withErrors([
                'otp' => 'Please request a new verification code.',
            ]);
        }

        if (now()->greaterThan($user->phone_otp_expires_at)) {
            $user->phone_otp = null;
            $user->phone_otp_expires_at = null;
            $user->save();

            return back()->withErrors([
                'otp' => 'The verification code has expired. Please request a new code.',
            ]);
        }

        if (!hash_equals(
            (string) $user->phone_otp,
            (string) $request->otp
        )) {
            return back()->withErrors([
                'otp' => 'The verification code is incorrect.',
            ]);
        }

        // Phone successfully verified.
        $user->phone_verified_at = now();
        $user->phone_otp = null;
        $user->phone_otp_expires_at = null;
        $user->save();

        return redirect()
            ->route('account.dashboard')
            ->with(
                'success',
                'Your phone number has been verified successfully.'
            );
    }
}