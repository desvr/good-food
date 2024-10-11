<?php

namespace App\Contracts\Shop\Senders;

interface SenderContract
{
    /**
     * Send message
     *
     * @param array  $to      Recipients phone number
     * @param string $message Message text
     * @param array  $options Additional options
     *
     * @return array
     */
    public function send(array $to, string $message, array $options = []): array;
}
