<?php

namespace App\Enum;

use App\Exceptions\ProductException;

enum ProductBadge: string
{
    case NEW  = 'new';
    case SALE = 'sale';

    /**
     * Get badge color by label
     *
     * @var string $label Badge label
     *
     * @return string
     *
     * @throws ProductException
     */
    public static function getBadgeColorByLabel(string $label): string
    {
        return match($label) {
            self::NEW->value => 'green',
            self::SALE->value => 'red',

            default => throw new ProductException('Badge не найден.'),
        };
    }

    /**
     * Get badge label list
     *
     * @return array<int, string>
     */
    public static function getBadgeLabelList(): array
    {
        $cases = [];
        foreach (self::cases() as $case) {
            $cases[$case->name] = $case->value;
        }

        return $cases;
    }
}
