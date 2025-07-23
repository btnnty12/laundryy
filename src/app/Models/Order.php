<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'service_id',
        'weight',
        'total_price',
        'pickup_date',
        'delivery_date',
        'notes',
        'status',
        'payment_status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'total_price' => 'decimal:2',
            'pickup_date' => 'date',
            'delivery_date' => 'date',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber()
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', now())->latest()->first();
        $sequence = $lastOrder ? (int) substr($lastOrder->order_number, -3) + 1 : 1;
        
        return 'LND-' . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function histories()
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    // Accessors
    public function getOrderNumberWithCustomerAttribute()
    {
        return $this->order_number . ' - ' . ($this->customer ? $this->customer->name : 'Unknown Customer');
    }
}