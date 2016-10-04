<?php
/**
 * ClientTest
 *
 * @copyright 2016 Demand Media, Inc. All Rights Reserved.
 */
namespace Demand\Potato;

use CouchbaseCluster;
use CouchbaseBucket;
use CouchbaseMetaDoc;
use Demand\Potato\Document as PotatoDocument;

/**
 * Unit Tests.
 *
 * @see Demand\Potato\Client
 *
 * @author Michael Funk <mike.funk@demandmedia.com>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Demand\Potato\Client class under test
     */
    protected $client;

    /**
     * @var CouchbaseCluster
     */
    protected $cluster;

    /**
     * Phpunit before each test.
     */
    public function setUp()
    {
        // mock dependencies
        $this->cluster = $this->prophesize(CouchbaseCluster::class);
        // instantiate class under test
        $this->client = new Client(['cluster' => $this->cluster->reveal()]);
    }

    public function testFetchDocumentWithHydrationDisabled()
    {
        // dummy data
        $id = 8686920;
        // it should open a bucket
        $bucket = $this->prophesize(CouchbaseBucket::class);
        $this->cluster->openBucket('default')->willReturn($bucket->reveal());
        // it should get a response
        $response = $this->prophesize(CouchbaseMetaDoc::class);
        $expected = $response->reveal();
        $bucket->get($id)->willReturn($expected);
        // one assoc array should be returned
        // call and verify
        $actual = $this->client->fetchDocument($id, ['hydrate' => false]);
        $this->assertEquals($expected, $actual);
    }

    public function testFetchMultipleDocumentsWithHydrationDisabled()
    {
        // dummy data
        $ids = [8686920];
        // it should open a bucket
        $bucket = $this->prophesize(CouchbaseBucket::class);
        $this->cluster->openBucket('default')->willReturn($bucket->reveal());
        // it should get a response
        $response = $this->prophesize(CouchbaseMetaDoc::class);
        $expected = [$response->reveal()];
        $bucket->get($ids)->willReturn($expected);
        // multiple assoc arrays should be returned
        // call and verify
        $actual = $this->client->fetchDocument($ids, ['hydrate' => false]);
        $this->assertEquals($expected, $actual);
    }
}
