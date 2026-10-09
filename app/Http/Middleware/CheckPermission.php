<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Part 3.2: permission:residents.view,households.view lets the request through when the
 * user's role has ANY of the listed permissions. Replaces the old role:Admin,Secretary checks.
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (! $request->user()->hasAnyPermission($permissions)) {
            $message = 'You do not have permission to access that page.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 403);
            }

            return redirect()->route('dashboard')->with('error', $message);
        }

        return $next($request);
    }
}
