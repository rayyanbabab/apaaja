<?php

if (! function_exists('panel_route')) {
    /**
     * Generate a URL for a named route using the correct panel prefix
     * based on the authenticated user's role.
     */
    function panel_route(string $name, array $params = []): string
    {
        $user = auth()->user();
        $role = $user?->role instanceof \App\Enums\UsersRole ? $user->role->value : (string) ($user?->role ?? 'admin');
        $prefix = $role === 'operator' ? 'staff' : 'admin';

        if (str_starts_with($name, 'admin.') || str_starts_with($name, 'staff.')) {
            if (\Illuminate\Support\Facades\Route::has($name)) {
                return route($name, $params);
            }
            if ($prefix === 'staff' && str_starts_with($name, 'admin.')) {
                $staffName = 'staff.' . substr($name, 6);
                if (\Illuminate\Support\Facades\Route::has($staffName)) {
                    return route($staffName, $params);
                }
            }
        }

        $targetRoute = $prefix . '.' . $name;
        if (\Illuminate\Support\Facades\Route::has($targetRoute)) {
            return route($targetRoute, $params);
        }

        if (\Illuminate\Support\Facades\Route::has('admin.' . $name)) {
            return route('admin.' . $name, $params);
        }

        if (\Illuminate\Support\Facades\Route::has($name)) {
            return route($name, $params);
        }

        return route($targetRoute, $params);
    }
}

if (! function_exists('panel_redirect')) {
    /**
     * Create a redirect response to a panel route (role-aware).
     */
    function panel_redirect(string $name, array $params = []): \Illuminate\Http\RedirectResponse
    {
        return redirect()->to(panel_route($name, $params));
    }
}
