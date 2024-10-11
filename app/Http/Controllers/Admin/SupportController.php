<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Shop\Services\Support\SupportServiceContract;
use App\DTO\SupportSendMessageInputDTO;
use App\Enum\ChatName;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class SupportController extends Controller
{
    public function index()
    {
        $chats = Chat::query()
            ->where('name', ChatName::SUPPORT->value)
            ->with([
                'last_message',
                'created_by_user',
            ])
            ->withCount(['messages' => function (Builder $query) {
                $query->whereNull('is_read')
                    ->where('from_admin', '=', 0);
            }])
            ->orderByDesc('updated_at')
            ->get();

        return view('admin.pages.chat', compact(['chats']));
    }

    /**
     * {GET} {AJAX} Load messages block.
     *
     * @param int $chat_id Chat ID
     */
    public function loadMessages(
        int $chat_id,
        SupportServiceContract $supportService
    ) {
        $chat = Chat::query()
            ->where('id', $chat_id)
            ->with(['created_by_user'])
            ->first();
        $messages = $supportService->getChatMessagesByChatId($chat_id);
        $support_avatar = $supportService->getSupportLogotype();

        return Blade::render(
            'admin.components.chat.chat_block',
            [
                'user' => Auth::user(),
                'chat' => $chat,
                'messages' => $messages,
                'support_avatar' => $support_avatar,
            ]
        );
    }

    /**
     * {POST} {AJAX} Send message.
     *
     * @param SupportSendMessageInputDTO $supportSendMessageDto Message DTO
     */
    public function sendMessage(
        SupportSendMessageInputDTO $supportSendMessageDto,
        SupportServiceContract $supportService,
    ) {
        return $supportService->sendMessage($supportSendMessageDto);
    }

    /**
     * {POST} {AJAX} Mark message as read.
     *
     * @param int $message_id Message ID
     */
    public function markMessageAsRead(
        int $message_id,
        SupportServiceContract $supportService,
    ) {
        $supportService->markMessageAsRead($message_id);
    }
}
