<?php

namespace App\Services\Shop\Support;

use App\Contracts\Shop\Services\Support\SupportChatServiceContract;
use App\Contracts\Shop\Services\Support\SupportMessageServiceContract;
use App\Contracts\Shop\Services\Support\SupportServiceContract;
use App\DTO\SupportSendMessageInputDTO;
use App\Models\Chat;
use App\Models\ChatUsers;
use App\Models\User;

class SupportService implements SupportServiceContract, SupportChatServiceContract, SupportMessageServiceContract
{
    public function __construct(
        private readonly SupportChatServiceContract $supportChatService,
        private readonly SupportMessageServiceContract $supportMessageService,
    ) {}

    /**
     * Get chat ID by user
     *
     * @param User $user User
     *
     * @return int
     */
    public function getChatIdByUser(User $user): int
    {
        return $this->supportChatService->getChatIdByUser($user);
    }

    /**
     * Create chat
     *
     * @param User $user User
     *
     * @return Chat
     */
    public function createChat(User $user): Chat
    {
        return $this->supportChatService->createChat($user);
    }

    /**
     * Connect user to chat
     *
     * @param User $user User
     * @param Chat $chat Chat
     *
     * @return void
     */
    public function connectUserToChat(User $user, Chat $chat): void
    {
        if (ChatUsers::where(['user_id' => $user->id, 'chat_id' => $chat->id])->first()) {
            return;
        }

        $chat->users()->attach($user->id);
    }

    /**
     * Get chat messages by chat ID
     *
     * @param int $chat_id Chat ID
     *
     * @return array
     */
    public function getChatMessagesByChatId(int $chat_id): array
    {
        return $this->supportMessageService->getChatMessagesByChatId($chat_id);
    }

    /**
     * Send message
     *
     * @param SupportSendMessageInputDTO $message_dto Message DTO
     *
     * @return array
     */
    public function sendMessage(SupportSendMessageInputDTO $message_dto): array
    {
        return $this->supportMessageService->sendMessage($message_dto);
    }

    /**
     * Mark message as read
     *
     * @param int $message_id Message ID
     *
     * @return void
     */
    public function markMessageAsRead(int $message_id): void
    {
        $this->supportMessageService->markMessageAsRead($message_id);
    }

    /**
     * Get support logotype
     *
     * @return string
     */
    public function getSupportLogotype(): string
    {
        return config('app.logo');
    }
}
