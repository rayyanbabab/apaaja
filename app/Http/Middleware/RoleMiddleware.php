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
            : (string) $user->role;

        $allowedRoles = [];
        foreach ($roles as $r) {
            foreach (explode(',', (string) $r) as $subR) {
                $trimmed = trim($subR);
                if ($trimmed !== '') {
                    $allowedRoles[] = $trimmed;
                }
            }
        }

        if (! in_array($userRole, $allowedRoles, true)) {
            Log::warning('Unauthorized role', [
                'expected' => implode(',', $allowedRoles),
                'actual'   => $userRole,
            ]);

            abort(403, 'Unauthorized role');
        }

        return $next($request);
    }
}
