<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // cek apakah user login & rolenya sesuai
        if (!$request->user() || $request->user()->role !== $role) {
            return response()->json([
                'message' => 'Forbidden: Access denied'
            ], 403);
        }

        return $next($request);
    }
}