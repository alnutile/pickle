<?php


namespace GD\Tests;


class ExperimentsTest extends \PHPUnit_Framework_TestCase
{
    protected $foo;

    /**
     * @test
     * @preserveGlobalState enabled
     */
    public function testScenarioExampleDepends()
    {
        $this->assertTrue(true);
        $this->foo = 'bar';
    }

    /**
     * @depends testScenarioExampleDepends
     * @preserveGlobalState
     */
    public function testShouldGetToStepOne()
    {
        $this->assertTrue(true);
    }

    /**
     * @depends testShouldGetToStepOne
     */
    public function testShouldGetToStepTwo()
    {
        $this->assertTrue(true);
    }

    /**
     * @depends testShouldGetToStepTwo
     */
    public function testShouldFailSinceNotComplete()
    {
        $this->markTestIncomplete("This should fail the tests");
    }


}