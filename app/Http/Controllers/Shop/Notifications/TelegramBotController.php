<?php

namespace App\Http\Controllers\Shop\Notifications;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Response;

class TelegramBotController extends Controller
{
    protected string $url;

    public function __construct()
    {
        $this->url = 'https://api.telegram.org/bot' . config('services.telegram-bot-api.token');
    }

    public function webhookInstall()
    {
        $guzzle = new \GuzzleHttp\Client();
        $url = $this->url . '/setwebhook?url=' . route('telegram.bot.webhookInitHandle');

        $response = $guzzle->post(
            $url,
            [
                'headers' => [
                    'X-Telegram-Bot-Api-Secret-Token' => config('services.telegram-bot-api.token'),
                ],
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Webhook не установлен.');
        }

        return redirect()->route('home');
    }

    /**
     * {POST} Webhook initial handle.
     */
    public function webhookInitHandle(): Response
    {
        $payload = json_decode(@file_get_contents('php://input'));
        if (empty($payload->message)) {
            return response('OK', 200)->header('Content-Type', 'text/plain');
        }

        $message = $payload->message;

        if (!empty($message_text = $message->text)) {
            if (str_contains($message_text, '/start')) {
                $text_strings = explode(' ', $message_text);

                if (!empty($text_strings[1])) {
                    $token = $text_strings[1];
                    $chat_id = $message->chat->id;

                    $user = User::query()->where('telegram_token', '=', $token)->first();
                    if (!empty($user)) {
                        $user->telegram_user_id = $chat_id;
                        $user->save();
                    }
                }
            }
        }

        return response('OK', 200)->header('Content-Type', 'text/plain');
    }
}
