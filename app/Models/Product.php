<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['umkm_id', 'name', 'price', 'stock'];

    // Produk ini milik 1 UMKM
    public function umkm()
    {
        return $this->belongsTo(Umkm::class);
    }
}