<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminPanelAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || $user->canAccessAdminRoute($request->route()?->getName())) {
            return $next($request);
        }

        if ($request->expectsJson() || ! $request->isMethod('GET')) {
            abort(403);
        }

        return redirect()
            ->route($user->adminLandingRouteName())
            ->with('status', 'Akun Admin Host hanya dapat mengakses menu Berita, Signal, dan Ebook.');
    }
}
