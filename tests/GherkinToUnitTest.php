<?php


namespace GD\Tests;


class GherkinToUnitTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @test
     */
    public function should_set_source_path() {

        $gd = new \GD\GherkinToDusk();
        $gd->setSourcePath(__DIR__ . '/fixtures/');

        \PHPUnit_Framework_Assert::assertContains('fixtures', $gd->getSourcePath(),
            "Setting path should work");

        $gd = new \GD\GherkinToDusk();
        \PHPUnit_Framework_Assert::assertNotContains('fixtures', $gd->getSourcePath(),
            "Default Setting path should work");
    }

    /**
     * @test
     * @expectedException \GD\Exceptions\SourcePathNotFoundException
     */
    public function should_handle_files_not_found() {
        $gd = new \GD\GherkinToDusk();
        $gd->setSourcePath('foo');
        $gd->handle();
    }



    /**
     * @test
     */
    public function should_save_as_related_domain_unit_test() {

    }

}