<?php

$loader = require dirname(__DIR__) . '/vendor/autoload.php';

class UserAttribute extends \Demand\Potato\Document
{
    public function __construct($key,$data)
    {
        $valueTypes = array(
            'is_safe' => 'bool',
            'other_attributes' => array(
                'email_open_count' => 'int',
                'email_click_count' => 'int'
            ),
            'prevent_from_selling_art' => 'bool',
        );
        parent::__construct($key,$data,$valueTypes);
    }
}

function logMsg($msg)
{
    echo round(memory_get_usage()/1024/1024, 1) . 'MB';
    echo " - ";
    if ($msg instanceof Exception) {
        echo $msg->getMessage() . "\n";
        echo $msg->getTraceAsString();
        echo "\n";
    } else {
        print_r($msg);
        echo "\n";
    }
}



$content_str = <<<EOD
{
    "first_name": "mike",
    "last_name": "shindle",
    "prevent_from_selling_art": "true",
    "other_attributes": {
        "email_open_count":"20",
        "email_click_count":"10",
        "unset_me_too" : "i'm a hidden little value",
        "level2": {
            "unset_me_three": "what happens next?"
        }
    },
    "favorites":[10,101,30],
    "unset_me": "i should be gone"
}
EOD;

logMsg('begin processing');
$doc = new Demand\Potato\Document('344780', $content_str, $valueTypes);
logMsg('the document object');
logMsg($doc);
// test key
logMsg('has key test');
logMsg('first_name: ' . ($doc->hasKey('first_name') ? 'true' : 'false'));
logMsg('other_attributes: ' . ($doc->hasKey('otherAttributes') ? 'true' : 'false'));
logMsg('random: ' . ($doc->hasKey('random') ? 'true' : 'false'));
// value test
logMsg('value test');
logMsg('first_name: ' . $doc->firstName);
logMsg('email open count: ' . $doc->otherAttributes->emailOpenCount);

// add parameters
$doc->isSafe = true;
// display JSON doc
logMsg('display JSON');
logMsg($doc->toJson());
