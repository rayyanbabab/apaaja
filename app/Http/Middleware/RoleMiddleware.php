<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UsersRole; 

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user()?->fresh();

        if (! $user) {
            abort(403, 'No user found');
        }

        $userRole = $user->role instanceof UsersRole
            ? $user->role->value
            : $user->role;

        if (! in_array($userRole, $roles)) {
            Log::warning('Unauthorized role', [
                'expected' => implode(',', $roles),
                'actual'   => $userRole,
            ]);

            abort(403, 'Unauthorized role');
        }

        return $next($request);
    }
}
