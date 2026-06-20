<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Hanya superadmin yang bisa mengakses.',
            ], 403);
        }

        return $next($request);
    }
}
