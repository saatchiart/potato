<?php
// Connect to Couchbase Server

$loader = require dirname(__DIR__) . '/vendor/autoload.php';

use Demand\Potato\Client;
use Demand\Potato\Document;

class UserDetail extends Document
{
    public function __construct($key,$data)
    {
        $valueTypes = array(
            'billing_address' => array(
                'postal' => 'string'
            ),
            'random_attribute' => 'string',
            'random_int' => 'int',
            'random_bool' => 'bool'
        );
        parent::__construct($key,$data,$valueTypes);
    }
}

$client = new Client(array('bucket' => 'user', 'class' => '\UserDetail'));
$docId = 'boy';
$doc = $client->fetchDocument($docId);


//var_dump($doc);
$doc->gender = 'male';
$doc->randomAttribute = 'something random';
$doc->randomInt = "123";
$doc->randomBool = 1;
$doc->billingAddress->postal = 90404;

var_dump($doc->toJson());
