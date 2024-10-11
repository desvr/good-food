<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class ProductFeature extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::updated(function() {
            Cache::tags('products')->flush();
        });

        static::deleted(function() {
            Cache::tags('products')->flush();
        });
    }

    protected $table = 'product_features';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'value'
    ];

    /**
     * Get product variations by feature ID
     *
     * @return HasMany
     */
    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class, 'feature_id', 'id');
    }

    /**
     * Get feature name by feature ID
     *
     * @param string $feature_id Feature ID
     *
     * @return string
     */
    public static function getFeatureNameByID(string $feature_id): string
    {
        return static::where('id', $feature_id)->value('name');
    }
}
