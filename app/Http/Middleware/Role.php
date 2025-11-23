<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Role
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! Auth::check()) {
            abort(403, 'Unauthorized action. Please log in.');
        }

        // Refresh user from database to ensure we have latest data
        $user = Auth::user();
        $user->refresh();

        $userRole = $user->role;

        // Check if user is approved (for non-admin roles)
        if (in_array($userRole, ['librarian', 'student', 'faculty']) && ! $user->is_approved) {
            abort(403, 'Your account must be approved to access this resource.');
        }

        if (! in_array($userRole, $roles)) {
            abort(403, 'Unauthorized action. Required role: '.implode(' or ', $roles).'. Your role: '.$userRole);
        }

        return $next($request);
    }
}
