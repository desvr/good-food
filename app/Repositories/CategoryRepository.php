<?php

namespace App\Repositories;

use App\Contracts\Shop\Helpers\Filters\FilterContract;
use App\Contracts\Shop\Repositories\CategoryRepositoryContract;
use App\Helpers\Filters\ProductFilter;
use App\Models\Category;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class CategoryRepository implements CategoryRepositoryContract
{
    /**
     * Get category data for category page on storefront
     *
     * @param string              $category_slug Category slug
     * @param FilterContract|null $filter        Filter instance
     *
     * @return Category
     *
     * @throws ModelNotFoundException
     */
    public function getCategoryData(string $category_slug, FilterContract $filter = null): Category
    {
        if ($filter) {
            $category_data = Category::query()->where(['slug' => $category_slug])->with([
                'products' => function (Builder $query) use ($filter) {
                    $query->active()->isProduct()->orderBy('id');

                    /** @var ProductFilter $filter */
                    $filter->applyFilter($query);

                    $query->latest();
                },
                'products.children.variations' => function (Builder $query) {
                    $query->select(
                        'product_variations.product_id AS product_id',
                        'product_features.id AS product_feature_id',
                        'product_features.name AS product_feature_name',
                        'product_features.value AS product_feature_value',
                        'product_feature_values.id AS product_feature_value_id',
                        'product_feature_values.name AS product_feature_value_name',
                        'product_feature_values.value AS product_feature_value_value',
                    )
                        ->join('product_features', 'product_variations.feature_id', '=', 'product_features.id')
                        ->join('product_feature_values', 'product_variations.feature_value_id', '=', 'product_feature_values.id')
                        ->orderBy('product_feature_value_value');
                },
                'products.variations' => function (Builder $query) {
                    $query->select(
                        'product_variations.product_id AS product_id',
                        'product_features.id AS product_feature_id',
                        'product_features.name AS product_feature_name',
                        'product_features.value AS product_feature_value',
                        'product_feature_values.id AS product_feature_value_id',
                        'product_feature_values.name AS product_feature_value_name',
                        'product_feature_values.value AS product_feature_value_value',
                    )
                        ->join('product_features', 'product_variations.feature_id', '=', 'product_features.id')
                        ->join('product_feature_values', 'product_variations.feature_value_id', '=', 'product_feature_values.id')
                        ->orderBy('product_feature_value_value');
                },
                'products.options',
            ])->first();
        } else {
            $category_data = Cache::tags(['categories', 'products'])->remember(
                \App\Enum\Cache\Repository\Category::CACHE_KEY_CATEGORY_DATA . $category_slug,
                \App\Enum\Cache\Repository\Category::CACHE_TTL_CATEGORY_DATA,
                function() use ($category_slug) {
                    return Category::query()->where(['slug' => $category_slug])->with([
                        'products' => function (Builder $query) {
                            $query->active()->isProduct()->orderBy('id');
                        },
                        'products.children.variations'=> function (Builder $query) {
                            $query->select(
                                'product_variations.product_id AS product_id',
                                'product_features.id AS product_feature_id',
                                'product_features.name AS product_feature_name',
                                'product_features.value AS product_feature_value',
                                'product_feature_values.id AS product_feature_value_id',
                                'product_feature_values.name AS product_feature_value_name',
                                'product_feature_values.value AS product_feature_value_value',
                            )
                                ->join('product_features', 'product_variations.feature_id', '=', 'product_features.id')
                                ->join('product_feature_values', 'product_variations.feature_value_id', '=', 'product_feature_values.id')
                                ->orderBy('product_feature_value_value');
                        },
                        'products.variations' => function (Builder $query) {
                            $query->select(
                                'product_variations.product_id AS product_id',
                                'product_features.id AS product_feature_id',
                                'product_features.name AS product_feature_name',
                                'product_features.value AS product_feature_value',
                                'product_feature_values.id AS product_feature_value_id',
                                'product_feature_values.name AS product_feature_value_name',
                                'product_feature_values.value AS product_feature_value_value',
                            )
                                ->join('product_features', 'product_variations.feature_id', '=', 'product_features.id')
                                ->join('product_feature_values', 'product_variations.feature_value_id', '=', 'product_feature_values.id')
                                ->orderBy('product_feature_value_value');
                        },
                    ])->first();
                }
            );
        }


        return $category_data;
    }

    /**
     * Get category list with data for home page on storefront
     *
     * @param int $limit Limit categories show
     *
     * @return Collection
     *
     * @throws ModelNotFoundException
     */
    public function getCategoryList(int $limit): Collection
    {
        $category_list = Cache::tags(['categories', 'products'])->remember(
            \App\Enum\Cache\Repository\Category::CACHE_KEY_CATEGORY_LIST,
            \App\Enum\Cache\Repository\Category::CACHE_TTL_CATEGORY_LIST,
            function() use ($limit) {
                return Category::with([
                    'products' => function (Builder $query) use ($limit) {
                        $query->active()->isProduct()->orderBy('id')->limit($limit - 1);
                    },
                    'products.children.variations'=> function (Builder $query) {
                        $query->select(
                            'product_variations.product_id AS product_id',
                            'product_features.id AS product_feature_id',
                            'product_features.name AS product_feature_name',
                            'product_features.value AS product_feature_value',
                            'product_feature_values.id AS product_feature_value_id',
                            'product_feature_values.name AS product_feature_value_name',
                            'product_feature_values.value AS product_feature_value_value',
                        )
                            ->join('product_features', 'product_variations.feature_id', '=', 'product_features.id')
                            ->join('product_feature_values', 'product_variations.feature_value_id', '=', 'product_feature_values.id')
                            ->orderBy('product_feature_value_value');
                    },
                    'products.variations' => function (Builder $query) {
                        $query->select(
                            'product_variations.product_id AS product_id',
                            'product_features.id AS product_feature_id',
                            'product_features.name AS product_feature_name',
                            'product_features.value AS product_feature_value',
                            'product_feature_values.id AS product_feature_value_id',
                            'product_feature_values.name AS product_feature_value_name',
                            'product_feature_values.value AS product_feature_value_value',
                        )
                            ->join('product_features', 'product_variations.feature_id', '=', 'product_features.id')
                            ->join('product_feature_values', 'product_variations.feature_value_id', '=', 'product_feature_values.id')
                            ->orderBy('product_feature_value_value');
                    }
                ])->withCount([
                    'products' => function (Builder $query) {
                        $query->active()->isProduct();
                    }
                ])->get();
            }
        );

        return $category_list;
    }
}
