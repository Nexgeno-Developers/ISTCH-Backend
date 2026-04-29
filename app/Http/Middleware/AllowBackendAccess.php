<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowBackendAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Check if the app is in production
        // if (app()->environment('production')) {
        //     // Get the allowed domain from config
        //     $allowedDomain = config('custom.backend_access_domain');

        //     // Get the current request host
        //     $currentHost = $request->getHost();

        //     // Deny access if the current host is not the allowed domain
        //     if ($allowedDomain && $currentHost !== $allowedDomain) {
        //         abort(403, 'Unauthorized access.');
        //     }
        // }

        return $next($request);
    }
}
