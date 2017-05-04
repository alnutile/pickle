<?php


namespace GD\Tests;

use Behat\Gherkin\Keywords\CucumberKeywords;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Parser;
use GD\GherkinToDusk;
use Illuminate\Filesystem\Filesystem;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class InitializeFileTest extends PHPUnit_Framework_TestCase
{


    /**
     * @var Filesystem
     */
    protected $file;

    /**
     * @var GherkinToDusk
     */
    protected $gd;

    public function setUp()
    {
        $this->file = new Filesystem();
        $il8n = __DIR__ . '/../src/i18n.yml';
        $keywords = new CucumberKeywords($il8n);
        $keywords->setLanguage('en');
        $lexer = new Lexer($keywords);
        $parser = new Parser($lexer);
        $this->gd = new \GD\GherkinToDusk($this->file, $parser);

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

    private function setupFolderAndFile()
    {
        $path = $this->gd->getSourcePath();

        /**
         * This can be come a path relative issue
         */
        if (!$this->file->exists($path)) {
            $this->file->makeDirectory($path, 0777, true);
            $this->file->copy(
                $this->gd->getSourcePath() . '../fixtures/features/test_naming.feature',
                $this->gd->getSourcePath() . 'test_naming.feature'
            );
        }
    }

    protected function tearDown()
    {
        $path = $this->gd->getSourcePath();

        /**
         * This can be come a path relative issue
         */
        if ($this->file->exists($path)) {
            $this->file->deleteDirectory($path);
        }
    }

    private function getFixtureFeature($string)
    {
        return $this->file->get(__DIR__ . sprintf('/features/%s', $string));
    }
}
