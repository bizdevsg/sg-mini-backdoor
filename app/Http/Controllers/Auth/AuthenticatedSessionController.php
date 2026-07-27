<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Support\SystemActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly SystemActivityLogger $systemActivityLogger,
    ) {
    }

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $this->systemActivityLogger->log(
            category: 'login',
            event: 'login_success',
            description: 'Login admin berhasil.',
            subject: 'admin-auth',
            user: $request->user(),
            request: $request,
        );

        return redirect()->intended(
            route($request->user()->adminLandingRouteName(), absolute: false)
        );
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user !== null) {
            $this->systemActivityLogger->log(
                category: 'login',
                event: 'logout',
                description: 'Logout admin berhasil.',
                subject: 'admin-auth',
                user: $user,
                request: $request,
            );
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
