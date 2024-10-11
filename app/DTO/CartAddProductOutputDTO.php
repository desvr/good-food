<?php

namespace App\DTO;

use Spatie\LaravelData\Data;

final class CartAddProductOutputDTO extends Data
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $price,
        public readonly ?int   $quantity = 1,
        public readonly ?array $attributes = [],
    ) {}
}
