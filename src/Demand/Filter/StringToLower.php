<?php

namespace Demand\Filter;

class StringToLower extends AbstractFilter
{
    /**
     * @var string
     */
    private $encoding;

    /**
     * Constructor
     *
     * @param string|array $encodingOrOptions OPTIONAL
     */
    public function __construct($encodingOrOptions = null)
    {
        $defaults = array('encoding' => null);
        // set any passed options
        if ($encodingOrOptions !== null) {
            if (is_string($encodingOrOptions)) {
                $this->setEncoding($encodingOrOptions);
            } elseif (is_array($encodingOrOptions)) {
                $encodingOrOptions = array_merge($defaults,$encodingOrOptions);
                parent::__construct($encodingOrOptions);
            } else {
                throw new \InvalidArgumentException(sprintf("%s requires a string or array passed in constructor",get_class($this)));
            }
        } else {
            parent::__construct($defaults);
        }
    }

    /**
     *
     * Returns the string $value, converting characters to lowercase as necessary
     *
     * If the value provided is non-scalar, the value will remain unfiltered
     *
     * @param  string $value
     * @return string|mixed
     */
    public function filter($value)
    {
        if (!is_scalar($value)) {
            return $value;
        }
        $value = (string) $value;
        if ($this->encoding !== null) {
            return mb_strtolower($value, $this->encoding);
        }
        return strtolower($value);
    }

    /**
     * Set the input encoding for the given string
     *
     * @param  string|null $encoding
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setEncoding($encoding = null)
    {
        if ($encoding !== null) {
            if (!function_exists('mb_strtolower')) {
                throw new Exception(sprintf(
                    '%s requires mbstring extension to be loaded',
                    get_class($this)
                ));
            }
            $encoding    = strtolower($encoding);
            $mbEncodings = array_map('strtolower', mb_list_encodings());
            if (!in_array($encoding, $mbEncodings)) {
                throw new \InvalidArgumentException(sprintf(
                    "Encoding '%s' is not supported by mbstring extension",
                    $encoding
                ));
            }
        } elseif ($encoding === null && function_exists('mb_internal_encoding')) {
            $encoding = mb_internal_encoding();
        }
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * Returns the set encoding
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }
}