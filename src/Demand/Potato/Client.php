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

    /** @var \CouchbaseCluster|null */
    private $cluster = null;
    /** @var \CouchbaseBucket|null */
    private $bucket = null;

    /**
     * Create and connect to the Couchbase cluster.
     *
     * If no user is provided (or is null), then it is assumed to be the same
     * name as the bucket. This is the common behavior and seldom needs to be
     * changed.
     *
     * @param array $options (default: array()) config options
     */
    public function __construct($options = array())
    {
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
            'environment' => 'development',
            'cluster' => null,
            'hydrate' => true
        );
        $this->config = $options + $defaults;
        $this->config['name'] = $this->config['name'] ?: $this->config['bucket'];

        // allow injecting a cluster, otherwise one will be created for you in init.
        if ($this->config['cluster']) {
            $this->cluster = $this->config['cluster'];
        }
        $this->init();
    }

    /**
     * Initialize the client. Sub-classes should override this function.
     */
    public function init()
    {
        if (!$this->cluster) {
            $this->cluster = new \CouchbaseCluster($this->config['uri'],$this->config['username'],$this->config['password']);
        }
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
        if ($this->bucket === null) {
            $this->bucket = $this->cluster->openBucket($this->config['bucket']);
        }
        return $this->bucket;
    }

    /**
     * @param array|string $ids
     * @param array $options
     * @return Document[]|Document|\CouchbaseMetaDoc[]|\CouchbaseMetaDoc
     * Depending on whether you're fetching multiple ids and whether hydration
     * is enabled
     */
    public function fetchDocument($ids, array $options = array())
    {
        $options = $options + $this->config;
        $response = $this->getBucket()->get($ids);
        if (!$options['hydrate']) {
            return $response;
        }
        if (is_array($response)) {
            $docs = array();
            foreach ($response as $id => $result) {
                $docs[] = $this->hydrate($id, $result, $options);
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
        $id = $doc->getId();
        $response = $this->getBucket()->upsert($id,$doc->toJson(),$options);
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
        return $this->getBucket()->query($query);
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
