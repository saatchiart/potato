<?php

namespace Demand\Potato\Filter;

abstract class AbstractFilter implements Filter
{
    /**
     * Is unicode enabled?
     *
     * @var bool
     */
    private static $unicodeSupportEnabled = null;

    /**
     * Is Unicode Support Enabled Utility function
     *
     * @return bool
     */
    public static function isUnicodeSupportEnabled()
    {
        if (self::$unicodeSupportEnabled === null) {
            self::determineUnicodeSupport();
        }
        return self::$unicodeSupportEnabled;
    }

    /**
     * Method to cache the regex needed to determine if unicode support is available
     *
     * @return bool
     */
    protected static function determineUnicodeSupport()
    {
        self::$unicodeSupportEnabled = defined('PREG_BAD_UTF8_OFFSET_ERROR') && preg_match('/\pL/u', 'a') == 1;
    }

    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    }

    /**
     * @param  array $options
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            if (method_exists($this, $setter)) {
                $this->{$setter}($value);
            } else {
                throw new \InvalidArgumentException(
                    sprintf(
                        'The option "%s" does not have a matching %s setter method or options[%s] array key',
                        $key,
                        $setter,
                        $key
                    )
                );
            }
        }
        return $this;
    }
}