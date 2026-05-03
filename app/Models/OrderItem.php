<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public const DELIVERY_PENDING = 'pending';

    public const DELIVERY_READY = 'ready';

    public const DELIVERY_PICKED_UP = 'picked_up';

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

    public function deliveryLabel(): string
    {
        return match ($this->delivery_status) {
            self::DELIVERY_READY => 'Siap diambil',
            self::DELIVERY_PICKED_UP => 'Sudah diambil',
            self::DELIVERY_PENDING => 'Disiapkan penjual',
            default => $this->delivery_status,
        };
    }
}