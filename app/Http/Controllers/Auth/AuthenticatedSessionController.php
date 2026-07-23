<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Admin Login
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            return redirect()->intended(
                route(
                    'admin.dashboard',
                    absolute: false
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Delivery Partner Login
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'delivery') {

            $deliveryProfile = $user->deliveryProfile;

            /*
            | No delivery profile found
            */
            if (!$deliveryProfile) {

                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' =>
                            'Delivery profile could not be found.',
                    ]);
            }

            /*
            | Pending Admin Approval
            */
            if (
                $deliveryProfile->approval_status
                === 'pending'
            ) {

                return redirect()
                    ->route('delivery.pending');
            }

            /*
            | Rejected Application
            */
            if (
                $deliveryProfile->approval_status
                === 'rejected'
            ) {

                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' =>
                            'Your delivery partner application has been rejected. Please contact the administrator.',
                    ]);
            }

            /*
            | Approved Delivery Partner
            */
            if (
                $deliveryProfile->approval_status
                === 'approved'
            ) {

                return redirect()
                    ->intended(
                        route(
                            'delivery.dashboard',
                            absolute: false
                        )
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Normal Customer Login
        |--------------------------------------------------------------------------
        */

        return redirect()->intended(
            route(
                'dashboard',
                absolute: false
            )
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}