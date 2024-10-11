<?php

namespace App\DTO;

use Spatie\LaravelData\Data;

final class SupportSendMessageInputDTO extends Data
{
    public function __construct(
        public readonly string $chat_id,
        public readonly string $message,
    ) {}
}
