<?php

namespace App\Contracts\Shop\Services\Support;

use App\Models\Chat;
use App\Models\User;

interface SupportChatServiceContract
{
    /**
     * Get chat ID by user
     *
     * @param User $user User
     *
     * @return int
     */
    public function getChatIdByUser(User $user): int;

    /**
     * Create chat
     *
     * @param User $user User
     *
     * @return Chat
     */
    public function createChat(User $user): Chat;
}
