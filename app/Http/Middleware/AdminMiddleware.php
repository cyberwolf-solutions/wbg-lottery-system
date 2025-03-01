<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware {
    public function handle($request, Closure $next) {
        if (!Auth::guard('admin')->check()) {
            return redirect('/api/admin/login');
        }
        return $next($request);
    }
}
