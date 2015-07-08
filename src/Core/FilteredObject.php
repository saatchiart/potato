<?php

namespace Demand\Core;

use Demand\Filter\Filter;
use Demand\Filter\FilterChain;
use Demand\Filter\StringToLower;
use Demand\Filter\Word\CamelCaseToUnderscore;

class FilteredObject extends Object
{
    /**
     * An array describing the value types of an object
     * @var array
     */
    private $valueTypes;

    /**
     * Filter to use to transform property names into JSON document keys
     * @var Filter
     */
    private $keyFilter;

    /**
     * Creates an array backed object from either a json encoded string,
     * a std class object, or an array. The creation util will recurse through objects
     * and turn associative arrays (objects) into an ArrayObject. Filter, if null, defaults
     * to @see CamelCaseToUnderscore() and @see StringToLower().
     *
     * @param mixed $data
     * @param array $valueTypes
     * @param Filter $keyFilter
     * @throws Exception
     */
    public function __construct($data, $valueTypes = array(), Filter $keyFilter = null)
    {
        // make sure all sub-object are of this type
        // set our value types
        $this->setValueTypes($valueTypes);
        // assign the keyFilter we want to use. Default to CamelCaseToUnderscore() and StringToLower()
        if (!$keyFilter) {
            $keyFilter = new FilterChain();
            $keyFilter->appendFilter(new CamelCaseToUnderscore())->appendFilter(new StringToLower());
        }
        $this->setKeyFilter($keyFilter);
        $arr = null;
        if (is_string($data)) {
            $arr = json_decode($data, true);
        } elseif (is_object($data) && get_class($data) == "stdClass") {
            $arr = (array)$data;
        } elseif (is_array($data)) {
            $arr = $data;
        } else {
            throw new Exception('Data type must be either JSON string or stdClass: ' . print_r($data, true));
        }
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                if ($this->isAssoc($v)) {
                    $vt = (array_key_exists($k, $valueTypes)) ? $valueTypes[$k] : array();
                    $arr[$k] = new FilteredObject($v, $vt, $this->keyFilter);
                } else {
                    $arr[$k] = array_filter($v,function($elem) { return new FilteredObject($elem,null,$this->keyFilter); });
                }
            }
        }
        parent::__construct($arr);
    }

    /**
     * @param $key
     * @throws Exception
     */
    public function __get($key)
    {
        $key = $this->normalizeKey($key);
        return parent::__get($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $key = $this->normalizeKey($key);
        if (is_array($value) && $this->isAssoc($value)) {
            $vt = (array_key_exists($key, $this->valueTypes)) ? $this->valueTypes[$key] : array();
            $value = new FilteredObject($value, $vt, $this->keyFilter);
        } elseif (array_key_exists($key, $this->valueTypes) && is_string($this->valueTypes[$key])) {
            settype($value, $this->valueTypes[$key]);
        }
        parent::__set($key,$value);
    }

    /**
     * @param string $key
     * @throws Exception
     */
    public function __unset($key)
    {
        $key = $this->normalizeKey($key);
        parent::__unset($key);
    }

    /**
     * @param string $key
     * @return string
     * @throws Exception
     */
    protected function normalizeKey($key)
    {
        if (!is_string($key)) {
            throw new Exception('Given key must be of type string!');
        }
        return $this->keyFilter->filter($key);
    }

    public function hasKey($key)
    {
        $key = $this->normalizeKey($key);
        return array_key_exists($key,$this->_values);
    }

    /**
     * Determine if an array is associative by looking at all the keys. If any key
     * is a string, the array is considered an associative array.
     *
     * @param array $array
     * @return bool
     */
    protected function isAssoc(array $array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }

    public function setValueTypes($types)
    {
        $this->valueTypes = $types;
    }

    public function getValueTypes()
    {
        return $this->valueTypes;
    }

    /**
     * @return Filter
     */
    public function getKeyFilter()
    {
        return $this->keyFilter;
    }

    /**
     * @param Filter $keyFilter
     */
    public function setKeyFilter($keyFilter)
    {
        $this->keyFilter = $keyFilter;
    }
}