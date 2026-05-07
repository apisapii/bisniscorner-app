<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const SERVICE_FEE_FLAT = 500;

    public const PAYMENT_PENDING = 'pending';

    public const PAYMENT_PAID = 'paid';

    public const PAYMENT_FAILED = 'failed';

    public const PAYMENT_EXPIRED = 'expired';

    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_email',
        'total_amount',
        'service_fee_amount',
        'status',
        'payment_status',
        'payment_paid_at',
        'xendit_reference',
    ];

    protected function casts(): array
    {
        return [
            'payment_paid_at' => 'datetime',
        ];
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function paymentLabel(): string
    {
        return match ($this->payment_status) {
            self::PAYMENT_PAID => 'Lunas',
            self::PAYMENT_PENDING => 'Menunggu pembayaran',
            self::PAYMENT_FAILED => 'Gagal bayar',
            self::PAYMENT_EXPIRED => 'Kadaluarsa',
            default => $this->payment_status ?? '-',
        };
    }

    public function subtotalAmount(): int
    {
        return max(0, (int) $this->total_amount - (int) $this->service_fee_amount);
    }
}