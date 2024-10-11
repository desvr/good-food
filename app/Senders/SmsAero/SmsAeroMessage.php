<?php

namespace App\Senders\SmsAero;

use App\Contracts\Shop\Senders\SenderContract;
use \SmsAero\SmsAeroMessage as SmsAeroMessageApi;

class SmsAeroMessage extends BaseSmsAero implements SenderContract
{
    private SmsAeroMessageApi $smsAeroMessage;

    public function __construct()
    {
        parent::__construct();

        $this->smsAeroMessage = new SmsAeroMessageApi($this->userLogin, $this->apiKey);
    }

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
        $data = [
            'numbers' => $to,
            'text' => $message,
            'sign' => $this->sign,
        ];

        if (!empty($options['dateSend'])) {
            $data['dateSend'] = (int) $options['dateSend'];
        }

        return $this->smsAeroMessage->send($data);
    }
}
