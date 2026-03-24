<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('team')->check()) {
            return redirect()->route('team.login')->withErrors(['email' => 'Bejelentkezés szükséges.']);
        }

        return $next($request);
    }
}
