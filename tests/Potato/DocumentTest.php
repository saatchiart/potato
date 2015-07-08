<?php

namespace Potato;

use Demand\Potato\Document;

class DocumentTest extends \PHPUnit_Framework_TestCase
{
    private $content_str = <<<EOD
{
    "item1": "string",
    "item2": 100,
    "item3": true,
    "item_arr": [
         { "line1": "a", "line2": 100 },
         { "line1": "b", "line2": 101 }
    ],
    "favorites":[10,101,30]
}
EOD;
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

    public function testDocumentArray()
    {
        $this->assertJson($this->content_str);
        $doc = new Document('tst',$this->content_str);
//        $arr = $doc->item_arr;
//        var_dump($arr);
        $this->assertEquals(100,$doc->item_arr[0]->line2);
        $this->assertEquals(101,$doc->item_arr[1]->line2);
        $this->assertEquals(30,$doc->favorites[2]);
    }

    public function testJsonStringCreate()
    {
        $this->assertJson($this->content_str);
        $doc = new Document('tst',$this->content_str);
        $this->assertJsonStringEqualsJsonString($doc->toJson(),$this->content_str);
    }
}
