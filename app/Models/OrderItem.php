<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Tambahkan baris ini 👇
    protected $fillable = [
        'order_id', 
        'product_id', 
        'umkm_id', 
        'quantity', 
        'price_at_time', 
        'delivery_status'
    ];

    // (Kalau ada fungsi relasi di bawahnya, biarkan saja)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}