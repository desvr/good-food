<?php

namespace App\Contracts\Shop\Repositories;

use App\Contracts\Shop\Helpers\Filters\FilterContract;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface CategoryRepositoryContract
{
    /**
     * Get category data for category page on storefront
     *
     * @param string         $category_slug Category slug
     * @param FilterContract $filter        Filter instance
     *
     * @return Category
     */
    public function getCategoryData(string $category_slug, FilterContract $filter): Category;

    /**
     * Get category list with data for home page on storefront
     *
     * @param int $limit Limit categories show
     *
     * @return Category
     * @throws ModelNotFoundException
     */
    public function getCategoryList(int $limit): Collection;
}
