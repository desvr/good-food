<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessages extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';
    protected $fillable = [
        'chat_id',
        'user_id',
        'from_admin',
        'content',
        'is_read',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
