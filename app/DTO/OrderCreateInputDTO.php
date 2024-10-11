<?php

namespace App\DTO;

use App\Enum\OrderStatus;
use Spatie\LaravelData\Data;

final class OrderCreateInputDTO extends Data
{
    public function __construct(
        public readonly string       $name,
        public readonly string       $phone,
        public readonly string       $shipping_type,
        public readonly string       $payment_method,
        public readonly int          $number_persons,
        public readonly ?bool        $verified = false,
        public readonly ?OrderStatus $status = OrderStatus::OPEN,
        public readonly ?string      $shipping_type_delivery = '',
        public readonly ?string      $shipping_type_pickup = '',
        public readonly ?string      $shipping_area = '',
        /** @var string[] $delivery_address */
        public readonly ?array       $delivery_address = [],
        public readonly ?bool        $is_preorder = false,
        public readonly ?string      $preorder_date = '',
        public readonly ?string      $preorder_time = '',
        public readonly ?bool        $no_request_send = false,
        public readonly ?int         $payment_method_cash_change_from = 0,
        public readonly ?string      $note = '',
    ) {}
}
