<?php

namespace App\Enum;

use App\Exceptions\OrderException;

enum OrderStatus: string
{
    /** The order was placed and created in the store database, but it hasn’t been processed yet */
    case OPEN = 'O';
    /** The payment was received */
    case PAID = 'P';
    /** The order is being delivered */
    case SHIPMENT = 'S';
    /** All work on the order was completed */
    case COMPLETE = 'C';
    /** The payment transaction failed */
    case FAILED = 'F';
    /** The order was canceled by the store administrator */
    case DECLINED = 'D';

    /** List of order statuses with an error */
    private const ERROR_STATUSES = [self::FAILED, self::DECLINED];

    /**
     * Get order status description
     *
     * @var string $status Order status
     *
     * @return string
     *
     * @throws OrderException
     */
    public static function getStatusDescription(string $status): string
    {
        return match ($status) {
            self::OPEN->value     => 'Создан',
            self::PAID->value     => 'Принят',
            self::SHIPMENT->value => 'Доставка',
            self::COMPLETE->value => 'Выполнен',
            self::FAILED->value   => 'Неудача',
            self::DECLINED->value => 'Аннулирован',

            default => throw new OrderException('Unexpected match value'),
        };
    }

    /**
     * Get order status list description
     *
     * @return array
     *
     * @throws OrderException
     */
    public static function getStatusListDescription(): array
    {
        $cases = [];
        foreach (self::cases() as $case) {
            $cases[$case->value] = self::getStatusDescription($case->value);
        }

        return $cases;
    }

    /**
     * Check order status in error list statuses
     *
     * @var string $status Order status
     *
     * @return bool
     */
    public static function checkErrorStatus(string $status): bool
    {
        if (in_array($status, self::ERROR_STATUSES)) {
            return true;
        }

        return false;
    }
}
