<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $UserRole = Auth::user()->role;
        // super admin
        if ($UserRole == 1) {
            return $next($request);
        }
        // admin user
        elseif ($UserRole == 2) {
            return redirect()->route('admin.dashboard');
        }
        // normal user
        elseif ($UserRole == 3) {
            return redirect()->route('dashboard');
        }
    }
}
