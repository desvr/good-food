<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class ProductVariation extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::created(function($model) {
            Cache::tags('products')->flush();
        });

        static::updated(function() {
            Cache::tags('products')->flush();
        });

        static::deleted(function() {
            Cache::tags('products')->flush();
        });
    }

    protected $table = 'product_variations';
    protected $fillable = [
        'product_id',
        'feature_id',
        'feature_value_id'
    ];

    /**
     * Get products by product id
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id', 'product_id');
    }

    /**
     * Get features by feature id
     *
     * @return HasMany
     */
    public function features(): HasMany
    {
        return $this->hasMany(ProductFeature::class, 'id', 'feature_id');
    }

    /**
     * Get feature values by feature id
     *
     * @return HasMany
     */
    public function feature_values(): HasMany
    {
        return $this->hasMany(ProductFeatureValue::class, 'id', 'feature_value_id');
    }
}
