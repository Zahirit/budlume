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
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // User must be logged in
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Check required role
        if ($request->user()->role !== $role) {

            // Admin should go to Admin Dashboard
            if ($request->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Customer should go to Customer Dashboard
            return redirect()->route('account.dashboard');
        }

        return $next($request);
    }
}