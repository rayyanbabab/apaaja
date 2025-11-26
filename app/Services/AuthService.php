<?php

namespace App\Services;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(array $credentials): ?string
    {
        $user = User::where('email', $credentials['email'])->first();
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return null;
        }
        if (! $user->is_active) {
            return 'inactive';
        }

        Auth::login($user);

        LoginLog::create([
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'logged_in_at' => now(),
        ]);

        $role = $user->role instanceof \App\Enums\UsersRole
            ? $user->role->value
            : $user->role;

        return match ($role) {
            'admin' => route('admin.dashboard'),
            'user'  => route('user.dashboard'),
            default => '/',
        };
    }
}
