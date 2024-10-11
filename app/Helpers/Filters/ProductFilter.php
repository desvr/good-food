<?php

namespace App\Helpers\Filters;

use App\Contracts\Shop\Helpers\Filters\FilterContract;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ProductFilter implements FilterContract
{
    /** @var string $apply_filter_type Apply product filter type */
    private string $apply_filter_type;

    /** @var string $apply_filter_type Apply product filter value */
    private string $apply_filter_value;

    /** @var array $already_filter Already applied product filters */
    private array $already_filter;

    public function __construct(array $filter_data) {
        if (!empty($filter_data['already_product_filters'])) {
            $already_filter = $filter_data['already_product_filters'];

            if (is_string($already_filter)) {
                parse_str($already_filter, $prepared_already_filter);
                $already_filter = $prepared_already_filter;
            }
        }

        $this->already_filter = $already_filter ?? [];
        $this->apply_filter_type = $filter_data['product_filter_type'] ?? '';
        $this->apply_filter_value = $filter_data['product_filter_value'] ?? '';
    }

    /**
     * Apply filter
     *
     * @param Builder $builder Builder
     */
    public function applyFilter(Builder $builder): void
    {
        $filter_data = $this->getFilterData();

        if (empty($filter_data)) {
            return;
        }

        foreach ($filter_data as $filter_values) {
            $builder->whereHas('options', function ($builder) use ($filter_values) {
                $builder->whereIn('option_id', $filter_values);
            });
        }
    }

    /**
     * Get filter data from class properties
     *
     * @return array
     */
    private function getFilterData(): array
    {
        if (empty($this->apply_filter_type) || empty($this->apply_filter_value)) {
            return !empty($this->already_filter) ? $this->already_filter : [];
        }

        if (empty($this->already_filter)) {
            return [
                $this->apply_filter_type => [
                    $this->apply_filter_value
                ]
            ];
        }

        if (
            !isset($this->already_filter[$this->apply_filter_type])
            || !in_array($this->apply_filter_value, $this->already_filter[$this->apply_filter_type])
        ) {
            $this->already_filter[$this->apply_filter_type][] = $this->apply_filter_value;
        } else {
            unset($this->already_filter[$this->apply_filter_type][
                array_search($this->apply_filter_value, $this->already_filter[$this->apply_filter_type])
            ]);

            if (empty($this->already_filter[$this->apply_filter_type])) {
                unset($this->already_filter[$this->apply_filter_type]);
            }
        }

        return $this->already_filter;
    }
}
