<?php


namespace GD\Tests;

class WriteFileTest extends TestCase
{


    public function setUp()
    {
        parent::setUp();
        $this->instantiateGD();

        $this->setupFolderAndFile();
    }

    public function testShouldMakeFileWithCorrectName() {
        $path = 'tests/features/test_naming.feature';
        $this->gd->setPathToFeature($path)
            ->initializeFeature();

        //dd($this->gd->getDuskClassAndMethods());

    }

    public function testFileShouldMatchExpected() {

    }


}
