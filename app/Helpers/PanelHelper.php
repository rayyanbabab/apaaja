<?php

if (! function_exists('panel_route')) {
    /**
     * Generate a URL for a named route using the correct panel prefix
     * based on the authenticated user's role.
     *
     * Usage (in controllers):
     *   return redirect()->to(panel_route('inventory.index'));
     *   return redirect()->to(panel_route('inventory.show', ['id' => $id]));
     */
    function panel_route(string $name, array $params = []): string
    {
        $user = auth()->user();
        $role = $user?->role?->value ?? 'admin';
        $prefix = $role === 'operator' ? 'staff' : 'admin';

        return route($prefix . '.' . $name, $params);
    }
}
