<?php

namespace Demand\Filter\Word;

class CamelCaseToUnderscore extends CamelCaseToSeparator
{
    public function __construct()
    {
        $options = array('separator' => '_');
        parent::__construct($options);
    }
}