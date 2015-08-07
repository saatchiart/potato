<?php

namespace Demand\Core;
use JsonSerializable;
use ArrayAccess;

class Object implements ArrayAccess, JsonSerializable
{
    /** @var array */
    protected $_values;

    public function __construct($fields=null)
    {
        $this->_values = array();
        if (is_array($fields)) {
            foreach($fields as $key => $value) {
                $this->initField($key,$value);
            }
        }
    }

    // Standard accessor magic methods
    public function __set($k, $v)
    {
        $this->_values[$k] = $v;
    }

    public function __isset($k)
    {
        return isset($this->_values[$k]);
    }

    public function __unset($k)
    {
        unset($this->_values[$k]);
    }

    public function __get($k)
    {
        if (array_key_exists($k, $this->_values)) {
            return $this->_values[$k];
        } else {
//            $class = get_class($this);
//            error_log("Notice: Undefined property of $class instance: $k");
            return null;
        }
    }

    // field to handle key/value initialization
    public function initField($key,$value)
    {
        $this->$key = $value;
    }

    // ArrayAccess methods
    public function offsetSet($k, $v)
    {
        $this->$k = $v;
    }

    public function offsetExists($k)
    {
        return array_key_exists($k, $this->_values);
    }

    public function offsetUnset($k)
    {
        unset($this->_values[$k]);
    }

    public function offsetGet($k)
    {
        return array_key_exists($k, $this->_values) ? $this->_values[$k] : null;
    }

    public function keys()
    {
        return array_keys($this->_values);
    }

    // Pretend to have late static bindings, even in PHP 5.2
    protected function _lsb($method)
    {
        $class = get_class($this);
        $args = array_slice(func_get_args(), 1);
        return call_user_func_array(array($class, $method), $args);
    }

    public function toJson()
    {
        if (defined('JSON_PRETTY_PRINT'))
            return json_encode($this->toArray(true), JSON_PRETTY_PRINT);
        else
            return json_encode($this->toArray(true));
    }

    public function jsonSerialize()
    {
        return $this->toArray(true);
    }
    public function toString()
    {
        return $this->toJson();
    }

    public function toArray($recursive=false)
    {
        if ($recursive)
            return self::convertObjectToArray($this->_values);
        else
            return $this->_values;
    }

    /**
     * @param $values
     * @return array
     */
    public static function convertObjectToArray($values)
    {
        $results = array();
        foreach ($values as $k => $v) {
            if ($v instanceof Object) {
                $results[$k] = $v->toArray(true);
            }
            else if (is_array($v)) {
                $results[$k] = self::convertObjectToArray($v);
            }
            else {
                $results[$k] = $v;
            }
        }
        return $results;
    }

    // This unfortunately needs to be public to be used in Util.php
    public static function scopedConstructFrom($class, $values)
    {
        $obj = new $class($values);
        return $obj;
    }

    public static function constructFrom($values)
    {
        $class = get_called_class();
        return self::scopedConstructFrom($class, $values);
    }
}