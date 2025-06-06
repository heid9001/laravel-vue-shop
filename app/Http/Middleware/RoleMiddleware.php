<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! in_array(auth()->user()?->role->value, $roles)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        return $next($request);
    }
}
