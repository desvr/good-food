<?php

namespace App\Contracts\Shop\Helpers\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;

interface FilterContract
{
    /**
     * Apply filter
     *
     * @param Builder $builder Builder
     */
    public function applyFilter(Builder $builder): void;
}
