<?php

namespace Core;

use Demand\Core\Object;
use PHPUnit_Framework_TestCase;

class ObjectTest extends PHPUnit_Framework_TestCase
{
    public function testObjectIsJsonEncodable()
    {
        $sampleArray = [
            'first_name'    => 'John',
            'age'           => 32,
            'is_married'    => true,
            'empty_field'   => '',
            'children'      => ['Marry', 'Sam']
        ];

        $object = new Object($sampleArray);

        $actual = json_encode($object);
        $expected = json_encode($sampleArray);

        $this->assertEquals($expected, $actual);
    }

    public function testEmptyObjectIsJsonEncodable()
    {
        $actual = json_encode(new Object());
        $expected = json_encode([]);

        $this->assertEquals($expected, $actual);
    }
}