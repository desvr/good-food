<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatUsers extends Model
{
    use HasFactory;

    protected $table = 'chat_users';
    protected $fillable = [
        'chat_id',
        'user_id',
    ];
}
