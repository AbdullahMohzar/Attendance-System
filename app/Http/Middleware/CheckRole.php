<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Check if user is logged in
        if (!auth()->check()) {
            return redirect('login');
        }

        // 2. Check if their role is in the allowed list
        // This allows us to use: middleware('role:admin,teacher')
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        // 3. Check if HR has approved them
        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account is pending HR approval.');
        }

        return $next($request);
    }
}
