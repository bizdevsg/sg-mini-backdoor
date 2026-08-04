<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Support\SystemActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $otherSessionsTerminated = $this->terminateOtherSessions($request->user(), $request->session()->getId());

        $this->systemActivityLogger->log(
            category: 'login',
            event: 'login_success',
            description: 'Login admin berhasil.',
            subject: 'admin-auth',
            user: $request->user(),
            request: $request,
        );

        if ($otherSessionsTerminated > 0) {
            $this->systemActivityLogger->log(
                category: 'login',
                event: 'login_other_sessions_terminated',
                description: 'Sesi login lain untuk akun ini diakhiri otomatis karena login baru.',
                subject: 'admin-auth',
                user: $request->user(),
                request: $request,
                context: [
                    'terminated_sessions' => $otherSessionsTerminated,
                ],
            );
        }

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

    /**
     * Only one active session is allowed per account. Deleting the other
     * session rows invalidates them immediately for the database session
     * driver: their next request finds no session data and is treated as
     * a guest, which routes them back through the login screen.
     */
    private function terminateOtherSessions(User $user, string $currentSessionId): int
    {
        if (config('session.driver') !== 'database') {
            return 0;
        }

        return DB::table(config('session.table', 'sessions'))
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->delete();
    }
}
