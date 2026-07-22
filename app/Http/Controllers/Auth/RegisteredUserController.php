<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate registration form
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
                'unique:users,phone',
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        // Generate secure 6-digit mobile OTP
        $otp = (string) random_int(100000, 999999);

        // Create registered customer account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),

            // Public registrations are customer accounts
            'role' => 'customer',

            // Mobile OTP verification
            'phone_otp' => $otp,
            'phone_otp_expires_at' => now()->addMinutes(10),
        ]);

        // Login newly registered customer
        Auth::login($user);

        /*
         * LOCAL TESTING ONLY
         *
         * Save OTP in:
         * storage/logs/laravel.log
         *
         * Later we will replace this with a real SMS provider.
         */
        Log::info('Budlume Registration Phone OTP', [
            'user_id' => $user->id,
            'phone' => $user->phone,
            'otp' => $otp,
        ]);

        // Redirect customer to mobile OTP verification page
        return redirect()
            ->route('phone.otp.show')
            ->with(
                'success',
                'A 6-digit verification code has been generated for your mobile number.'
            );
    }
}