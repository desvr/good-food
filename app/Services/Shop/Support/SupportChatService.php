<?php

namespace App\Services\Shop\Support;

use App\Contracts\Shop\Services\Support\SupportChatServiceContract;
use App\Enum\ChatName;
use App\Exceptions\SupportException;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class SupportChatService implements SupportChatServiceContract
{
    /**
     * Get chat ID by user
     *
     * @param User $user User
     *
     * @return int
     */
    public function getChatIdByUser(User $user): int
    {
        $chat_id = Chat::query()
            ->select('id')
            ->where('name', ChatName::SUPPORT->value)
            ->whereHas('users', function (Builder $query) use ($user) {
                $query->where('id', $user->id);
            })->value('id');

        return $chat_id ?? 0;
    }

    /**
     * Create chat
     *
     * @param User $user User
     *
     * @return Chat
     *
     * @throws SupportException
     */
    public function createChat(User $user): Chat
    {
        if ($this->getChatByCreatedUser($user)) {
            throw new SupportException('The current chat already exists.');
        }

        $chat = Chat::create([
            'name'       => ChatName::SUPPORT->value,
            'created_by' => $user->id,
        ]);

        $chat->users()->attach($user->id);

        return $chat;
    }

    /**
     * Get chat by created user
     *
     * @param User $user User
     *
     * @return Chat|null
     */
    private function getChatByCreatedUser(User $user): ?Chat
    {
        return Chat::where('name', ChatName::SUPPORT->value)
            ->where('created_by', $user->id)
            ->first();
    }
}
