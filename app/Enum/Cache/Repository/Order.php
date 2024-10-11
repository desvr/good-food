<?php

namespace App\Enum\Cache\Repository;

class Order
{
    const CACHE_KEY_ORDER_DATA = 'order:';
    const CACHE_TTL_ORDER_DATA = 60 * 10;

    const CACHE_KEY_ORDER_DATA_BY_USER = 'orders:user:';
    const CACHE_TTL_ORDER_DATA_BY_USER = 60 * 15;
}
