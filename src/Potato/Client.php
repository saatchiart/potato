<?php
/**
 * Potato: The simple ODM for Couchbase on PHP
 *
 * Client class is based on Basement written by Michael Nitschinger.
 *
 */
namespace Demand\Potato;

/**
 * The `Client`class is your main entry point when working with your Couchbase cluster.
 *
 * TODO: Handle fetching multiple ids at one time
 * TODO: Offer include_docs functionality in view query results
 */
class Client
{
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
            'class' => 'Demand\Potato\Document',
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
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Return the underlying couchbase bucket.
     *
     * @return \CouchbaseBucket
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param array|string $ids
     * @param array $options
     * @return array|Document
     */
    public function fetchDocument($ids, array $options = array())
    {
        $options = $options + $this->config;
        $response = $this->bucket->get($ids);
        if (is_array($response)) {
            $docs = array();
            foreach ($response as $id => $result) {
                $docs[] = $this->hydrate($id,$result,$options);
            }
            return $docs;
        } else {
            return $this->hydrate($ids,$response,$options);
        }
    }

    /**
     * @param Document $doc
     * @param array $options
     * @return mixed
     */
    public function saveDocument(Document $doc, array $options = array())
    {
        $id = $doc->getKey();
        $response = $this->bucket->upsert($id,$doc->toJson(),$options);
        return $response;
    }

    /**
     * Creates a new ViewQuery instance for performing a view query.
     *
     * @param string $designDoc The name of the design document to query.
     * @param string $name The name of the view.
     * @param array $options Custom options to be set.
     * @return ViewQuery
     */
    public function createViewQuery($designDoc, $name, $options = array())
    {
        $query = ViewQuery::from($designDoc,$name);
        $query->custom($options);
        return $query;
    }

    /**
     * @param string $designDoc
     * @param string $name
     * @param array $options
     * @return ViewQuery
     */
    public function createSpatialQuery($designDoc, $name, $options = array())
    {
        $query = ViewQuery::fromSpatial($designDoc,$name);
        $query->custom($options);
        return $query;
    }

    /**
     * Execute a view or spatial query.
     *
     * @param mixed $query
     */
    public function query($query)
    {
        return $this->bucket->query($query);
    }

    /**
     * @param string $id
     * @param \CouchbaseMetaDoc $result
     * @param array $option
     */
    private function hydrate($id,$result,&$options)
    {
        $clazz = $options['class'];
        /** @var Document $doc */
        $doc = new $clazz($id,$result->value);
        $doc->setCas($result->cas);
        return $doc;
    }
}
