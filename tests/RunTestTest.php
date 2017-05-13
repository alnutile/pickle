<?php


namespace GD\Tests;

use GD\RunTest;

class RunTestTest extends TestCase
{


    public function setUp()
    {
        parent::setUp();
    }

    public function testShouldGetPathToFileTestFromPathOfFeature()
    {


        $this->markTestSkipped("HUGE Refactor");
        $runner = new RunTest($this->app);

        $runner->handleDomain("/tests/features/foo.feature");

        $this->assertEquals($runner->getClassFileNameAndPath(), "tests/Unit/FooTest.php");
    }

    public function testShouldGetDirectoryAndReturnFilesInDirectory()
    {
    }
}
