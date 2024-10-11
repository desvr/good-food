<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class OrderData extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::created(function() {
            Cache::tags('orders')->flush();
        });

        static::updated(function() {
            Cache::tags('orders')->flush();
        });

        static::deleted(function() {
            Cache::tags('orders')->flush();
        });
    }

    protected $table = 'order_data';
    protected $fillable = [
        'order_id',
        'type',
        'data',
    ];
    protected $casts = [
        'data' => AsArrayObject::class,
        'created_at' => 'datetime:d.m.Y H:m:i',
        'updated_at' => 'datetime:d.m.Y H:m:i',
    ];

    /**
     * Get order by order payments
     *
     * @return belongsTo
     */
    public function order(): belongsTo
    {
        return $this->belongsTo(Order::class, 'id', 'order_id');
    }
}
