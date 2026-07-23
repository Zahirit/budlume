<?php

namespace App\Http\Controllers\Delivery\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeliveryPhoneOtpController extends Controller
{
    /**
     * Get the delivery user currently going through registration.
     */
    private function getDeliveryUser(): ?User
    {
        $userId = session('delivery_registration_user_id');

        if (!$userId) {
            return null;
        }

        return User::where('id', $userId)
            ->where('role', 'delivery')
            ->first();
    }

    /**
     * Show Delivery Mobile OTP verification page.
     */
    public function show()
    {
        $user = $this->getDeliveryUser();

        if (!$user) {
            return redirect()
                ->route('delivery.register')
                ->with('error', 'Your delivery registration session has expired. Please register again.');
        }

        // Already verified.
        if ($user->phone_verified_at) {
            session()->forget('delivery_registration_user_id');

            return redirect()
                ->route('login')
                ->with('success', 'Your mobile number is already verified. Your delivery account is awaiting administrator approval.');
        }

        return view(
            'delivery.auth.verify-phone-otp',
            compact('user')
        );
    }

    /**
     * Generate / resend Delivery Mobile OTP.
     */
    public function send(Request $request)
    {
        $user = $this->getDeliveryUser();

        if (!$user) {
            return redirect()
                ->route('delivery.register')
                ->with('error', 'Your delivery registration session has expired. Please register again.');
        }

        if ($user->phone_verified_at) {
            session()->forget('delivery_registration_user_id');

            return redirect()
                ->route('login')
                ->with('success', 'Your mobile number is already verified.');
        }

        $otp = (string) random_int(100000, 999999);

        $user->phone_otp = $otp;
        $user->phone_otp_expires_at = now()->addMinutes(10);
        $user->save();

        /*
         * LOCAL TESTING ONLY
         *
         * OTP is written to:
         * storage/logs/laravel.log
         *
         * Later we will replace this with a real SMS provider.
         */
        Log::info('Budlume Delivery Registration Phone OTP', [
            'user_id' => $user->id,
            'phone'   => $user->phone,
            'otp'     => $otp,
        ]);

        return redirect()
            ->route('delivery.phone.otp.show')
            ->with(
                'success',
                'A 6-digit verification code has been generated for your mobile number.'
            );
    }

    /**
     * Verify Delivery Mobile OTP.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => [
                'required',
                'digits:6',
            ],
        ]);

        $user = $this->getDeliveryUser();

        if (!$user) {
            return redirect()
                ->route('delivery.register')
                ->with('error', 'Your delivery registration session has expired. Please register again.');
        }

        if ($user->phone_verified_at) {
            session()->forget('delivery_registration_user_id');

            return redirect()
                ->route('login')
                ->with('success', 'Your mobile number is already verified.');
        }

        if (!$user->phone_otp || !$user->phone_otp_expires_at) {
            return back()->withErrors([
                'otp' => 'Please request a verification code first.',
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

        /*
         * Mobile verification successful.
         */
        $user->phone_verified_at = now();
        $user->phone_otp = null;
        $user->phone_otp_expires_at = null;
        $user->save();

        /*
         * Registration OTP process is complete.
         */
        session()->forget('delivery_registration_user_id');

        return redirect()
            ->route('delivery.pending')
            ->with(
                'success',
                'Mobile number verified successfully. Your delivery account is now waiting for administrator approval.'
            );
    }
}