<?php

use App\Models\Chat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('support.chat.{chatId}', function ($user, $chatId) {
    if (auth()->guard('admin')->user()) {
        return true;
    } else {
        $auth_user = auth()->guard('web')->user();
        $chat_ids = Chat::query()
            ->where('name', 'support')
            ->whereHas('users', function (Builder $query) use ($auth_user) {
                $query->where('user_id', $auth_user->id);
            })
            ->get()
            ->pluck('id')
            ->toArray();
    }

    if (empty($chat_ids)) {
        return false;
    }

    return in_array((int) $chatId, $chat_ids);
}, ['guards' => ['web', 'admin']]);
