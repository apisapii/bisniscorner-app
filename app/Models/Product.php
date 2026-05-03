<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'umkm_id', 
        'name', 
        'description', // Pastikan ini ada
        'price', 
        'stock',       // (kalau kamu pakai kolom stock)
        'image'        // Pastikan ini ada
    ];

    // Produk ini milik 1 UMKM
    public function umkm()
    {
        return $this->belongsTo(Umkm::class);
    }
}