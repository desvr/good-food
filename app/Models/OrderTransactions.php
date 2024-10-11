<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderTransactions extends Model
{
    use HasFactory;

    protected $table = 'order_transactions';
    public $timestamps = false;
    protected $fillable = [
        'order_id',
        'payment_id',
        'transaction_key',
        'amount',
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

    /**
     * Get payment by order payments
     *
     * @return belongsTo
     */
    public function payment(): belongsTo
    {
        return $this->belongsTo(Payment::class, 'id', 'payment_id');
    }
}
