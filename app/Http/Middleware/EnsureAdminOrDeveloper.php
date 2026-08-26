<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminOrDeveloper
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || (!$user->isAdmin() && !$user->isDeveloper())) {
            return response()->json([
                'error' => 'Forbidden. Admin or Developer access required.'
            ], 403);
        }

        return $next($request);
    }
}
