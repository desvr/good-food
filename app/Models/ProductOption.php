<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ProductOption extends Model
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

    protected $table = 'product_options';
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'option_id',
    ];
}
