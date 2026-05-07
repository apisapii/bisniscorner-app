<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Umkm extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'contact_name', 'contact_phone', 'bank_name', 'account_number'];

    protected static function booted(): void
    {
        static::creating(function (Umkm $umkm) {
            if (empty($umkm->slug)) {
                $umkm->slug = static::uniqueSlugFromName($umkm->name);
            }
        });
    }

    public static function uniqueSlugFromName(string $name): string
    {
        $base = Str::slug($name) ?: 'toko-'.Str::lower(Str::random(6));
        $slug = $base;
        $n = 0;
        while (static::where('slug', $slug)->exists()) {
            $n++;
            $slug = $base.'-'.$n;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // 1 UMKM bisa punya banyak User (Admin)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // 1 UMKM bisa punya banyak Produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}