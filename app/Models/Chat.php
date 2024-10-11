<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';
    protected $fillable = [
        'name',
        'created_by',
        'created_at',
        'updated_at',
    ];

    /**
     * Get users by chat
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_users')->withTimestamps();
    }

    /**
     * Get created chat user
     *
     * @return HasOne
     */
    public function created_by_user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    /**
     * Get messages from chat
     *
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessages::class, 'chat_id', 'id');
    }

    /**
     * Get last message from chat
     *
     * @return HasOne
     */
    public function last_message(): HasOne
    {
        return $this->hasOne(ChatMessages::class, 'chat_id', 'id')
            ->latestOfMany();
    }
}
