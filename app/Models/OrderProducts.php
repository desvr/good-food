<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProducts extends Model
{
    use HasFactory;

    protected $table = 'order_products';
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'original_item_price',
        'result_item_price',
        'original_subtotal_price',
        'result_subtotal_price',
        'data',
    ];
    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime:d.m.Y H:m:i',
        'updated_at' => 'datetime:d.m.Y H:m:i',
    ];

    /**
     * Get order by order products
     *
     * @return belongsTo
     */
    public function order(): belongsTo
    {
        return $this->belongsTo(Order::class, 'id', 'order_id');
    }
}
