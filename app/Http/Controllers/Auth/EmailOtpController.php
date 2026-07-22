<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailOtpController extends Controller
{
    /**
     * Show OTP verification page.
     */
    public function show()
    {
        $user = Auth::user();

        // Already verified -> customer account dashboard
        if ($user->email_verified_at) {
            return redirect()->route('account.dashboard');
        }

        return view('auth.verify-email-otp');
    }

    /**
     * Send / resend OTP to user's email.
     */
    public function send()
    {
        $user = Auth::user();

        // Already verified -> customer account dashboard
        if ($user->email_verified_at) {
            return redirect()->route('account.dashboard');
        }

        // Generate secure 6-digit OTP
        $otp = (string) random_int(100000, 999999);

        // Save OTP with 10-minute expiration
        $user->email_otp = $otp;
        $user->email_otp_expires_at = now()->addMinutes(10);
        $user->save();

        // Send OTP email
        Mail::to($user->email)->send(
            new EmailOtpMail($otp)
        );

        return redirect()
            ->route('email.otp.show')
            ->with('success', 'A verification code has been sent to your email.');
    }

    /**
     * Verify submitted OTP.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = Auth::user();

        // OTP does not exist
        if (!$user->email_otp || !$user->email_otp_expires_at) {
            return back()->withErrors([
                'otp' => 'Please request a new verification code.',
            ]);
        }

        // OTP expired
        if (now()->greaterThan($user->email_otp_expires_at)) {
            $user->email_otp = null;
            $user->email_otp_expires_at = null;
            $user->save();

            return back()->withErrors([
                'otp' => 'The verification code has expired. Please request a new code.',
            ]);
        }

        // OTP incorrect
        if (!hash_equals(
            (string) $user->email_otp,
            (string) $request->otp
        )) {
            return back()->withErrors([
                'otp' => 'The verification code is incorrect.',
            ]);
        }

        // OTP correct -> verify email
        $user->email_verified_at = now();
        $user->email_otp = null;
        $user->email_otp_expires_at = null;
        $user->save();

        return redirect()
            ->route('account.dashboard')
            ->with(
                'success',
                'Your email has been verified successfully.'
            );
    }
}