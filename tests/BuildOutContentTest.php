<?php


namespace GD\Tests;

use GD\Helpers\BuildOutContent;

class BuildOutContentTest extends TestCase
{


    use BuildOutContent;

    public function testShouldAddParentMethodInfoToArray()
    {

        $name = 'testFoo';

        $results = $this->getParentLevelContent($name);

        $what_it_should_be = [
            "method_name" => $name
        ];

        $this->assertEquals($what_it_should_be["method_name"], $results["method_name"]);
    }

    public function testShouldAddStepMethodInfoToArray()
    {

        $name = 'foo';

        $results = $this->getStepLevelContent($name);

        $what_it_should_be = [
            'method_name' => "foo",
            'reference' => '$this->foo()',
        ];

        $this->assertEquals($what_it_should_be, $results);
        $this->assertArrayHasKey("method_name", $results);
        $this->assertEquals("foo", $what_it_should_be["method_name"]);
    }
}
