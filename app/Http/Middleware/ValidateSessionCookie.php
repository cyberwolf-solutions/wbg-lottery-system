<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class ValidateSessionCookie
{
    public function handle($request, Closure $next)
    {
        // Check if session cookie exists but is invalid
        if ($request->hasSession() && !$request->session()->isValidId($request->session()->getId())) {
            return redirect()->route('login')->withError('Session expired, please log in again');
        }
        

        return $next($request);
    }
}
