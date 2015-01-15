<?php

namespace Demand\Potato\Filter;

class FilterChain implements Filter
{
    const CHAIN_APPEND  = 'append';
    const CHAIN_PREPEND = 'prepend';

    /**
     * Filter chain
     *
     * @var array
     */
    private $filters = array();

    /**
     * Adds a filter to the chain
     *
     * @param  Filter $filter
     * @param  string $placement
     * @return FilterChain Provides a fluent interface
     */
    public function addFilter(Filter $filter, $placement = self::CHAIN_APPEND)
    {
        if ($placement == self::CHAIN_PREPEND) {
            array_unshift($this->filters, $filter);
        } else {
            $this->filters[] = $filter;
        }
        return $this;
    }

    /**
     * Add a filter to the end of the chain
     *
     * @param  Filter $filter
     * @return FilterChain Provides a fluent interface
     */
    public function appendFilter(Filter $filter)
    {
        return $this->addFilter($filter, self::CHAIN_APPEND);
    }
    /**
     * Add a filter to the start of the chain
     *
     * @param  Filter $filter
     * @return FilterChain Provides a fluent interface
     */
    public function prependFilter(Filter $filter)
    {
        return $this->addFilter($filter, self::CHAIN_PREPEND);
    }
    /**
     * Get all the filters
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Returns $value filtered through each filter in the chain
     *
     * Filters are run in the order in which they were added to the chain (FIFO)
     *
     * @param  mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        $valueFiltered = $value;
        foreach ($this->filters as $filter) {
            $valueFiltered = $filter->filter($valueFiltered);
        }
        return $valueFiltered;
    }
}