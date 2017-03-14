<?php
/**
 * Decorate a couchbase view query.
 *
 * This is because CouchbaseViewQuery changed all methods to final. ಠ_ಠ
 *
 * @copyright Leaf Group, Ltd. All Rights Reserved.
 */
namespace Demand\Potato;

use CouchbaseViewQuery;

class ViewQuery
{
    /** Force a view update before returning data */
    const UPDATE_BEFORE = CouchbaseViewQuery::UPDATE_BEFORE;
    /** Allow stale views */
    const UPDATE_NONE = CouchbaseViewQuery::UPDATE_NONE;
    /** Allow stale view, update view after it has been accessed. */
    const UPDATE_AFTER = CouchbaseViewQuery::UPDATE_AFTER;

    const ORDER_ASCENDING = CouchbaseViewQuery::ORDER_ASCENDING;
    const ORDER_DESCENDING = CouchbaseViewQuery::ORDER_DESCENDING;

    /** @var Couchbase\ViewQuery */
    protected $viewQuery;

    /**
     * Compose the instance with a view query so we can wrap final methods.
     *
     * @param Couchbase\ViewQuery|null $viewQuery (default: null)
     */
    public function __construct(CouchbaseViewQuery $viewQuery = null)
    {
        parent::__construct();
        $this->viewQuery = $viewQuery ?: new CouchbaseViewQuery();
    }

    /**
     * Creates a new Couchbase ViewQuery instance for performing a view query.
     *
     * @param string $designDocumentName the name of the design document to query
     * @param string $viewName the name of the view to query
     *
     * @return ViewQuery
     */
    public static function from($designDocumentName, $viewName)
    {
        return CouchbaseViewQuery::from($designDocumentName, $viewName);
    }

    /**
     * Creates a new Couchbase ViewQuery instance for performing a spatial query.
     *
     * @param string $designDocumentName the name of the design document to query
     * @param string $viewName the name of the view to query
     *
     * @return SpatialViewQuery
     */
    public static function fromSpatial($designDocumentName, $viewName)
    {
        return CouchbaseViewQuery::fromSpatial($designDocumentName, $viewName);
    }

    /**
     * Returns associative array, representing the View query.
     *
     * @return array object which is ready to be serialized.
     */
    public function encode()
    {
        return $this->viewQuery->encode();
    }

    /**
     * Limits the result set to a specified number rows.
     *
     * @param int $limit maximum number of records in the response
     *
     * @return ViewQuery
     */
    public function limit($limit)
    {
        return $this->viewQuery->limit($limit);
    }

    /**
     * Skips a number o records rom the beginning of the result set
     *
     * @param int $skip number of records to skip
     * @return ViewQuery
     */
    public function skip($skip)
    {
        return $this->viewQuery->skip($skip);
    }

    /**
     * Specifies the mode of updating to perorm before and after executing the query
     *
     * @param int $consistency use constants UPDATE_BEFORE, UPDATE_NONE, UPDATE_AFTER
     * @return ViewQuery
     *
     * @see \Couchbase\ViewQuery::UPDATE_BEFORE
     * @see \Couchbase\ViewQuery::UPDATE_NONE
     * @see \Couchbase\ViewQuery::UPDATE_AFTER
     */
    public function consistency($consistency)
    {
        return $this->viewQuery->consistency($consistency);
    }

    /**
     * Orders the results by key as specified
     *
     * @param int $order use contstants ORDER_ASCENDING, ORDER_DESCENDING
     * @return ViewQuery
     */
    public function order($order)
    {
        return $this->viewQuery->order($order);
    }

    /**
     * Specifies whether the reduction function should be applied to results of the query.
     *
     * @param bool $reduce
     * @return ViewQuery
     */
    public function reduce($reduce)
    {
        return $this->viewQuery->reduce($reduce);
    }

    /**
     * Group the results using the reduce function to a group or single row.
     *
     * Important: this setter and groupLevel should not be used together in the
     * same ViewQuery. It is sufficient to only set the grouping level only and
     * use this setter in cases where you always want the highest group level
     * implictly.
     *
     * @param bool $group
     * @return ViewQuery
     *
     * @see \Couchbase\ViewQuery#groupLevel
     */
    public function group($group)
    {
        return $this->viewQuery->group($group);
    }

    /**
     * Specify the group level to be used.
     *
     * Important: group() and this setter should not be used together in the
     * same ViewQuery. It is sufficient to only use this setter and use group()
     * in cases where you always want the highest group level implictly.
     *
     * @param int $groupLevel the number of elements in the keys to use
     * @return ViewQuery
     *
     * @see \Couchbase\ViewQuery#group
     */
    public function groupLevel($groupLevel)
    {
        return $this->viewQuery->groupLevel($groupLevel);
    }

    /**
     * Restict results of the query to the specified key
     *
     * @param mixed $key key
     * @return ViewQuery
     */
    public function key($key)
    {
        return $this->viewQuery->key($key);
    }

    /**
     * Restict results of the query to the specified set of keys
     *
     * @param array $keys set of keys
     * @return ViewQuery
     */
    public function keys($keys)
    {
        return $this->viewQuery->keys($keys);
    }

    /**
     * Specifies a range of the keys to return from the index.
     *
     * @param mixed $startKey
     * @param mixed $endKey
     * @param bool $inclusiveEnd
     * @return ViewQuery
     */
    public function range($startKey, $endKey, $inclusiveEnd = false)
    {
        return $this->viewQuery->range($startKey, $endKey, $inclusiveEnd);
    }

    /**
     * Specifies start and end document IDs in addition to range limits.
     *
     * This might be needed for more precise pagination with a lot of documents
     * with the same key selected into the same page.
     *
     * @param string $startKeyDocumentId document ID
     * @param string $endKeyDocumentId document ID
     * @return ViewQuery
     */
    public function idRange($startKeyDocumentId, $endKeyDocumentId)
    {
        return $this->viewQuery->idRange($startKeyDocumentId, $endKeyDocumentId);
    }

    /**
     * Specifies custom options to pass to the server.
     *
     * Note that these options are expected to be already encoded.
     *
     * @param array $customParameters parameters
     * @return ViewQuery
     *
     * @see https://developer.couchbase.com/documentation/server/current/rest-api/rest-views-get.html
     *   Getting Views Information
     */
    public function custom($customParameters)
    {
        return $this->viewQuery->custom($customParameters);
    }
}
