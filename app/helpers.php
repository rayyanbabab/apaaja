<?php
declare(strict_types=1);

if (! function_exists('panel_route')) {
    /**
     * Generate a URL for a named route using the correct panel prefix
     * (admin or staff) based on the authenticated user's role.
     *
     * Usage in controllers:
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

if (! function_exists('panel_redirect')) {
    /**
     * Create a redirect response to a panel route (role-aware).
     * Replaces redirect()->route('admin.xxx') in controllers.
     *
     * Usage:
     *   return panel_redirect('inventory.index');
     *   return panel_redirect('inventory.show', ['id' => $id]);
     *   return panel_redirect('inventory.index')->with('success', 'Done!');
     */
    function panel_redirect(string $name, array $params = []): \Illuminate\Http\RedirectResponse
    {
        return redirect()->to(panel_route($name, $params));
    }
}