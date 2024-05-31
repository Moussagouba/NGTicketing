<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();
    //     $loggedInUserRole = $request->user->role;
    //     //super admin
    //     if ($loggedInUserRole == 1) {
    //         return redirect()->intended(route('super-admin.dashboard', absolute: false));
    //     }
    //     // admin
    //     elseif ($loggedInUserRole == 2) {
    //         return redirect()->intended(route('admin.dashboard', absolute: false));
    //     }
    //     // normal user
    //     return redirect()->intended(RouteServiceProvider::HOME);
    // }
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticating the user
        if (auth()->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            $loggedInUserRole = auth()->user()->role;

            // Redirect based on user role
            if ($loggedInUserRole == 1) {
                return redirect()->route('super-admin.dashboard');
            } elseif ($loggedInUserRole == 2) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        // Handle authentication failure
        return redirect()->back()->withInput()->withErrors(['email' => 'Invalid credentials']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
