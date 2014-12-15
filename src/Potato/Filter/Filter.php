<?php

namespace Potato\Filter;

interface Filter
{
    /**
     * Returns the result of filtering $value
     *
     * @param mixed $value
     * @throws Exception
     * @return mixed
     */
    public function filter($value);
}