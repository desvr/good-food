<?php

namespace App\Services\Shop\Support;

use App\Contracts\Shop\Services\Support\SupportMessageServiceContract;
use App\DTO\SupportSendMessageInputDTO;
use App\Events\Chat\StoreSupportMessageEvent;
use App\Http\Resources\Chat\MessageResource;
use App\Models\Administrator;
use App\Models\Chat;
use App\Models\ChatMessages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupportMessageService implements SupportMessageServiceContract
{
    /**
     * Get chat messages by chat ID
     *
     * @param int $chat_id Chat ID
     *
     * @return array
     */
    public function getChatMessagesByChatId(int $chat_id): array
    {
        $is_admin = false;
        if (Auth::user() instanceof Administrator) {
            $is_admin = true;
        }

        ChatMessages::where('chat_id', $chat_id)
            ->where('from_admin', '=', (int) !$is_admin)
            ->whereNull('is_read')
            ->update([
                'is_read' => true,
            ]);

        return MessageResource::collection(
            ChatMessages::query()->where('chat_id', $chat_id)->get()
        )->resolve();
    }

    /**
     * Send message
     *
     * @param SupportSendMessageInputDTO $message_dto Message DTO
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function sendMessage(SupportSendMessageInputDTO $message_dto): array
    {
        $user_id = Auth::id();
        $message = DB::transaction(function() use ($user_id, $message_dto) {
            $chat_id = $message_dto->chat_id;
            $message = $this->createMessage($user_id, $chat_id, $message_dto->message);

            Chat::find($chat_id)->touch();

            return $message;
        });

        /** Handle the event: Message store. */
        broadcast(new StoreSupportMessageEvent($message))->toOthers();

        return MessageResource::make($message)->resolve();
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
        $is_admin = false;
        if (Auth::user() instanceof Administrator) {
            $is_admin = true;
        }

        ChatMessages::where('id', $message_id)
            ->where('from_admin', '=', (int) !$is_admin)
            ->whereNull('is_read')
            ->update([
                'is_read' => true,
            ]);
    }

    /**
     * Create message
     *
     * @param int    $user_id         User ID
     * @param int    $chat_id         Chat ID
     * @param string $message_content Message content
     *
     * @return ChatMessages
     */
    private function createMessage(int $user_id, int $chat_id, string $message_content): ChatMessages
    {
        $from_admin = false;
        if (Auth::user() instanceof Administrator) {
            $from_admin = true;
        }

        return ChatMessages::create([
            'chat_id'    => $chat_id,
            'user_id'    => $user_id,
            'content'    => trim(htmlspecialchars(addslashes($message_content))),
            'from_admin' => (int) $from_admin,
        ]);
    }
}
