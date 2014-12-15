<?php

namespace Potato;

class Document extends DocumentObject
{
    /**
     * The unique key of the document.
     * @var string
     */
    private $key;

    /**
     * Contains the CAS value for the document if there is one.
     * @var string
     */
    private $cas = null;

    /**
     * The value when populated from a view result.
     */
    private $value = null;

    public function __construct($key, $data, $valueTypes = array())
    {
        $this->key = strval($key);
        parent::__construct($data,$valueTypes);
    }

    public function getKey() {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getCas()
    {
        return $this->cas;
    }

    /**
     * @param mixed $cas
     */
    public function setCas($cas)
    {
        $this->cas = $cas;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }


}