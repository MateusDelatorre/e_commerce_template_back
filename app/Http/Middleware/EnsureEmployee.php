<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmployee
{
    /**
     * Handle an incoming request.
     * Allows: employee, admin, owner, developer.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isEmployee()) {
            return response()->json([
                'error' => 'Forbidden. Employee access required.'
            ], 403);
        }

        return $next($request);
    }
}
