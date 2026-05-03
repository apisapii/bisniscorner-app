<?php

namespace App\Support;

use App\Models\User;

class AuthRedirect
{
    /**
     * URL default setelah login/register sesuai role.
     */
    public static function homeUrl(User $user): string
    {
        return match ($user->role) {
            'super_admin' => route('admin.tenants.index', absolute: false),
            'admin_umkm' => route('dashboard', absolute: false),
            'customer' => route('catalog', absolute: false),
            default => route('catalog', absolute: false),
        };
    }
}
