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
            "method" => [
                'public function testFoo() {',
                '}'
            ]
        ];

        $this->assertEquals($what_it_should_be["method"], $results["method"]);
    }

    public function testShouldAddStepMethodInfoToArray()
    {

        $name = 'foo';

        $results = $this->getStepLevelContent($name);

        $what_it_should_be = [
            'method' =>
                [
                    "public function foo() {",
                    "\$this->markTestIncomplete('Time to code');",
                    "}"
                ],
            'reference' => '$this->foo'
        ];

        $this->assertEquals($what_it_should_be, $results);
        $this->assertArrayHasKey("method", $results);
        $this->assertEquals("public function foo() {", $results['method'][0]);
        $this->assertEquals("\$this->markTestIncomplete('Time to code');", $results['method'][1]);
        $this->assertEquals("}", $results['method'][2]);
    }
}
