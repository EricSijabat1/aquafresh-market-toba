<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // Jika request menuju ke path admin, arahkan ke login admin
            if ($request->routeIs('admin.*')) {
                return route('admin.login');
            }

            // Jika tidak, arahkan ke login default
            return route('login');
        }
        
        return null;
    }
}
