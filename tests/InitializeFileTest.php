<?php


namespace GD\Tests;

use Behat\Gherkin\Keywords\CucumberKeywords;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Parser;
use GD\GherkinToDusk;
use Illuminate\Filesystem\Filesystem;

class InitializeFileTest extends TestCase
{

    public function setUp()
    {
        $this->instantiateGD();

        $this->setupFolderAndFile();
    }

    /**
     * @test
     */
    public function shouldConvertGherkinFileToMatchingUnitTest()
    {

        $file_to_convert = $this->getFixtureFeature('test_naming.feature');

        $feature = $this->gd->getParser()->parse($file_to_convert);

        \PHPUnit_Framework_Assert::assertInstanceOf(\Behat\Gherkin\Node\FeatureNode::class, $feature);

        \PHPUnit_Framework_Assert::assertEquals(2, count($feature->getScenarios()));

        $scenario_1 = $feature->getScenarios()[0];

        \PHPUnit_Framework_Assert::assertInstanceOf(\Behat\Gherkin\Node\ScenarioNode::class, $scenario_1);

        \PHPUnit_Framework_Assert::assertEquals(1, count($scenario_1->getTags()));

        $step_1 = $scenario_1->getSteps()[0];

        \PHPUnit_Framework_Assert::assertInstanceOf(\Behat\Gherkin\Node\StepNode::class, $step_1);

        \PHPUnit_Framework_Assert::assertContains("I have a profile created", $step_1->getText());

        \PHPUnit_Framework_Assert::assertEquals("Given", $step_1->getKeyword());
    }

    /**
     * @test
     */
    public function shouldSetProperDuskTestName()
    {

        $path = 'tests/features/test_naming.feature';
        $this->gd->setPathToFeature($path)
            ->initializeFeature();

        \PHPUnit_Framework_Assert::assertEquals("TestNamingTest", $this->gd->getDuskTestName());
    }

    /**
     * @test
     */
    public function shouldContainTextFromFeatureConvertedIntoUnitTest()
    {

        $file_to_convert = $this->getFixtureFeature('test_naming.feature');
        $path = 'tests/features/test_naming.feature';
        $this->gd->setPathToFeature($path)
            ->initializeFeature();

        \PHPUnit_Framework_Assert::assertNotNull($this->gd->getFeatureContent());
        \PHPUnit_Framework_Assert::assertEquals($this->gd->getFeatureContent(), $file_to_convert);

        \PHPUnit_Framework_Assert::assertInstanceOf(\Behat\Gherkin\Node\FeatureNode::class, $this->gd->getParsedFeature());

        \PHPUnit_Framework_Assert::assertNotEmpty($this->gd->getDuskClassAndMethods());
        \PHPUnit_Framework_Assert::assertArrayHasKey('parent', $this->gd->getDuskClassAndMethods()[0]);
        \PHPUnit_Framework_Assert::assertEquals('testEditProfile', $this->gd->getDuskClassAndMethods()[0]['parent']);
    }

    /**
     * @test
     */
    public function shouldHaveArrayWithTextForParentAndChild()
    {
        $file_to_convert = $this->getFixtureFeature('test_naming.feature');
        $path = 'tests/features/test_naming.feature';
        $this->gd->setPathToFeature($path)
            ->initializeFeature();

        $this->assertCount(2, $this->gd->getDuskClassAndMethods());

        $this->assertArrayHasKey('parent', $this->gd->getDuskClassAndMethods()[0]);
        $this->assertEquals('testEditProfile', $this->gd->getDuskClassAndMethods()[0]['parent']);
        $this->assertEquals('public function testEditProfile() {',
            $this->gd->getDuskClassAndMethods()[0]['parent_content']['method'][0]);
        $this->assertArrayHasKey('parent_content', $this->gd->getDuskClassAndMethods()[0]);
        $this->assertArrayHasKey('steps', $this->gd->getDuskClassAndMethods()[0]);
        $this->assertArrayHasKey('method', $this->gd->getDuskClassAndMethods()[0]['steps'][0]);
        $this->assertEquals('public function givenIHaveAProfileCreated() {', $this->gd->getDuskClassAndMethods()[0]['steps'][0]['method'][0]);
        $this->assertEquals('$this->markTestIncomplete(\'Time to code\');', $this->gd->getDuskClassAndMethods()[0]['steps'][0]['method'][1]);

    }

    /**
     * @test
     */
    public function shouldCreateFile()
    {

    }

    /**
     * @test
     */
    public function shouldPutTheMethodsInTheFileAsNeeded()
    {
    }


    /**
     * @test
     */
    public function shouldGetTaggedOnly()
    {

    }

    protected function tearDown()
    {

        $this->cleanUpFile();
    }

    private function getFixtureFeature($string)
    {
        return $this->file->get(__DIR__ . sprintf('/features/%s', $string));
    }


}
