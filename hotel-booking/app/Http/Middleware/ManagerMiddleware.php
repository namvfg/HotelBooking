<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return response()->json([
                "message" => "Unauthenticated"
            ], 401);
        }

        $role = $request->user()->role;
        if ($role !== Role::MANAGER && $role !== Role::ADMIN) {
            return response()->json([
                "message" => "Forbiden"
            ], 403);
        }

        return $next($request);
    }
}
