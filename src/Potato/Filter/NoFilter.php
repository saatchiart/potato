<?php

namespace Demand\Potato\Filter;

class NoFilter implements Filter
{
    /**
     * Do not filter the data at all. Return the passed parameter.
     *
     * @param mixed $value
     * @throws Exception
     * @return mixed
     */
    public function filter($value)
    {
        return $value;
    }

}