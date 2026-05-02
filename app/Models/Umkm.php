<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    protected $fillable = ['name', 'description', 'bank_name', 'account_number'];

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