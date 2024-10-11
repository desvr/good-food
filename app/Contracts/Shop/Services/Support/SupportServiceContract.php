<?php

namespace App\Contracts\Shop\Services\Support;

use App\Models\Chat;
use App\Models\User;

interface SupportServiceContract
{
    /**
     * Connect user to chat
     *
     * @param User $user User
     * @param Chat $chat Chat
     *
     * @return void
     */
    public function connectUserToChat(User $user, Chat $chat): void;

    /**
     * Get support logotype
     *
     * @return string
     */
    public function getSupportLogotype(): string;
}
