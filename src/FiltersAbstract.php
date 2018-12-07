<?php

namespace Mukja\LaravelFilters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class FiltersAbstract
{
    /**
     * Current Request.
     * @var $request Illuminate\Http\Request
     */
    protected $request;

    /**
     * Defined Filters
     * @var array
     */
    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Loops through the available filters
     * and returns a new instantiation of the
     * filter class.
     * @param  Builder $builder
     * @return object
     */
    public function filter(Builder $builder)
    {
        foreach ($this->getFilters() as $filter => $value) {
            $this->resolveFilter($filter)->filter($builder, $value);
        }

        return $builder;
    }

    /**
     * Adds a new filter to the available
     * filters.
     * @param array $filters
     */
    public function add (array $filters)
    {
        $this->filters = array_merge($this->filters, $filters);

        return $this;
    }

    /**
     * Returns the instantiated class
     * by the given filter.
     * @param  string $filter
     * @return object
     */
    protected function resolveFilter($filter)
    {
        return new $this->filters[$filter];
    }

    /**
     * Returns all the available filters.
     * @return string
     */
    public function getFilters()
    {
        return $this->filterFilters($this->filters);
    }

    /**
     * Filters through the available filters
     * by the request and removes the ones
     * which are empty
     * @param  array $filters
     * @return array
     */
    public function filterFilters($filters)
    {
        return array_filter($this->request->only(array_keys($this->filters)));
    }
}
