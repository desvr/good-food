<?php

namespace App\Senders\SmsAero;

use App\Senders\BaseSender;

abstract class BaseSmsAero extends BaseSender
{
    protected string $userLogin;
    protected string $apiKey;
    protected string $sign;

    public function __construct()
    {
        $this->userLogin = config('senders.smsaero.user_login');
        $this->apiKey = config('senders.smsaero.api_key');
        $this->sign = 'SMS Aero';
    }
}
