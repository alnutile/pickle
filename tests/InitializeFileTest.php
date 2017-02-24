<?php


namespace GD\Tests;


class InitializeFileTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @test
     * @expectedException \GD\Exceptions\MustSetFileNameAndPath
     */
    public function should_throw_filename_error() {
        $gd = new \GD\GherkinToDusk();
        $gd->setComponent('initialize-file');
        $gd->handle();
    }

    /**
     * @test
     */
    public function should_limit_to_one_file() {

    }

    /**
     * @test
     */
    public function should_convert_yaml_to_array() {

    }


}