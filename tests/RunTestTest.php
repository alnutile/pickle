<?php


namespace GD\Tests;

use GD\RunTest;

class RunTestTest extends TestCase
{


    public function setUp()
    {
        parent::setUp();

        $this->removeFolder();

        $this->makeFolderAndFile();
    }

    protected function makeFolderAndFile()
    {
        $this->file->makeDirectory(__DIR__ . '/Feature');
        $this->file->put(__DIR__ . '/Feature/FooTest.php', "Foo");
    }

    protected function removeFolder()
    {
        if ($this->file->exists(__DIR__ . '/Feature')) {
            $this->file->deleteDirectory(__DIR__ . '/Feature');
        }
    }

    public function tearDown()
    {
        parent::tearDown();
        \Mockery::close();
        $this->removeFolder();
    }

    public function testShouldGetPathToFileTestFromPathOfFeature()
    {
        /** @var RunTest $runner */
        $runner = \Mockery::mock(RunTest::class, [$this->app])->makePartial();
        $runner->shouldAllowMockingProtectedMethods();
        $runner->shouldReceive('runCommand')->andReturnNull();
        //$runner = new RunTest($this->app);

        $runner->handleDomain("/tests/features/foo.feature");

        $this->assertEquals($runner->fullPathToDestinationFile(), __DIR__ . "/Feature/FooTest.php");
        $command = sprintf("vendor/bin/phpunit %s", __DIR__ . "/Feature/FooTest.php");
        $this->assertEquals($runner->getCommand(), $command);
    }

    public function testShouldGetDirectoryAndReturnFilesInDirectory()
    {
        /** @var RunTest $runner */
        $runner = \Mockery::mock(RunTest::class, [$this->app])->makePartial();
        $runner->shouldAllowMockingProtectedMethods();
        $runner->shouldReceive('runCommand')->andReturnNull();
        //$runner = new RunTest($this->app);

        $runner->handleBrowser("/tests/features/foo.feature");

        $this->assertEquals($runner->fullPathToDestinationFile(), __DIR__ . "/Browser/FooTest.php");
        $command = sprintf("php artisan dusk %s", __DIR__ . "/Browser/FooTest.php");
        $this->assertEquals($runner->getCommand(), $command);
    }
}
