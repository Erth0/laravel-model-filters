<?php

namespace Mukja\LaravelFilters;

use Illuminate\Database\Eloquent\Builder;

abstract class FilterAbstract
{
    abstract public function filter(Builder $builder, $value);

    /**
     * Pre defined filter mappings
     * @return array
     */
    public function mappings ()
    {
        return [];
    }

    /**
     * Resolves the filter by the given key.
     * @param  string $key
     * @return mixed
     */
    public function resolveFilterValue($key)
    {
        return array_get($this->mappings(), $key);
    }
}
