<?php
/**
 * Potato: The simple ODM for Couchbase on PHP
 *
 * Client class is based on Basement written by Michael Nitschinger.
 *
 */
namespace Potato;

/**
 * The `Client`class is your main entry point when working with your Couchbase cluster.
  */
class Client {

    /**
     * Holds the current config.
     * @var array
     */
    private $config;

    /** @var \CouchbaseCluster */
    private $cluster;
    /** @var \CouchbaseBucket */
    private $bucket;

    /**
     * Create and connect to the Couchbase cluster.
     *
     * If no user is provided (or is null), then it is assumed to be the same
     * name as the bucket. This is the common behavior and seldom needs to be
     * changed.
     */
    public function __construct($options = array()) {
        $defaults = array(
            'name' => null,
            'uri' => 'http://127.0.0.1:8091',
            'bucket' => 'default',
            'password' => '',
            'username' => '',
            'class' => 'Potato\Document',
            'persist' => false,
            'connect' => true,
            'transcoder' => 'json',
            'environment' => 'development'
        );
        $this->config = $options + $defaults;
        $this->config['name'] = $this->config['name'] ?: $this->config['bucket'];
        $this->init();
    }

    /**
     * Initialize the client. Sub-classes should override this function.
     */
    public function init()
    {
        $this->cluster = new \CouchbaseCluster($this->config['uri'],$this->config['username'],$this->config['password']);
        $this->bucket = $this->cluster->openBucket($this->config['bucket']);
    }

    /**
     * Returns the current configuration.
     */
    public function getConfig() {
        return $this->config;
    }
    /**
     * Read or set a transcoder.
     */
    public function transcoder($name = null, $transcoder = array()) {
        if($name == null) {
            return $this->transcoders;
        } elseif(empty($transcoder) && isset($this->transcoders[$name])) {
            return $this->transcoders[$name];
        } elseif(empty($transcoder)) {
            return false;
        }
        if(!isset($transcoder['encode']) || !isset($transcoder['decode']) ||
            !is_callable($transcoder['encode']) || !is_callable($transcoder['decode'])) {
            $msg = "A transcoder must provide 'encode' and 'decode' callable functions";
            throw new InvalidArgumentException($msg);
        }
        $this->transcoders[$name] = $transcoder;
    }

    /**
     * Disconnect the current connection.
     */
    public function disconnect() {
//        $this->connection = null;
//        unset(static::$connections[$this->config['name']]);
//        return true;
    }
    /**
     * Returns the sate of the connection.
     */
    public function connected() {
        return $this->connection !== null;
    }
    /**
     * Returns the connection resource if connected.
     */
    public function connection() {
        return $this->connected() ? $this->connection : false;
    }

    public function fetchDocument($id, array $options = array())
    {
        $options = $options + $this->config;
        $result = $this->bucket->get($id);
        $clazz = $options['class'];
        $doc = new $clazz($id,$result->value);
        $doc->setCas($result->cas);
        return $doc;
    }
}
