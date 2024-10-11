<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Category extends Model
{
    use HasFactory, HasEagerLimit;

    public static function boot()
    {
        parent::boot();

        static::created(function($model) {
            Cache::tags('categories')->flush();
        });

        static::updated(function() {
            Cache::tags('categories')->flush();
        });

        static::deleted(function() {
            Cache::tags('categories')->flush();
        });
    }

    protected $table = 'categories';
    protected $fillable = ['parent_id', 'name', 'slug', 'description'];

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
     * Get products by category
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Get filter list for category.
     *
     * @return array Filter list
     */
    public function getCategoryFilterList(): array
    {
        $product_filters = [];

        $options = Option::query()->where('category_id', $this->id)->get();
        foreach ($options as $option) {
            $product_filters[$option->type][] = $option;
        }

        return $product_filters;
    }
}
