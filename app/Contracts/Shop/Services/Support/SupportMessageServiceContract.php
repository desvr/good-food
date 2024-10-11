<?php

namespace App\Contracts\Shop\Services\Support;

use App\DTO\SupportSendMessageInputDTO;

interface SupportMessageServiceContract
{
    /**
     * Get chat messages by chat ID
     *
     * @param int $chat_id Chat ID
     *
     * @return array
     */
    public function getChatMessagesByChatId(int $chat_id): array;

    /**
     * Send message
     *
     * @param SupportSendMessageInputDTO $message_dto Message DTO
     *
     * @return array
     */
    public function sendMessage(SupportSendMessageInputDTO $message_dto): array;

    /**
     * Mark message as read
     *
     * @param int $message_id Message ID
     *
     * @return void
     */
    public function markMessageAsRead(int $message_id): void;
}
