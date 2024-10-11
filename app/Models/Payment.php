<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $fillable = [
        'name',
        'processor',
        'method',
        'settings',
        'webhook_key',
        'template',
    ];
    protected $casts = [
        'settings' => 'array',
        'created_at' => 'datetime:d.m.Y H:m:i',
        'updated_at' => 'datetime:d.m.Y H:m:i',
    ];

    /**
     * Get order transactions by payment
     *
     * @return hasMany
     */
    public function order_transactions(): hasMany
    {
        return $this->hasMany(OrderTransactions::class, 'payment_id', 'id');
    }
}
