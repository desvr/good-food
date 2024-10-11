<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class ProductFeatureValue extends Model
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

    protected $table = 'product_feature_values';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'value'
    ];

    /**
     * Get product variations by feature value id
     *
     * @return HasMany
     */
    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class, 'feature_value_id', 'id');
    }

    /**
     * Get feature value name by feature value ID
     *
     * @param string $feature_value_id Feature value ID
     *
     * @return string
     */
    public static function getFeatureValueNameByID(string $feature_value_id): string
    {
        return static::where('id', $feature_value_id)->value('name');
    }
}
