<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Adjust 'is_admin' based on your users table
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}