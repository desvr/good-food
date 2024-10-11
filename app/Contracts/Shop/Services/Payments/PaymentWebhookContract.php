<?php

namespace App\Contracts\Shop\Services\Payments;

interface PaymentWebhookContract
{
    /**
     * Webhooks install
     *
     * @return void
     */
    public function webhookInstall(): void;

    /**
     * Webhook handle.
     *
     * @param string $payload    Webhook payload
     * @param string $sig_header Sig header
     *
     * @return array
     */
    public function webhookHandle(string $payload, string $sig_header): array;
}
