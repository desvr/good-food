<?php

namespace App\DTO;

use Spatie\LaravelData\Data;

final class CartAddProductInputDTO extends Data
{
    public function __construct(
        public readonly ?int    $product_id = 0,
        public readonly ?string $variation_data = '',
        public readonly ?int    $quantity = 1,
    ) {}
}
