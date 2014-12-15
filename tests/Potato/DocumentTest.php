<?php

namespace Potato;

class DocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testDocumentCreate()
    {
        $content_str = <<<EOD
{
    "first_name": "mike",
    "last_name": "shindle",
    "prevent_from_selling_art": true,
    "other_attributes": {
        "email_open_count":"20",
        "email_click_count":"10",
        "unset_me_too" : "i'm a hidden little value"
        },
    "favorites":[10,101,30],
    "unset_me": "i should be gone",
    "level2": {
        "unset_me_three": "what happens next?"
        }
}
EOD;
        $this->assertJson($content_str);

        $doc = new Document('344780', $content_str);
        $json = $doc->toJson();
        $this->assertJson($content_str,$json);
//
//        // test key
//        $this->logMsg('has key test');
//        $this->logMsg('first_name: ' . $attr->hasKey('first_name'));
//        $this->logMsg('open count: ' . $attr->hasKey('email_open_count'));
//        $this->logMsg('random: ' . ($attr->hasKey('random') ? 'true' : 'false'));
//        $this->logMsg('other_attributes: ' . ($attr->hasKey('other_attributes') ? 'true' : 'false'));
//        $this->logMsg('other_attributes:random: ' . ($attr->hasKey('other_attributes:random') ? 'true' : 'false'));
    }
}
