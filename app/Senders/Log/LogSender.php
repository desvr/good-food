<?php

namespace App\Senders\Log;

use App\Contracts\Shop\Senders\SenderContract;
use App\Senders\BaseSender;
use Illuminate\Support\Facades\Log;

class LogSender extends BaseSender implements SenderContract
{
    /**
     * Send message
     *
     * @param array  $to      Recipients phone number
     * @param string $message Message text
     * @param array  $options Additional options
     *
     * @return array
     *
     * @throws \Exception
     */
    public function send(array $to, string $message, array $options = []): array
    {
        foreach ($to as $phone_number) {
            Log::info('LogSender: numbers - ' . $phone_number . '; text - ' . $message);
        }

        return [];
    }
}
