<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_per_kg',
        'estimated_hours',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_per_kg' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}