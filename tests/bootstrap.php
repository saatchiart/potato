<?php

$loader = require dirname(__DIR__) . "/vendor/autoload.php";
$loader->addPsr4('Potato\\', __DIR__.'/Potato');

date_default_timezone_set('UTC');