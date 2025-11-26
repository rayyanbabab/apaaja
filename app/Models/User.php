<?php

namespace App\Models;

use App\Enums\UsersRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'bio',
        'email',
        'profil',
        'password',
        'role',
        'is_active',
    ];

    public function hasRole(UsersRole|string $role): bool
    {
        if ($this->role instanceof UsersRole) {
            return $this->role->value === (is_string($role) ? $role : $role->value);
        }

        return $this->role === (is_string($role) ? $role : $role->value);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UsersRole::class,
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function borrowingRequests()
    {
        return $this->hasMany(BorrowingRequest::class);
    }
}