<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Morilog\Jalali\Jalalian;

trait FilterTrait
{
    abstract public function scopeFilter(Builder $query, $filter = []);

    public function scopeFilterStrColumn(Builder $builder, Collection $filter, $name)
    {
        return $builder->when($filter->get($name), function ($query) use ($name, $filter) {
            $query->where($name, 'like', "%{$filter->get($name)}%");
        });
    }

    public function scopeFilterColumn(Builder $builder, Collection $filter, $name, $operator = '=')
    {
        return $builder->when(($filter->get($name)), function ($query) use ($name, $filter, $operator) {
            if ($filter->get($name) === 'null')
                $query->whereNull($name);
            else
                $query->where($name, $operator, $filter->get($name));
        });
    }

    public function scopeFilterBetweenColumn(Builder $builder, Collection $filter, $name)
    {
        $builder->when($filter->get('min_' . $name), function ($query) use ($name, $filter) {
            $query->where($name, '>=', $filter->get('min_' . $name));
        });

        $builder->when($filter->get('max_' . $name), function ($query) use ($name, $filter) {
            $query->where($name, '<=', $filter->get('max_' . $name));
        });

        return $builder;
    }



    public function scopeFilterRelColumn(Builder $query, Collection $filters, string $item)
    {
        if ($filters->get($item) && is_array($filters->get($item)) && array_filter($filters->get($item))) {
            $item_filter = array_filter($filters->get($item));
            $query->whereHas(Str::camel($item), function ($q) use ($item_filter) {
                $q->filter($item_filter);
            });
        }

        return $query;
    }
    
}
