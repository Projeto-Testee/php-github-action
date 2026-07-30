<?php

declare(strict_types=1);

namespace App\QueryFilters;


class Status extends Filter
{
    protected function applyFilter($builder)
    {
        $value = request($this->filterName());

        if (!in_array($value, config('todo_settings.status_types', []))) {
            return $builder;
        }

        return $builder->where($this->filterName(), $value);

    }
}
