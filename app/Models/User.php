<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            session(['user_data' => $model->toArray()]);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'birthday',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];

    /**
     * Get orders by user
     *
     * @return hasMany
     */
    public function orders(): hasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    /**
     * Get chats by user
     *
     * @return BelongsToMany
     */
    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class, 'chat_users')->withTimestamps();
    }

    /**
     * Accessor returned formatted phone for view
     *
     * @return Attribute
     */
    protected function phoneFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => preg_replace(
                '/^(\d)(\d{3})(\d{3})(\d{4})$/',
                '+\1 (\2) \3-\4',
                (string) $this->phone
            ),
        );
    }
}
