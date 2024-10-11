<?php

namespace App\Enum;

use App\Exceptions\ShippingException;

enum ShippingType: string
{
    case DELIVERY = 'delivery';
    case PICKUP   = 'pickup';

    /**
     * Get shipping type description
     *
     * @var string $type Shipping type
     *
     * @return string
     *
     * @throws ShippingException
     */
    public static function getShippingTypeDescription(string $type): string
    {
        return match ($type) {
            self::DELIVERY->value => 'Доставка',
            self::PICKUP->value   => 'Самовывоз',

            default => throw new ShippingException('Unexpected match value'),
        };
    }

    /**
     * Get shipping type list description
     *
     * @return array
     */
    public static function getShippingTypeList(): array
    {
        $cases = [];
        foreach (self::cases() as $case) {
            $cases[] = $case->value;
        }

        return $cases;
    }

    /**
     * Get shipping type list description
     *
     * @return array
     *
     * @throws ShippingException
     */
    public static function getShippingTypeListDescription(): array
    {
        $cases = [];
        foreach (self::cases() as $case) {
            $cases[$case->value] = self::getShippingTypeDescription($case->value);
        }

        return $cases;
    }

    /**
     * Shipping is delivery
     *
     * @var string $type Shipping type
     *
     * @return bool
     */
    public static function isDeliveryShipping(string $type): bool
    {
        return $type === self::DELIVERY->value;
    }

    /**
     * Shipping is pickup
     *
     * @var string $type Shipping type
     *
     * @return bool
     */
    public static function isPickupShipping(string $type): bool
    {
        return $type === self::PICKUP->value;
    }
}
