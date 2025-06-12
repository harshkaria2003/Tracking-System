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
     * @param  mixed ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // If user has no related role, deny access
        if (!$user->role || !isset($user->role->name)) {
            abort(403, 'Unauthorized role (no role assigned).');
        }

        $userRoleName = strtolower($user->role->name);
        $allowedRoles = array_map('strtolower', $roles);

        if (!in_array($userRoleName, $allowedRoles)) {
            abort(403, 'Unauthorized role.');
        }

        return $next($request);
    }
}
