<?php

namespace Demand\Potato\Filter\Word;

use Demand\Potato\Filter\Exception;
use Demand\Potato\Filter\PregReplace;

abstract class AbstractSeparator extends PregReplace
{
    protected $separator = null;

    /**
     * Constructor which sets the default separator to space.
     *
     * @param  array $options
     * @return void
     */
    public function __construct(array $options = array())
    {
        $defaults = array('separator' => ' ');
        $options = array_merge($defaults,$options);
        parent::__construct($options);
    }

    /**
     * Sets a new seperator
     *
     * @param string $separator
     * @return $this
     */
    public function setSeparator($separator)
    {
        if ($separator == null) {
            throw new Exception('"' . $separator . '" is not a valid separator.');
        }
        $this->separator = $separator;
        return $this;
    }

    /**
     * Returns the actual set seperator
     *
     * @return  string
     */
    public function getSeparator()
    {
        return $this->separator;
    }
}