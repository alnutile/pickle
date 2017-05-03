<?php

namespace GD;

use Behat\Gherkin\Gherkin;
use Behat\Gherkin\Keywords\CucumberKeywords;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Loader\GherkinFileLoader;
use Behat\Gherkin\Loader\YamlFileLoader;
use Behat\Gherkin\Parser;
use GD\Exceptions\MustSetFileNameAndPath;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class GherkinToDusk extends BaseGherkinToDusk
{

    /**
     * @var Parser
     */
    protected $parser;

    protected $component = false;

    protected $string_contents = null;

    /**
     * Yml Content of a test yml
     * @var array
     */
    protected $feature_content;



    public function initializeFeature()
    {

        if ($this->context == 'domain') {
            $this->featureToUnit();
        }
    }

    protected function featureToUnit()
    {
//        $this->feature_content =
//            $this->getFilesystem()->get($this->getFileNameAndPath());
//
//
//        $keywords = new CucumberKeywords($this->getFileNameAndPath());
//        $lexer = new Lexer($keywords);
//        $gherkin = new Parser($lexer);
//
//        $fileload = new GherkinFileLoader($gherkin);
//        $results = $fileload->supports($this->getFileNameAndPath());
//        $results = $fileload->load($this->getFileNameAndPath());

        $this->loadFileContent();


        dd($this->feature_content);


        //dd($gherkin->parse($this->feature_content, []));
    }

    /**
     * @return Parser
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @param Parser $parser
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
    }

    /**
     * @return boolean
     */
    public function isComponent()
    {
        return $this->component;
    }

    /**
     * @param boolean $component
     */
    public function setComponent($component)
    {
        $this->component = $component;
    }

    /**
     * @return mixed
     */
    public function getFeatureContent()
    {
        return $this->feature_content;
    }

    /**
     * @param mixed $feature_content
     */
    public function setFeatureContent($feature_content)
    {
        $this->feature_content = $feature_content;
    }

    private function loadFileContent()
    {
        $this->feature_content = $this->filesystem->get(getcwd() . DIRECTORY_SEPARATOR . $this->getPathToFeature());
    }
}
