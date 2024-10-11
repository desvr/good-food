<?php

namespace App\Http\Controllers\Shop;

use App\Contracts\Shop\Services\Support\SupportServiceContract;
use App\DTO\SupportSendMessageInputDTO;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function __construct(
        private readonly SupportServiceContract $supportService,
    ) {}

    public function show()
    {
        $user = Auth::user();
        $chat_id = $this->supportService->getChatIdByUser($user);
        $messages = $this->supportService->getChatMessagesByChatId($chat_id);
        $support_avatar = $this->supportService->getSupportLogotype();

        return view('shop.pages.support.chat', compact([
            'user',
            'messages',
            'chat_id',
            'support_avatar'
        ]));
    }

    /**
     * {POST} Initializing a new chat.
     */
    public function store()
    {
        $user = Auth::user();

        try {
            $this->supportService->createChat($user);
        } catch (\Exception) {
            return back();
        }

        return redirect()->route('support.chat');
    }

    /**
     * {POST} {AJAX} Send message.
     *
     * @param SupportSendMessageInputDTO $supportSendMessageDto Message DTO
     */
    public function sendMessage(SupportSendMessageInputDTO $supportSendMessageDto)
    {
        return $this->supportService->sendMessage($supportSendMessageDto);
    }

    /**
     * {POST} {AJAX} Mark message as read.
     *
     * @param int $message_id Message ID
     */
    public function markMessageAsRead(
        int $message_id,
        SupportServiceContract $supportService
    ) {
        $supportService->markMessageAsRead($message_id);
    }
}
