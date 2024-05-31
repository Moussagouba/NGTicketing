<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class NormalUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $UserRole = Auth::user()->role;
        // super admin
        if ($UserRole == 1) {
            return redirect()->route('super-admin.dashboard');
        }
        // admin user
        elseif ($UserRole == 2) {
            return redirect()->route('admin.dashboard');
        }
        // normal user
        elseif ($UserRole == 3) {
            return $next($request);
        }
    }
}
