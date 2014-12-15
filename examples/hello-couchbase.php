<?php
// Connect to Couchbase Server

$loader = require 'vendor/autoload.php';

use Potato\Client;
use Potato\Document;

$client = new Client(array('bucket' => 'beer-sample'));
$docId = 'aass_brewery-juleol';
$doc = $client->fetchDocument($docId);


var_dump($doc);
var_dump($doc->toJson());
