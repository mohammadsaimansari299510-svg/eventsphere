<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Please log in to access this feature.');
        }

        $user = Auth::user();

        if ($user->status === 'suspended') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been suspended by the administrator.');
        }

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'You do not have permission to access that section.');
    }
}
