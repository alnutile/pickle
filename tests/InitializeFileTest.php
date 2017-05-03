<?php


namespace GD\Tests;




use Behat\Gherkin\Keywords\CucumberKeywords;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Parser;
use GD\GherkinToDusk;
use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class InitializeFileTest extends TestCase
{


    /**
     * @var Filesystem
     */
    protected $file;

    /**
     * @var GherkinToDusk
     */
    protected $gd;

    public function setUp() {
        $this->file = new Filesystem();
        $this->gd = new \GD\GherkinToDusk($this->file);

        $il8n = __DIR__ . '/../src/i18n.yml';

        $keywords = new CucumberKeywords($il8n);

        $keywords->setLanguage('en');

        $lexer = new Lexer($keywords);

        $parser = new Parser($lexer);

        $this->gd->setParser($parser);


        $this->setupFolderAndFile();
    }

    /**
     * @test
     */
    public function should_convert_gherkin_file_to_matching_unit_test() {

        $file_to_convert = $this->file->get(__DIR__ . '/fixtures/features/test_naming.feature');

        $feature = $this->gd->getParser()->parse($file_to_convert);

        \PHPUnit_Framework_Assert::assertInstanceOf(\Behat\Gherkin\Node\FeatureNode::class, $feature);


//        $parser = new Definition('Behat\Gherkin\Parser', array(
//            new Reference('gherkin.lexer')
//        ));
//        $lex = new Definition('Behat\Gherkin\Lexer', array(
//            new Reference('gherkin.keywords')
//        ));
        /**
         * How to get this into an array
         * How to get the Scenario out of it
         * How to get the steps out of it cleanly
         *
         */

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

    private function setupFolderAndFile()
    {
        $path = $this->gd->getSourcePath();

        /**
         * This can be come a path relative issue
         */
        if(!$this->file->exists($path)) {
            $this->file->makeDirectory($path, 0777, true);
            $this->file->copy(
                $this->gd->getSourcePath() . '/features/test_naming.feature',
                $this->gd->getSourcePath() . '/test_naming.feature');
        }

    }

    protected function tearDown() {
        $path = $this->gd->getSourcePath() . '/tests/features';

        /**
         * This can be come a path relative issue
         */
        if($this->file->exists($path)) {
            $this->file->deleteDirectory($path);
        }
    }


}