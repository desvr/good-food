<?php

namespace App\Models;

use App\Enum\ProductType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasEagerLimit;

    public static function boot()
    {
        parent::boot();

        static::created(function() {
            Cache::tags(['products', 'categories'])->flush();
        });

        static::updated(function() {
            Cache::tags(['products', 'categories'])->flush();
        });

        static::deleted(function() {
            Cache::tags(['products', 'categories'])->flush();
        });
    }

    protected $table = 'products';
    protected $fillable = [
        'parent_id',
        'type',
        'code',
        'name',
        'slug',
        'description',
        'image',
        'weight',
        'price',
        'calories',
        'label',
        'stoplist'
    ];
    protected $dates = [
        'deleted_at'
    ];

    /**
     * Route key name for products routing
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get categories by product
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get parent product by parent_id
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'parent_id', 'id');
    }

    /**
     * Get children product by parent_id
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Product::class, 'parent_id', 'id');
    }

    /**
     * Get product variations by product id
     *
     * @return HasMany
     */
    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class, 'product_id', 'id');
    }

    /**
     * Get options by product
     *
     * @return BelongsToMany
     */
    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class, 'product_options');
    }

    /**
     * Local scope a query to only include active products.
     *
     * @param Builder $query
     *
     * @return void
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('active', 1);
    }

    /**
     * Local scope a query to only include products with product type 'P'.
     *
     * @param Builder $query
     *
     * @return void
     */
    public function scopeIsProduct(Builder $query): void
    {
        $query->where('type', ProductType::PRODUCT->value);
    }

    /**
     * Local scope a query to only include products (variations) with product type 'V'.
     *
     * @param Builder $query
     *
     * @return void
     */
    public function scopeIsVariation(Builder $query): void
    {
        $query->where('type', ProductType::VARIATION->value);
    }

    /**
     * Mutator save product name in upper case
     *
     * @return Attribute
     */
    public function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value),
        );
    }
}
