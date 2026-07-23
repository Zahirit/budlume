<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        string $role
    ): Response
    {
        // User must be logged in
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Correct role - allow request
        if ($request->user()->role === $role) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Wrong dashboard - redirect user to their own dashboard
        |--------------------------------------------------------------------------
        */

        return match ($request->user()->role) {

            'admin' => redirect()
                ->route('admin.dashboard'),

            'delivery' => redirect()
                ->route('delivery.dashboard'),

            'customer' => redirect()
                ->route('account.dashboard'),

            default => redirect()
                ->route('home'),
        };
    }
}