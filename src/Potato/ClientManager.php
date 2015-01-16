<?php

namespace Demand\Potato;

/**
 * Class ClientManager
 * @package Demand\Potato
 *
 * A singleton client manager when running without dependency injection.
 */
class ClientManager
{
    /** @var array */
    private $defaults = array(
        'name' => null,
        'uri' => 'http://127.0.0.1:8091',
        'bucket' => 'default',
        'password' => '',
        'username' => '',
        'class' => 'Demand\Potato\Document',
        'persist' => false,
        'connect' => true,
        'transcoder' => 'json',
        'environment' => 'development'
    );
    /** @var array  */
    private $clients= array();

    /**
     * Return a singleton instance of this class
     * @return ClientManager
     */
    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }
        return $instance;
    }

    /**
     * @param array $defaults
     */
    public function setDefaults($defaults = array())
    {
        $this->defaults = array_merge($this->defaults,$defaults);
    }

    public function getClient($name, $options = array())
    {
        // make sure our arg is valid
        if (!is_string($name))
            throw new \InvalidArgumentException('$name must be specified and a string.');
        // if we have it, return it
        if (array_key_exists($name,$this->clients)) {
            return $this->clients[$name];
        }
        // looks like we need to make the connection
        $config = $options + $this->defaults;
        // make sure the name we pass to client is the same as the one we will use.
        if ($config['name'] != $name) $config['name'] = $name;
        $this->clients['name'] = new Client($config);
        return $this->clients['name'];
    }

    /**
     * Prevent instantiation directly from other classes
     */
    protected function __construct()
    {

    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}