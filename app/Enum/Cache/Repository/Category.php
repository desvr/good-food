<?php

namespace App\Enum\Cache\Repository;

class Category
{
    const CACHE_KEY_CATEGORY_DATA = 'category:';
    const CACHE_TTL_CATEGORY_DATA = 60 * 30;

    const CACHE_KEY_CATEGORY_LIST = 'categories:list';
    const CACHE_TTL_CATEGORY_LIST = 60 * 30;

    const CACHE_KEY_SIMPLE_CATEGORY_LIST = 'simple:categories:list';
    const CACHE_TTL_SIMPLE_CATEGORY_LIST = 60 * 30;
}
