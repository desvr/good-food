<?php

namespace App\Models;

use App\Enum\OrderStatus;
use App\Events\CreatedOrderEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Order extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::created(function($model) {
            Cache::forget(\App\Enum\Cache\Repository\Order::CACHE_KEY_ORDER_DATA_BY_USER . $model->user_id);

            /** Handle the event: Order created. */
            CreatedOrderEvent::dispatch($model);
        });

        static::updated(function() {
            Cache::tags('orders')->flush();
        });

        static::deleted(function() {
            Cache::tags('orders')->flush();
        });
    }

    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'verified',
        'status',
        'shipping_type',
        'delivery_area',
        'delivery_address',
        'is_preorder',
        'preorder_datetime',
        'note',
        'number_persons',
        'payment_method',
        'promotion_id',
        'original_price',
        'result_price',
        'bonus_points',
        'condition_id',
        'condition_data',
        'request_send',
        'receipt_code',
    ];
    protected $casts = [
        'status' => OrderStatus::class,
        'condition_data' => 'array',
        'created_at' => 'datetime:d.m.Y H:m:i',
        'updated_at' => 'datetime:d.m.Y H:m:i',
    ];

    /**
     * Get order products by order
     *
     * @return hasMany
     */
    public function order_products(): hasMany
    {
        return $this->hasMany(OrderProducts::class, 'order_id', 'id');
    }

    /**
     * Get order transactions by order
     *
     * @return hasMany
     */
    public function order_transactions(): hasMany
    {
        return $this->hasMany(OrderTransactions::class, 'order_id', 'id');
    }

    /**
     * Get user by order
     *
     * @return belongsTo
     */
    public function user(): belongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
