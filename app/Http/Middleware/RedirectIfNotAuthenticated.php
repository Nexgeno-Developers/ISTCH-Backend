<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('backend.login');
        }

        // Check role (Admin = 1)
        if (Auth::user()->role_id != 1) {
            abort(403, 'Unauthorized access');
        }        

        return $next($request);
    }
}